<?php

namespace App\Http\Controllers\Frontend;

use App\Events\Order\OrderCreated as OrderCreatedEvent;
use App\Models\Form;
use App\Models\Gateway;
use App\Models\Plan;
use App\Notifications\Order\OrderCreated;
use App\Models\Addon;
use App\Models\Order;
use App\Models\Role;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\OrderItemMeta;
use App\Models\User;
use App\Models\OrderMeta;
use App\Models\VerifyUser;
use App\Notifications\Order\OrderUpdated;
use App\Repositories\CouponRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;
use Omnipay\Omnipay;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Controller;
use Gloudemans\Shoppingcart\Facades\Cart;
use \App\Models\Tax;
use App\Models\Country;
use App\Services\CartService;
use MediaUploader;
use File;

class CartController extends Controller
{
    /**
     * @var cart
     */
    private $cart;
    /**
     * @var CouponRepository
     */
    private $couponRepository;

    public function __construct(CartService $cart, CouponRepository $couponRepository) {
        $this->cart = $cart;

        $this->couponRepository = $couponRepository;

        $this->upload_path = (string)'local/'.date('Ym');
    }

    public function show(Request $request)
    {
        if ($this->cart->countItems() < 1) {
            return redirect('/');
        }

        $user_state = $user_country = 0;
        $countries = Country::all();
        $states = collect([]);

        $this->cart->refresh();

        if ( \Auth::check() ) {
            $user = \Auth::user();

            $states = (isset(\Auth::user()->billing_country->states)) ? \Auth::user()->billing_country->states : collect([]);

            $user_state   = $user->billing_state ?? 0;
            $user_country = $user->billing_country->id ?? 0;
        }

        $cartItems = $this->cart->getCartItemsTransformed();

        $plan = $product = null;
        $model = $cartItems->first()->model;
        if ($model instanceof Plan) {
            $plan = $model;
            $product = $model->product;
        } elseif ($model instanceof Product) {
            $product = $model;
        }

        $cart = $this->cart;

        return view('themes.default.cart', compact( 'product','plan', 'countries', 'states', 'cart', 'user_state', 'user_country'));

    }

    /**
     * @todo make this more robust.
     *
     * @param Request $request
     * @return type
     */
    public function update(Request $request)
    {
        $cartItems = $this->cart->getCartItemsTransformed();
        $plan = $product = null;
        $model = $cartItems->first()->model;
        if ($model instanceof Plan) {
            $plan = $model;
            $product = $model->product;
        } elseif ($model instanceof Product) {
            $product = $model;
        }

        if ((isset($plan->id) && !$plan->id) || !$product->id) {
            return;
        }

        if ( $request->input('billing_country') || $request->input('billing_state') ) {
            $this->cart->updateTax($request->input('billing_country'), $request->input('billing_state'));
        }

        if (!$request->input('apply_coupon') && !$request->input('remove_coupon')) {
            try {
                foreach ($this->cart->getCartItems() as $key => $item) {
                    if ($item->model instanceof Addon) {
                        $this->cart->removeToCart($item->rowId);
                    }
                }

                if (!empty($request->input('addons'))) {
                    foreach ($request->input('addons') as $addon) {
                        $addonModel = $product->addons()->where('id', $addon)->first();

                        $this->cart->addToCart($addonModel->id, $addonModel->name, 1, $addonModel->price, ["type" => 'addon', 'model' => $addonModel]);
                    }
                }
            } catch (\Exception $ex) {
                return response()->json('Error: ' . $ex->getMessage(), 422);
            }
        }

        $response = $this->applyCoupon($request, $product, $plan);

        if ($request->input('remove_coupon')) {
            session()->forget('discounted');
            session()->forget('discount_amount');
            session()->forget('coupon_code');
            session()->forget('coupon_id');
            session()->forget('on_subtotal');

            return response()->view('core.order.summary', ['cart' => $this->cart, 'product' => $product, 'plan' => $plan]);
        }

        if ($response instanceof JsonResponse && $response->status() != '200') {
            return $response;
        }

        return response()->view('core.order.summary', ['cart' => $this->cart, 'product' => $product, 'plan' => $plan]);
    }

    public function store(Request $request)
    {
        $this->cart->clearCart();
        $type = '';

        if ($request->input('plan_id')) {
            $item = Plan::findOrFail($request->input('plan_id'));
            $type = 'plan';
        } elseif ($request->input('product_id')) {
            $item = Product::findOrFail($request->input('product_id'));
            $type = 'product';
        }

        $this->cart->addToCart($item->id, $item->name, 1, $item->price, ['type' => $type, 'model' => $item]);

        return redirect()->route('ch_cart');
    }

    public function upload(Request $request)
    {
        if (!$request->input('form_id') || !$request->input('input_name')) {
            die('here');
        }

        $form = Form::findOrFail($request->input('form_id'));

        $input = collect(json_decode($form->raw_content))->where('name', $request->input('input_name'))->first();

        $allowed_type = !empty($input->allowed_types) ? explode(', ', $input->allowed_types) : '';

        $file = $request->file('file');

        try {
            $media = MediaUploader::fromSource($file)
                ->toDestination('local', $this->upload_path)
                ->setAllowedExtensions($allowed_type)
                ->upload();
        } catch ( \Exception $exception ) {
            return response()->json(['error' => $exception->getMessage()], 422);
        }


        if( $media->aggregate_type == 'image' ) {
            foreach (config('media.sizes') as $name => $size) {

                $width = ($name != 'medium') ? $size[0] : null;
                $height = $size[1];

                $resized_filename = $media->filename . '.' . $media->extension;
                $abs_path = $media->directory . '/' . $name . '/';

                if (!File::exists(storage_path('app/'.$abs_path))) {
                    try {
                        File::makeDirectory(storage_path('app/'.$abs_path));
                    } catch (\Exception $exception) {
                        die($exception->getMessage());
                    }

                }

                Image::make(storage_path('app/'.$media->getDiskPath()))
                    ->resize($width, $height, function( $constraint ){
                        $constraint->aspectRatio();
                    })->save(storage_path('app/'.$abs_path . $resized_filename));

                $media->meta()->updateOrCreate(['size_name' => $name], ['path' => $abs_path . $resized_filename]);
            }
        }

        return response()->json($media->id, 200);
    }

    public function applyCoupon($request, $product, $plan)
    {
        if (!$request->input('apply_coupon') && !\session('discounted')) {
            return;
        }

        $code = $request->input('code') ? $request->input('code') : \session('coupon_code');

        if ( !$code ) {
            return response()->json(['error' => trans('cart.Valid Coupon code is required.')], 422);
        }

        $coupon = $this->couponRepository->getByCode($code);

        if (!$coupon) {
            return response()->json(['error' => trans('cart.Coupon code is invalid.')], 422);
        }

        if (!is_null($coupon->start_date) && !Carbon::parse($coupon->start_date)->isPast()) {
            return response()->json(['error' => trans('cart.This Coupon code is valid from :date.', ['date' => $coupon->start_date])], 422);
        }

        if (!is_null($coupon->end_date) && Carbon::parse($coupon->end_date)->addDay()->isPast()) {
            return response()->json(['error' => trans('cart.Coupon is expired.')], 422);
        }

        $products = $coupon->products->pluck('id')->toArray();

        if (sizeof($products) > 0 && !in_array($product->id, $products)) {
            return response()->json(['error' => trans('cart.The Coupon is not valid for this product.')], 422);
        }

        if ($coupon->use_once == 1) {
            $coupon_used = Order::where('user_id', \auth()->id())->whereHas('order_details', function (Builder $builder) use($coupon) {
                $builder->where('key', 'coupon_code')->where('value', $coupon->code);
            })->count();

            if ($coupon_used > 0) {
                return response()->json(['error' => trans('cart.You have already used this coupon.')], 422);
            }
        }

        if ($coupon->use_once == 0 && $coupon->max_uses != '') {
            $coupon_used = Order::where('user_id', \auth()->id())->whereHas('order_details', function (Builder $builder) use($coupon) {
                $builder->where('key', 'coupon_code')->where('value', $coupon->code);
            })->count();

            if ($coupon_used >= $coupon->max_uses) {
                return response()->json(['error' => trans('cart.Coupon usage limit has exceeded.')], 422);
            }
        }

        $this->cart->applyCouponCode($coupon);

        return response()->view('core.order.summary', ['cart' => $this->cart, 'product' => $product, 'plan' => $plan]);
    }
}
