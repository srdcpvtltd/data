<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Notifications\Order\PreOrderQueryNotification;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($id)
    {
        $product = Product::findBySlugOrFail($id);
        $title = ($product->getMeta('seo_title')) ? $product->getMeta('seo_title') : $product->name;
        $description = ($product->getMeta('seo_description')) ? $product->getMeta('seo_description') : null;
        return view('themes.default.single', compact('product', 'title', 'description'));
    }

    public function pre_order_query(Request $request)
    {
        $rules = [
            'item_id' => 'required|exists:products,id',
            'message' => 'required',
        ];

        if (!auth()->check()) {
            $rules['name'] = 'required';
            $rules['email'] = 'required|email';
        }

        $request->validate($rules);

        try {
            $service = Product::find($request->item_id);

            if (auth()->check()) {

                $user = auth()->user();

                $name = "{$user->first_name} {$user->last_name}";
                $email = $user->email;
            } else {
                $name = $request->name;
                $email = $request->email;
            }

            $data = [
                'service_name' => $service->name,
                'url' => route('ch_product_single', [$service->slug]),
                'name' => $name,
                'email' => $email,
                'content' => $request->message,
            ];

            $admins = User::whereHas('roles', function ($query) {
                $query->whereName('administrator');
            })->get();

            foreach ($admins as $admin) {
                $admin->notify(new PreOrderQueryNotification($data));
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => trans('product_detail.Message Sending Failed. Please try again.')
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => trans('product_detail.Your message has been sent successfully.')
        ]);
    }
}
