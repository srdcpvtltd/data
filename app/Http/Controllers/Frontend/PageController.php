<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function show( $page_id ) {

        $pages = [
                    'tos' => 'terms-of-service',
                    'privacy_policy' => 'privacy-policy',
                    'refund_policy' => 'refund-policy',
                    'contact_details' => 'contact'
                ];
        $key = array_search($page_id, $pages);
        if ( $key == false ) {
            abort(404, trans('general.Page not found.'));
        }


        $heading = $pages[$key];
        $title = ucwords(str_replace('-', ' ', $pages[$key]));
        $content = setting($key);

        return view('themes.default.page', compact('content', 'title', 'heading'));
    }
}
