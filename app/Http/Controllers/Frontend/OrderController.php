<?php

namespace App\Http\Controllers\Frontend;

use App;
use App\Facades\CartService;
use App\Http\Controllers\Controller;
use App\Models\Gateway;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderMeta;
use App\Models\Plan;
use App\Models\Product;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\VerifyUser;
use App\Notifications\Order\OrderCreated;
use App\Notifications\Order\OrderUpdated;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Instamojo\Instamojo;
use Omnipay\Omnipay;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use Swift_TransportException;

class OrderController extends Controller
{
    public function submit(Request $request)
    {
        $purchased_items = [];

        if (CartService::getCartItems()->count() > 0) {
            foreach (CartService::getCartItems() as $row) {
                $price = round($row->total, 2, PHP_ROUND_HALF_DOWN);

                $purchased_items[] = [
                    'name' => $row->name,
                    'price' => $price,
                    'quantity' => $row->qty
                ];
            }
        } else {
            App::abort(422, trans('cart.Please select a service.'));
        }

        $rules['payment_method'] = 'required';

        if (setting('checkout.billing_info', '1') == '1') {
            $rules['first_name'] = 'required|string|max:255';
            $rules['last_name'] = 'required|string|max:255';
            if (!\auth()->check()) {
                $rules['email'] = 'required|email';
            }
            $rules['usermeta.billing_address'] = 'required|string|max:255';
            $rules['usermeta.billing_city'] = 'required|string|max:255';
            $rules['usermeta.billing_zip'] = 'required|string|regex:/^[\pL\s\pM\pN_-]+$/u|max:50';
            $rules['usermeta.billing_country'] = 'required';
            $rules['usermeta.billing_state'] = 'required';

            $request->validate($rules);

            $this->syncBilling($request);
        }

        $order = auth()->check() ? \Auth()->user()->orders()->create([]) : Order::create(['user_id' => 0]);

        foreach (CartService::getCartItems() as $row) {
            $plan = $product = null;
            $model = $row->model;
            if ($model instanceof Plan) {
                $plan = $model;
                $product = $model->product;
            } elseif ($model instanceof Product) {
                $product = $model;
            }

            $item = new OrderItem([
                'item_id' => $row->id,
                'item_type' => $row->options['type'],
                'price' => $row->price,
            ]);

            $item->order()->associate($order)->save();

            $item->meta()->create([
                'key' => '_item_name',
                'value' => $row->name
            ]);

            if ($row->model instanceof App\Models\Plan || $row->model instanceof Product) {
                $downloads = $product->getMedia('attachments')->pluck('id')->toArray();

                if (sizeof($downloads) > 0) {
                    $order->syncMedia($downloads, 'downloads');
                }
            }

            $item->meta()->create([
                'key' => '_subtotal',
                'value' => $row->price
            ]);

            $item->meta()->create([
                'key' => '_qty',
                'value' => $row->qty
            ]);

            $item->meta()->create([
                'key' => '_tax_rate',
                'value' => $row->taxRate
            ]);

            $item->meta()->create([
                'key' => '_total',
                'value' => $row->total
            ]);

        }

        $orderMetaData = [
            [
                'key' => 'billing_first_name',
                'value' => $request->input('first_name')
            ],
            [
                'key' => 'billing_last_name',
                'value' => $request->input('last_name')
            ],
            [
                'key' => 'billing_address',
                'value' => $request->input('usermeta.billing_address')
            ],
            [
                'key' => 'billing_city',
                'value' => $request->input('usermeta.billing_city')
            ],
            [
                'key' => 'billing_zip',
                'value' => $request->input('usermeta.billing_zip')
            ],
            [
                'key' => 'billing_country',
                'value' => $request->input('usermeta.billing_country')
            ],
            [
                'key' => 'billing_state',
                'value' => $request->input('usermeta.billing_state')
            ],
            [
                'key' => '_payment_method',
                'value' => $request->input('payment_method')
            ],
            [
                'key' => '_transaction_id',
                'value' => ''
            ],
            [
                'key' => '_customer_ip_address',
                'value' => bwpc_get_client_ip()
            ],
            [
                'key' => '_customer_user_agent',
                'value' => $_SERVER['HTTP_USER_AGENT']
            ],
            [
                'key' => '_order_currency',
                'value' => setting('currency', 'USD')
            ],
            [
                'key' => '_order_total',
                'value' => CartService::getTotal()
            ],
            [
                'key' => '_tax_total',
                'value' => CartService::tax()
            ],
            [
                'key' => '_tax_rate',
                'value' => get_tax_rate()
            ],
            [
                'key' => '_order_subtotal',
                'value' => CartService::getSubtotal()
            ]
        ];

        if (\auth()->guest()) {
            $orderMetaData[] = ['key' => 'billing_email', 'value' => $request->input('email')];
            $orderMetaData[] = ['key' => 'order_key', 'value' => Str::uuid()];
        }

        if (\CartService::discountFloat() > 0) {
            $orderMetaData[] = ['key' => 'discounted', 'value' => '1'];
            $orderMetaData[] = ['key' => 'discount_amount', 'value' => session('discount_amount')];
            $orderMetaData[] = ['key' => 'coupon_code', 'value' => session('coupon_code')];
            $orderMetaData[] = ['key' => 'coupon_id', 'value' => session('coupon_id')];
            $orderMetaData[] = ['key' => 'on_subtotal', 'value' => session('on_subtotal')];
        }

        foreach ($orderMetaData as $meta) {
            $orderMeta = new OrderMeta([
                'key' => $meta['key'],
                'value' => $meta['value']
            ]);

            $orderMeta->order()->associate($order)->save();
        }


        Session::put('order', $order->id);

        Session::save();

        if ($request->has('form_data')) {

            $product_id = CartService::getCartItems()->first()->id;

            $product = Product::find($product_id);

            $form = collect(json_decode($product->form->raw_content, true));

            $insert = [];
            $insertFormData = [];

            foreach ($request->form_data as $key => $value) {

                $input = $form->where('name', $key)->first();

                $insert['label'] = strip_tags($input['label']);
                $insert['type'] = strip_tags($input['type']);
                $insert['value'] = is_array($value) ? implode(',', $value) : $value;

                $insertFormData[] = $insert;
            }

            $order->customFields()->createMany($insertFormData);
        }

        // Free Items
        if (CartService::getCartItems()->first()->model->price == 0) {

            CartService::destroy();

            $status = 'processing';

            $order->update(['status' => $status]);

            return redirect(route('ch_order_submitted', [$order->id]));
        }

        $payment_methods = [
            'paypal' => 'PayPal_Express',
            'stripe' => 'Stripe',
            'razorpay' => 'Razorpay_Checkout',
            'offline_payments' => 'Offline Payments',
            'instamojo' => 'InstaMojo'
        ];

        $payment_method_key = $request->input('payment_method');
        $payment_method = $payment_methods[$request->input('payment_method')];

        if ($payment_method_key == 'offline_payments') {

            CartService::destroy();

            $status = setting('offline_payments.order_status', 'processing');

            $order->update(['status' => $status]);

            return redirect(route('ch_order_submitted', [$order->id]));
        }

        if ($payment_method_key == 'razorpay') {
            $order->update(['status' => 'pending']);

            $displayCurrency = setting('currency', 'USD');

            try {
                $mode = setting('razorpay.sandbox_mode') == 'yes' ? 'sandbox' : 'live';
                $razorClient = new Api(setting('razorpay.' . $mode . '.ki'), setting('razorpay.' . $mode . '.sk'));

                $razorOrder = $razorClient->order->create([
                    'receipt' => $order->id,
                    'amount' => (int)(CartService::getTotal() * 100), // amount in the smallest currency unit
                    'currency' => $displayCurrency,// <a href="https://razorpay.freshdesk.com/support/solutions/articles/11000065530-what-currencies-does-razorpay-support" target="_blank">See the list of supported currencies</a>.)
                    'payment_capture' => '1'
                ]);

                $razorpayOrderId = $razorOrder['id'];

                $amount = $razorOrder['amount'];

                $order->order_details()->updateOrCreate(['key' => 'razorpay_order_id'], ['value' => $razorpayOrderId]);
                $order->order_details()->updateOrCreate(['key' => 'razorpay_order_amount'], ['value' => $amount]);
            } catch (\Exception $exception) {
                dd($exception->getMessage());
            }

            CartService::destroy();

            return redirect()->route('ch_pay_form', $order->id);
        }

        if ($payment_method_key == 'instamojo') {
            $order->update(['status' => 'pending']);


            try {
                $mode = setting('instamojo.sandbox_mode') == 'yes' ? 'sandbox' : 'live';

                $InstaMojoEndpoint = ($mode == 'sandbox') ? 'https://test.instamojo.com/api/1.1/' : null;

                $instaMojoApi = new Instamojo(setting('instamojo.' . $mode . '.api'), setting('instamojo.' . $mode . '.at'), $InstaMojoEndpoint);

                $InstaMojoResponse = $instaMojoApi->paymentRequestCreate(array(
                    "purpose" => "Order ID: " . $order->id,
                    "amount" => CartService::getTotal(),
                    "send_email" => true,
                    "email" => \auth()->user()->email,
                    "redirect_url" => route('ch_order_submitted', $order->id)
                ));

                $order->order_details()->updateOrCreate(['key' => 'instamojo_payment_id'], ['value' => $InstaMojoResponse['id']]);
                $order->order_details()->updateOrCreate(['key' => 'instamojo_longurl'], ['value' => $InstaMojoResponse['longurl']]);
            } catch (\Exception $exception) {
                dd($exception->getMessage());
            }

            CartService::destroy();

            return redirect()->route('ch_pay_form', $order->id);
        }

        $mode = setting($payment_method_key . '.sandbox_mode') == 'yes' ? 'sandbox' : 'live';

        $description = implode(', ', array_map(function ($el) {
            return $el['name'] . ' (' . ch_format_price($el['price']) . ')';
        }, $purchased_items));

        if ($payment_method_key == 'paypal') {
            $gateway = Omnipay::create($payment_method);

            // Send purchase request
            $response = $gateway->purchase(
                [
                    'amount' => CartService::getTotal(),
                    'currency' => setting('currency', 'USD'),
                    'username' => setting("paypal.username"),
                    'password' => setting("paypal.password"),
                    'signature' => setting("paypal.signature"),
                    'testMode' => setting('paypal.sandbox_mode') == 'yes',
                    'returnUrl' => route('ch_order_submitted', [$order->id]),
                    'cancelUrl' => route('ch_order_cancel', [$order->id]),
                    'notifyUrl' => route('ch_ipn', ['gateway' => 'PayPal', 'order_id' => $order->id]),
                    'transactionId' => $order->id
                ]
            )->send();
            // ->setItems($purchased_items)
        } elseif ($payment_method_key == 'stripe') {
            $gateway = Omnipay::create($payment_method);

            $token = $_POST['stripeToken'];
            $response = $gateway->purchase([
                'amount' => CartService::getTotal(),
                'currency' => setting('currency', 'USD'),
                'apiKey' => setting("stripe.sk"),
                'token' => $token,
                'description' => $description,
                'level3' => [
                    'merchant_reference' => $order->id,
                    'line_items' => $purchased_items,
                ],
            ])->send();

        }

        // Process response
        if ($response->isSuccessful()) {
            CartService::destroy();

            $status = 'processing';
            $tx_id = $response->getTransactionReference();

            if ($payment_method_key == 'coinpayments') {
                $status = 'pending';
                $tx_id = $response->getTransactionId();
            }

//            // Payment was successful
            $order->update(['status' => $status]);
            $meta = $order->order_details()->where('key', '_transaction_id')->first();
            $meta->value = $tx_id;
            $meta->save();

            try {
                if (\auth()->check()) {
                    $order->user->notify(new OrderUpdated($order));
                } else {
                    $guestUser = $this->createGuestUser($order);
                    Notification::send($guestUser, new OrderUpdated($order));
                }
            } catch (Swift_TransportException $exception) {
            }

            return redirect(route('ch_order_submitted', [$order->id]));

        } elseif ($response->isRedirect()) {
            CartService::destroy();
            // Redirect to offsite payment gateway
            $response->redirect();
        } else {
            $order->update(['status' => 'failed']);
            echo $response->getMessage();
        }
//
        exit;


    }


    public function ipn(Request $req, $gateway)
    {

        if ($gateway == 'paypal') {
            $this->paypal_ipn($gateway);
        }
    }

    private function paypal_ipn($gateway)
    {
        $order_id = 0;

        if (isset($_REQUEST['invoice'])) {
            $order_id = intval($_REQUEST['invoice']);
        }

        $mode = setting('paypal.sandbox_mode') == 'yes' ? '.sandbox' : '';

        $order = Order::findOrFail($order_id);

        if (strtolower($gateway == 'paypal')) {

            $raw_post_data = file_get_contents('php://input');
            $raw_post_array = explode('&', $raw_post_data);
            $myPost = array();
            foreach ($raw_post_array as $keyval) {
                $keyval = explode('=', $keyval);
                if (count($keyval) == 2)
                    $myPost[$keyval[0]] = urldecode($keyval[1]);
            }

            // read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'

            $get_magic_quotes_exists = false;

            $req = 'cmd=_notify-validate';
            if (function_exists('get_magic_quotes_gpc')) {
                $get_magic_quotes_exists = true;
            }
            foreach ($myPost as $key => $value) {
                $value = $get_magic_quotes_exists ? urlencode(stripslashes($value)) : urlencode($value);
                $req .= "&$key=$value";
            }

            // Step 2: POST IPN data back to PayPal to validate
            $ch = curl_init('https://ipnpb' . $mode . '.paypal.com/cgi-bin/webscr');
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
            // In wamp-like environments that do not come bundled with root authority certificates,
            // please download 'cacert.pem' from "https://curl.haxx.se/docs/caextract.html" and set
            // the directory path of the certificate as shown below:
            // curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
            if (!($res = curl_exec($ch))) {
                // error_log("Got " . curl_error($ch) . " when processing IPN data");
                curl_close($ch);
                exit;
            }
            curl_close($ch);


            if (strcmp($res, "VERIFIED") == 0) {

                if (strtolower($_POST['payment_status']) == 'completed') {
                    $order->update(['status' => 'processing']);
                    $meta = $order->order_details()->where('key', '_transaction_id')->first();
                    $meta->value = $_REQUEST['txn_id'];
                    $meta->save();

                    $to_user = User::find($order->user->id)->first();
                    $to_user->notify(new OrderUpdated($order));

                } else {
                    $order->update(['status' => 'failed']);
                }
            } else if (strcmp($res, "INVALID") == 0) {
                // IPN invalid, log for manual investigation
                echo "The response from IPN was: <b>" . $res . "</b>";
            }

        }
    }


    public function completed(Request $request, $id)
    {
        if (\auth()->check()) {
            $order = Auth::user()->orders()->findOrFail($id);
        } else {
            $order = Order::findOrFail($id);
        }

        $order_key = $order->getMeta('order_key');

        if (\session('order')) {
            try {
                // Notify the user.
                $admins = User::whereHas('roles', function ($query) {
                    $query->whereName('administrator');
                })->get();

                // Notify the user.
                $admins = User::whereHas('roles', function ($query) {
                    $query->whereName('administrator');
                })->get();

                foreach ($admins as $admin) {
                    $admin->notify(new OrderCreated($order));
                }

                if (\auth()->check()) {
                    \Auth::user()->notify(new OrderCreated($order));
                } else {
                    $guestUser = $this->createGuestUser($order);

                    Notification::send($guestUser, new OrderCreated($order));
                }

            } catch (Swift_TransportException $exception) {
            }
        }

        $request->session()->forget('order');
        Session::save();

        if (strtolower($order->PaymentMethod()) == 'paypal') {
            $gateway = Omnipay::create('PayPal_Express');
            $gateway->initialize(array(
                'username' => setting("paypal.username"),
                'password' => setting("paypal.password"),
                'signature' => setting("paypal.signature"),
                'testMode' => setting('paypal.sandbox_mode') == 'yes',
            ));
            // Send purchase request
            $response = $gateway->completePurchase(
                ['amount' => number_format((float)$order->total(), 2),
                    'currency' => $order->getMeta('_order_currency'),
                    'transactionId' => $order->id
                ]
            )->send();
        }

        if ($order->getMeta('_payment_method') == 'instamojo' && $order->status == 'pending') {
            try {
                $mode = setting('instamojo.sandbox_mode') == 'yes' ? 'sandbox' : 'live';

                $InstaMojoEndpoint = ($mode == 'sandbox') ? 'https://test.instamojo.com/api/1.1/' : null;

                $instaMojoApi = new Instamojo(setting('instamojo.' . $mode . '.api'), setting('instamojo.' . $mode . '.at'), $InstaMojoEndpoint);

                $response = $instaMojoApi->paymentRequestPaymentStatus($request->input('payment_request_id'), $request->input('payment_id'));

                if ($response['payment']['status'] == 'Credit') {
                    $order->update(['status' => 'processing']);
                }

            } catch (Exception $e) {
                exit('Error: ' . $e->getMessage());
            }
        }

        return view('themes.default.thankyou', compact('order', 'order_key'));
    }


    public function cancel(Request $request, $id)
    {
        if (!is_null(session('order'))) {
            $order = \Auth::user()->orders()->find($request->session()->get('order'));
            $order->update(['status' => 'cancelled']);;
            $request->session()->forget('order');
            Session::save();
            return view('themes.default.cancel', compact('order'));
        } else {
            return redirect('/');
        }
    }


    public function failed(Request $request, $id)
    {
        if (!is_null(session('order'))) {
            $order = \Auth::user()->orders()->find($request->session()->get('order'));
            $order->update(['status' => 'failed']);;
            $request->session()->forget('order');
            Session::save();
            $failed = true;
            return view('themes.default.cancel', compact('order', 'failed'));
        } else {
            return redirect('/');
        }
    }

    public function showPaymentForm($id)
    {
        $order = \auth()->user()->orders()->findOrFail($id);

        if ($order->getMeta('_payment_method') == 'razorpay' && $order->status == 'pending') {

            $addons = '';
            if ($order->items->where('item_type', 'addon')->count() > 0) :
                $addons_array = [];
                foreach ($order->items->where('item_type', 'addon') as $addon) :
                    $addons_array[] = $addon->name();
                endforeach;
                $addons = ' (' . implode(',', $addons_array) . ')';
            endif;

            $mode = setting('razorpay.sandbox_mode') == 'yes' ? 'sandbox' : 'live';

            $paymentData = [
                "key" => setting('razorpay.' . $mode . '.ki'),
                "amount" => $order->getMeta('razorpay_order_amount'),
                "name" => setting('app.name'),
                "description" => $order->items->first()->name() . $addons,
                "image" => get_logo_url(),
                "prefill" => [
                    "email" => \auth()->user()->email,
                ],
                "notes" => [
                    "merchant_order_id" => $order->id,
                ],
                "order_id" => $order->getMeta('razorpay_order_id'),
            ];

            return view('themes.default.pay', compact('order', 'paymentData'));
        }

        if ($order->getMeta('_payment_method') == 'instamojo' && $order->status == 'pending') {
            return view('themes.default.pay', compact('order'));
        }

        abort(404);
    }

    public function verifyPayment($id)
    {
        if (\request('razorpay_signature') && \request('razorpay_payment_id')) {

            $mode = setting('razorpay.sandbox_mode') == 'yes' ? 'sandbox' : 'live';
            $razorClient = new Api(setting('razorpay.' . $mode . '.ki'), setting('razorpay.' . $mode . '.sk'));

            $success = true;
            $order = Order::findOrFail($id);
            try {
                $attributes = array(
                    'razorpay_order_id' => $order->getMeta('razorpay_order_id'),
                    'razorpay_payment_id' => \request('razorpay_payment_id'),
                    'razorpay_signature' => \request('razorpay_signature')
                );

                $razorClient->utility->verifyPaymentSignature($attributes);
            } catch (SignatureVerificationError $e) {
                $success = false;
                $error = 'Razorpay Error : ' . $e->getMessage();
            }

            if ($success == true) {

                $tx_id = \request('razorpay_payment_id');


                $order->update(['status' => 'processing']);

                $meta = $order->order_details()->where('key', '_transaction_id')->first();
                $meta->value = $tx_id;
                $meta->save();

                try {
                    $order->user->notify(new OrderUpdated($order));
                } catch (Swift_TransportException $exception) {
                }

                return redirect(route('ch_order_submitted', [$order->id]));
            } else {
                $order->update(['status' => 'failed']);

                return redirect(route('ch_order_failed', [$order->id]));
            }
        }
    }

    private function syncBilling(Request $request)
    {

        $usermeta = $request->input('usermeta');

        if (empty($usermeta)) {
            return;
        }

        // if guest
        if (!\auth()->check()) {
            return;
        }

        \Auth::user()->update([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name')
        ]);

        $user = Auth::user();

        // Loop through all the meta keys we're looking for
        foreach ($usermeta as $key => $value) {

            $newMeta = new UserMeta(['key' => $key]);
            $meta = $user->meta()->where('key', $key)->first() ?: $newMeta->user()->associate($user);

            if (is_array($value)) {
                $value = serialize($value);
            }

            $meta->value = $value;
            $meta->save();

        }
    }

    private function createGuestUser($order)
    {
        return User::make([
            'first_name' => $order->getMeta('billing_first_name'),
            'last_name' => $order->getMeta('billing_last_name'),
            'email' => $order->getMeta('billing_email'),
        ]);
    }

}
