<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Term;
use Illuminate\Http\Request;
use Setting;
use Storage;

class HomeController extends Controller
{

    public function index() {
        $products = Product::latest('id')->paginate(setting('products.per_page', 8));
        return view('themes.default.index', compact('products'));
    }

    public function category($slug)
    {
        if($slug)
        {
            $slug = explode('/', utf8_uri_encode($slug));
        }

        $terms = Term::whereIn('slug', [end($slug)])->get();

        if ($terms->count() < 1) {
            abort(404);
        }

        $term = $terms->pluck('id')->toArray();

        $query = Product::latest('id')->whereHas('terms', function ($q) use ($term) {
            $q->whereIn('term_id', $term);
        });

        $data['term'] = $terms->where('slug', collect($slug)->last())->first();
        $data['description'] = $data['term']->description;
        $data['products'] = $query->paginate(setting('services.per_page', 8));
        return view('themes.default.index', $data);
    }

    public function attachment(Request $request, $id)
    {
        // check if URL is still valid.
        if (! $request->hasValidSignature()) {
            abort(401);
        }

        // if guest, check for order key.
        if (auth()->guest()) {
            $order = Order::whereHas('order_details', function ($q) use ($request) {
                $q->where('key', 'order_key')->where('value', $request->input('key'));
            })->first();

            if (!$order) {
                abort(401);
            }
        }

        return Storage::disk('local')->download(decrypt($id));
    }

    public function search(Request $request)
    {
        $query = Product::where('name', 'LIKE', "%{$request->q}%")
            ->orWhere('description', "LIKE", "%{$request->q}%");

        $data['q'] = $request->q;
        $data['products'] = $query->paginate(setting('services.per_page', 8));
        return view('themes.default.index', $data);
    }
}
