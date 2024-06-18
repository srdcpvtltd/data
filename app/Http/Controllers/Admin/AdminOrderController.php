<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Order;
use App\Models\User;
use App\Notifications\Order\MessageAdded;
use App\Notifications\Order\OrderCreated;
use App\Notifications\Order\OrderUpdated;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Laracasts\Flash\Flash;

class AdminOrderController extends Controller {

    protected $data = [];

    public function __construct() {
        $this->data['title'] = 'Orders';
    }

    public function index() {
        $this->data['orders'] = Order::with([
            'items' => function ($query) {
                $query->where('item_type', 'line_item');
            }
        ])->latest('id')->paginate(20);

        return view('admin.order.index', $this->data);
    }

    public function create(){
        $this->data['title'] = 'Add new Order';
        return view('admin.order.create', $this->data);
    }

    public function show(Request $request, $id) {
        $this->data['order'] = Order::findOrFail($id);
        $this->data['attachments'] = json_encode($this->data['order']->getMedia('attachments')->map(function ($media){
            return [
                'id' => $media->id,
                'image_url' => $media->getUrl('thumbnail'),
                'size' => $media->size
            ];
        }));
        return view('admin.order.show', $this->data);
    }

    public function update(Request $request, $order_id) {

        $order = Order::findOrFail($order_id);
        try {
            if ($request->input('post_message')) {
                return $this->post_message($request, $order);
            } elseif ($request->input('update_order')) {

                if ($order->PaymentMethod() == 'offline_payments') {
                    $order->order_details()->where('key', '_transaction_id')
                        ->update(['value' => $request->transaction_id]);
                }

                if ($order->status != $request->input('status')) {
                    $order->update(['status' => $request->input('status')]);

                    if ($order->user) {
                        $order->user->notify(new OrderUpdated($order));
                    } else {
                        $guestUser = User::make([
                            'first_name' => $order->getMeta('billing_first_name'),
                            'last_name' => $order->getMeta('billing_last_name'),
                            'email' => $order->getMeta('billing_email'),
                        ]);

                        Notification::send($guestUser, new OrderUpdated($order));
                    }
                }

                Flash::success('Order updated.');
            }
        } catch (Exception $ex) {
            flash('Error: ' . $ex->getMessage())->error();
        }

        return redirect()->route('ch-admin.order.show', [$order->id]);
    }

    private function post_message($request, $order) {

        $request->validate([
            'content' => 'string|required'
        ]);

        $message = $order->messages()->create([
            'content' => $request->input('content'),
            'type' => 'message',
            'user_id' => Auth::user()->id
        ]);

        if (\request()->has('attachments') && sizeof(\request()->input('attachments')) > 0) {
            $message->syncMedia(\request()->input('attachments'), 'attachments');
        }

        if ($order->user_id == 0) {
            $guestUser = User::make([
                'first_name' => $order->getMeta('billing_first_name'),
                'last_name' => $order->getMeta('billing_last_name'),
                'email' => $order->getMeta('billing_email'),
            ]);

            Notification::send($guestUser, new MessageAdded($order, $guestUser, route('ch_order_view', [$order->id, 'key' => $order->getMeta('order_key')])));
        } else {
            $order->user->notify(new MessageAdded($order, Auth::user(), route('ch_order_view', $order->id)));
        }

        Flash::success('Message added.');

        return redirect()->route('ch-admin.order.messages', [$order->id]);
    }

    public function destroy(Order $order)
    {
        $order->order_details()->delete();

        $order->items->each(function ($item) {

            $item->meta()->delete();

            $item->delete();
        });

        $order->customFields->each(function ($field) {
            if ($field->type == 'file' && !empty($field->file)) {

                $file = $field->file;

                $file->meta->each(function ($meta_file) {
                    if (file_exists(storage_path("app/" . $meta_file->path))) {
                        unlink(storage_path("app/" . $meta_file->path));
                    }
                    $meta_file->delete();
                });

                $file_path = "app/{$file->directory}/{$file->filename}.{$file->extension}";
                if (file_exists(storage_path($file_path))) {
                    unlink(storage_path($file_path));
                }
                $file->delete();
            }

            $field->delete();
        });

        $order->delete();

        Flash::success('Order deleted successfully.');

        return redirect()->back();
    }

    public function messages($id)
    {
        $this->data['order'] = Order::findOrFail($id);
        $this->data['attachments'] = json_encode($this->data['order']->getMedia('attachments')->map(function ($media){
            return [
                'id' => $media->id,
                'image_url' => $media->getUrl('thumbnail'),
                'size' => $media->size
            ];
        }));

        return view('admin.order.messages', $this->data);
    }
}
