<?php

use App\Models\Media;
use App\Models\Setting;
use App\Models\Tax;
use App\Models\Term;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

define('UPDATE_URL', 'https://updates.chargepanda.com');
define('REMOTE_VERSION', UPDATE_URL . '/check.php');
define('NOTIFIER_CACHE_INTERVAL', 21600); // The time interval for the remote XML cache in the database (21600 seconds = 6 hours)
// this is the version of the deployed script
define('VERSION', '1.0');

function isUpToDate($force_check = false)
{
    $remoteVersion = ch_check_for_updates(NOTIFIER_CACHE_INTERVAL, $force_check);

    return version_compare(VERSION, $remoteVersion ?? VERSION, 'ge');
}


// Get the remote XML file contents and return its data (Version and Changelog)
// Uses the cached version if available and inside the time interval defined
function ch_check_for_updates($interval, $force_check = false)
{
    $notifier_file_url = REMOTE_VERSION;
    $last = setting('update_last_check');
    $now = time();

    // check the cache
    if (!$last || (($now - $last) > $interval) || $force_check == true) {
        // cache doesn't exist, or is old, so refresh it
        if (function_exists('curl_init')) { // if cURL is available, use it...
            $ch = curl_init($notifier_file_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $cache = curl_exec($ch);
            curl_close($ch);
        } else {
            $cache = @file_get_contents($notifier_file_url); // ...if not, use the common file_get_contents()
        }

        if ($cache) {
            // we got good results
            Setting::updateSettings(['remote_version' => $cache]);
            Setting::updateSettings(['update_last_check' => time()]);
        }
        // read from the cache file
        $notifier_data = setting('remote_version');
    } else {
        // cache file is fresh enough, so read from it
        $notifier_data = setting('remote_version');
    }
    return $notifier_data;
}


/**
 * Returns the size of a file without downloading it, or -1 if the file
 * size could not be determined.
 *
 * @param $url - The location of the remote file to download. Cannot
 * be null or empty.
 *
 * @return The size of the file referenced by $url, or -1 if the size
 * could not be determined.
 */
function curl_get_file_size($url)
{
    // Assume failure.
    $result = -1;

    $curl = curl_init($url);

    // Issue a HEAD request and follow any redirects.
    curl_setopt($curl, CURLOPT_NOBODY, true);
    curl_setopt($curl, CURLOPT_HEADER, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

    $data = curl_exec($curl);
    curl_close($curl);

    if ($data) {
        $content_length = "unknown";
        $status = "unknown";

        if (preg_match("/^HTTP\/1\.[01] (\d\d\d)/", $data, $matches)) {
            $status = (int)$matches[1];
        }

        if (preg_match("/Content-Length: (\d+)/", $data, $matches)) {
            $content_length = (int)$matches[1];
        }

        // http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
        if ($status == 200 || ($status > 300 && $status <= 308)) {
            $result = $content_length;
        }
    }

    return $result;
}


function get_base_url($path = '')
{
    return url($path);
}

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function CategoryList($s = NULL, $parent = 0, $spacing = '', $category_tree_array = '')
{

    if (!is_array($category_tree_array)) {
        $category_tree_array = array();
    }

    if (!is_null($s)) {
        $categories = Term::where(['parent' => $parent, 'taxonomy' => 'category'])->where('name', 'LIKE', '%' . $s . '%')->get();
    } else {
        $categories = Term::where(['parent' => $parent, 'taxonomy' => 'category'])->get();
    }

    foreach ($categories as $category) {
        $category_tree_array[$category['id']] = $spacing . $category['name'];
        $category_tree_array = CategoryList($s, $category['id'], '' . $spacing . '-&nbsp;', $category_tree_array);
    }

    return $category_tree_array;

}


function CategoryArray($parent = 0, $spacing = '', $category_tree_array = '')
{

    if (!is_array($category_tree_array)) {
        $category_tree_array = array();
    }
    $categories = Term::where(['parent' => $parent, 'taxonomy' => 'category'])->get();

    foreach ($categories as $category) {
        $category_tree_array[$category['id']] = $spacing . $category['name'];
        $category_tree_array = CategoryArray($category['id'], '' . $spacing . '-&nbsp;', $category_tree_array);
    }

    return $category_tree_array;

}

function generate_filename($filename, $path)
{

    $actual_name = pathinfo($filename, PATHINFO_FILENAME);
    $original_name = $actual_name;
    $extension = pathinfo($filename, PATHINFO_EXTENSION);

    $i = 1;
    while (file_exists($path . $actual_name . "." . $extension)) {
        $actual_name = (string)$original_name . $i;
        $filename = $actual_name . "." . $extension;
        $i++;
    }

}


/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param boole $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @source http://gravatar.com/site/implement/images/php/
 */
function get_gravatar($email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array())
{
    $url = '//www.gravatar.com/avatar/';
    $url .= md5(strtolower(trim($email)));
    $url .= "?s=$s&d=$d&r=$r";
    if ($img) {
        $url = '<img src="' . $url . '"';
        foreach ($atts as $key => $val) {
            $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
    }
    return $url;
}


function seoUrl($string)
{
    //Lower case everything
    $string = strtolower($string);
    //Make alphanumeric (removes all other characters)
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    //Clean up multiple dashes or whitespaces
    $string = preg_replace("/[\s-]+/", " ", $string);
    //Convert whitespaces and underscore to dash
    $string = preg_replace("/[\s_]/", "-", $string);
    return $string;
}


function is_image($mime)
{
    return (substr($mime, 0, 5) === 'image');
}


function is_serialized($data)
{
    // if it isn't a string, it isn't serialized
    if (!is_string($data))
        return false;
    $data = trim($data);
    if ('N;' == $data)
        return true;
    if (!preg_match('/^([adObis]):/', $data, $badions))
        return false;
    switch ($badions[1]) {
        case 'a' :
        case 'O' :
        case 's' :
            if (preg_match("/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data))
                return true;
            break;
        case 'b' :
        case 'i' :
        case 'd' :
            if (preg_match("/^{$badions[1]}:[0-9.E-]+;\$/", $data))
                return true;
            break;
    }
    return false;
}


function ch_active_item($route, $class)
{

    $current_route = Route::currentRouteName();

    if ($current_route == 'ch-admin.settings.show') {
        $current_route = url()->full();
    }

    if (strpos($current_route, $route) !== false) {
        return $class;
    }

}


function public_upload_path($filename = '')
{
    return ($filename == '') ? Storage::disk('uploads')->getDriver()->getAdapter()->getPathPrefix() :
        Storage::disk('uploads')->getDriver()->getAdapter()->getPathPrefix() . $filename;
}


function bwpc_get_client_ip()
{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}


function ch_format_price($price = 0, $currency = '')
{

    $currency_position = setting('currency_position', 'left');

    if ($currency == '') {
        $symbol = ch_currency_symbol(setting('currency', 'USD'));
    } else {
        $symbol = ch_currency_symbol($currency);
    }

    if (\setting('price_format', '1') == '2') {
        $symbol = setting('currency', 'USD');
    }

    if ($currency_position == 'left') {
        return $symbol . $price;
    } elseif ($currency_position == 'right') {
        return $price . $symbol;
    } elseif ($currency_position == 'left_space') {
        return trim($symbol . ' ' . $price);
    } elseif ($currency_position == 'right_space') {
        return trim($price . ' ' . $symbol);
    }

}


function ch_currency_symbol($key)
{
    $currency_symbols = array(
        'AED' => '&#1583;.&#1573;', // ?
        'AFN' => '&#65;&#102;',
        'ALL' => '&#76;&#101;&#107;',
        'AMD' => '',
        'ANG' => '&#402;',
        'AOA' => '&#75;&#122;', // ?
        'ARS' => '&#36;',
        'AUD' => '&#36;',
        'AWG' => '&#402;',
        'AZN' => '&#1084;&#1072;&#1085;',
        'BAM' => '&#75;&#77;',
        'BBD' => '&#36;',
        'BDT' => '&#2547;', // ?
        'BGN' => '&#1083;&#1074;',
        'BHD' => '.&#1583;.&#1576;', // ?
        'BIF' => '&#70;&#66;&#117;', // ?
        'BMD' => '&#36;',
        'BND' => '&#36;',
        'BOB' => '&#36;&#98;',
        'BRL' => '&#82;&#36;',
        'BSD' => '&#36;',
        'BTN' => '&#78;&#117;&#46;', // ?
        'BWP' => '&#80;',
        'BYR' => '&#112;&#46;',
        'BZD' => '&#66;&#90;&#36;',
        'CAD' => '&#36;',
        'CDF' => '&#70;&#67;',
        'CHF' => '&#67;&#72;&#70;',
        'CLF' => '', // ?
        'CLP' => '&#36;',
        'CNY' => '&#165;',
        'COP' => '&#36;',
        'CRC' => '&#8353;',
        'CUP' => '&#8396;',
        'CVE' => '&#36;', // ?
        'CZK' => '&#75;&#269;',
        'DJF' => '&#70;&#100;&#106;', // ?
        'DKK' => '&#107;&#114;',
        'DOP' => '&#82;&#68;&#36;',
        'DZD' => '&#1583;&#1580;', // ?
        'EGP' => '&#163;',
        'ETB' => '&#66;&#114;',
        'EUR' => '&#8364;',
        'FJD' => '&#36;',
        'FKP' => '&#163;',
        'GBP' => '&#163;',
        'GEL' => '&#4314;', // ?
        'GHS' => '&#162;',
        'GIP' => '&#163;',
        'GMD' => '&#68;', // ?
        'GNF' => '&#70;&#71;', // ?
        'GTQ' => '&#81;',
        'GYD' => '&#36;',
        'HKD' => '&#36;',
        'HNL' => '&#76;',
        'HRK' => '&#107;&#110;',
        'HTG' => '&#71;', // ?
        'HUF' => '&#70;&#116;',
        'IDR' => '&#82;&#112;',
        'ILS' => '&#8362;',
        'INR' => '&#8377;',
        'IQD' => '&#1593;.&#1583;', // ?
        'IRR' => '&#65020;',
        'ISK' => '&#107;&#114;',
        'JEP' => '&#163;',
        'JMD' => '&#74;&#36;',
        'JOD' => '&#74;&#68;', // ?
        'JPY' => '&#165;',
        'KES' => '&#75;&#83;&#104;', // ?
        'KGS' => '&#1083;&#1074;',
        'KHR' => '&#6107;',
        'KMF' => '&#67;&#70;', // ?
        'KPW' => '&#8361;',
        'KRW' => '&#8361;',
        'KWD' => '&#1583;.&#1603;', // ?
        'KYD' => '&#36;',
        'KZT' => '&#1083;&#1074;',
        'LAK' => '&#8365;',
        'LBP' => '&#163;',
        'LKR' => '&#8360;',
        'LRD' => '&#36;',
        'LSL' => '&#76;', // ?
        'LTL' => '&#76;&#116;',
        'LVL' => '&#76;&#115;',
        'LYD' => '&#1604;.&#1583;', // ?
        'MAD' => '&#1583;.&#1605;.', //?
        'MDL' => '&#76;',
        'MGA' => '&#65;&#114;', // ?
        'MKD' => '&#1076;&#1077;&#1085;',
        'MMK' => '&#75;',
        'MNT' => '&#8366;',
        'MOP' => '&#77;&#79;&#80;&#36;', // ?
        'MRO' => '&#85;&#77;', // ?
        'MUR' => '&#8360;', // ?
        'MVR' => '.&#1923;', // ?
        'MWK' => '&#77;&#75;',
        'MXN' => '&#36;',
        'MYR' => '&#82;&#77;',
        'MZN' => '&#77;&#84;',
        'NAD' => '&#36;',
        'NGN' => '&#8358;',
        'NIO' => '&#67;&#36;',
        'NOK' => '&#107;&#114;',
        'NPR' => '&#8360;',
        'NZD' => '&#36;',
        'OMR' => '&#65020;',
        'PAB' => '&#66;&#47;&#46;',
        'PEN' => '&#83;&#47;&#46;',
        'PGK' => '&#75;', // ?
        'PHP' => '&#8369;',
        'PKR' => '&#8360;',
        'PLN' => '&#122;&#322;',
        'PYG' => '&#71;&#115;',
        'QAR' => '&#65020;',
        'RON' => '&#108;&#101;&#105;',
        'RSD' => '&#1044;&#1080;&#1085;&#46;',
        'RUB' => '&#1088;&#1091;&#1073;',
        'RWF' => '&#1585;.&#1587;',
        'SAR' => '&#65020;',
        'SBD' => '&#36;',
        'SCR' => '&#8360;',
        'SDG' => '&#163;', // ?
        'SEK' => '&#107;&#114;',
        'SGD' => '&#36;',
        'SHP' => '&#163;',
        'SLL' => '&#76;&#101;', // ?
        'SOS' => '&#83;',
        'SRD' => '&#36;',
        'STD' => '&#68;&#98;', // ?
        'SVC' => '&#36;',
        'SYP' => '&#163;',
        'SZL' => '&#76;', // ?
        'THB' => '&#3647;',
        'TJS' => '&#84;&#74;&#83;', // ? TJS (guess)
        'TMT' => '&#109;',
        'TND' => '&#1583;.&#1578;',
        'TOP' => '&#84;&#36;',
        'TRY' => '&#8356;', // New Turkey Lira (old symbol used)
        'TTD' => '&#36;',
        'TWD' => '&#78;&#84;&#36;',
        'TZS' => '',
        'UAH' => '&#8372;',
        'UGX' => '&#85;&#83;&#104;',
        'USD' => '&#36;',
        'UYU' => '&#36;&#85;',
        'UZS' => '&#1083;&#1074;',
        'VEF' => '&#66;&#115;',
        'VND' => '&#8363;',
        'VUV' => '&#86;&#84;',
        'WST' => '&#87;&#83;&#36;',
        'XAF' => '&#70;&#67;&#70;&#65;',
        'XCD' => '&#36;',
        'XDR' => '',
        'XOF' => '',
        'XPF' => '&#70;',
        'YER' => '&#65020;',
        'ZAR' => '&#82;',
        'ZMK' => '&#90;&#75;', // ?
        'ZWL' => '&#90;&#36;',
    );

    return isset($currency_symbols[$key]) ? $currency_symbols[$key] : $key;

}


function is_gateway_configured($gateway = '')
{
    if ($gateway == 'stripe') {
        return setting('stripe.enabled', 'yes') == 'yes' && (setting('stripe.pk') != '' && setting('stripe.sk') != '');
    }

    if ($gateway == 'paypal') {
        return setting('paypal.enabled', 'yes') == 'yes' &&
            (
                setting('paypal.username') != '' &&
                setting('paypal.password') != '' &&
                setting('paypal.signature') != ''
            );
    }

    if ($gateway == 'offline_payments') {
        return setting('offline_payments.enabled') == 'yes' &&
            !empty(\setting('offline_payments.title')) &&
            !empty(\setting('offline_payments.description'));
    }

    return false;
}


function coinpayments_api_call($cmd, $req = array())
{
    // Fill these in from your API Keys page
    $public_key = setting('coinpayments.public_key');
    $private_key = setting('coinpayments.private_key');

    // Set the API command and required fields
    $req['version'] = 1;
    $req['cmd'] = $cmd;
    $req['key'] = $public_key;
    $req['format'] = 'json'; //supported values are json and xml

    // Generate the query string
    $post_data = http_build_query($req, '', '&');

    // Calculate the HMAC signature on the POST data
    $hmac = hash_hmac('sha512', $post_data, $private_key);

    // Create cURL handle and initialize (if needed)
    static $ch = NULL;
    if ($ch === NULL) {
        $ch = curl_init('https://www.coinpayments.net/api.php');
        curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('HMAC: ' . $hmac));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

    // Execute the call and close cURL handle
    $data = curl_exec($ch);
    // Parse and return data if successful.
    if ($data !== FALSE) {
        if (PHP_INT_SIZE < 8 && version_compare(PHP_VERSION, '5.4.0') >= 0) {
            // We are on 32-bit PHP, so use the bigint as string option. If you are using any API calls with Satoshis it is highly NOT recommended to use 32-bit PHP
            $dec = json_decode($data, TRUE, 512, JSON_BIGINT_AS_STRING);
        } else {
            $dec = json_decode($data, TRUE);
        }
        if ($dec !== NULL && count($dec)) {
            return $dec;
        } else {
            // If you are using PHP 5.5.0 or higher you can use json_last_error_msg() for a better error message
            return array('error' => 'Unable to parse JSON result (' . json_last_error() . ')');
        }
    } else {
        return array('error' => 'cURL error: ' . curl_error($ch));
    }
}


function get_tax_rate($country_id = 0, $state_id = 0)
{

    if (Auth::check()) {

        $user = Auth::user();
        $tax_row = null;

        if ($user->billing_state && $state_id == 0) {
            $tax_row = Tax::where('state_id', $user->billing_state)->first();
        } else {
            $tax_row = Tax::where('state_id', $state_id)->first();
        }

        if (is_null($tax_row) && $user->billing_country && $country_id == 0) {
            $tax_row = Tax::where('country_id', $user->billing_country->id)->first();
        } elseif (is_null($tax_row) && $country_id != 0) {
            $tax_row = Tax::where('country_id', $country_id)->first();
        }

        if (!is_null($tax_row)) {
            config()->set('cart.tax', $tax_row->rate);
        }
    }

    return config()->get('cart.tax');
}

function setting($key = null, $default = null)
{
    static $settings;

    if (is_null($settings)) {
        $settings = Cache::remember('settings', 24 * 60, function () {
            return Setting::pluck('value', 'key')->all();
        });
    }

    if (!empty($key)) {
        return isset($settings[$key]) ? $settings[$key] : $default;
    }
    return $settings;
}


function get_placeholder_img()
{
    return url('assets/img/thumb.png');
}

function get_menu($categories, $childm = false)
{
    if ($categories->count() > 0) {

        foreach ($categories as $category) {
            if ($category->product_count > 0) {
                ?>
                <?php if ($category->childs->count() > 0) { ?>
                    <li>
                        <a href="<?php echo $category->route; ?>"><?php echo $category->name; ?></a>
                        <ul>
                            <?php foreach ($category->childs as $child) { ?>
                                <?php if ($child->childs->count() > 0) { ?>
                                    <li><a href="<?php echo $child->route; ?>"><?php echo $child->name; ?></a>
                                        <?php
                                        if ($child->childs->count() > 0 && $child->product_count > 0) {
                                            get_menu($child->childs, true);
                                        }
                                        ?>
                                    </li>
                                    <?php
                                } else {
                                    ?>
                                    <li><a href="<?php echo $child->route; ?>"><?php echo $child->name; ?></a></li>
                                    <?php
                                }
                                ?>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } else { ?>
                    <li><a href="<?php echo $category->route; ?>"><?php echo $category->name; ?></a></li>
                <?php }
            }
        }
    }

}

function get_child_menus($category)
{
    if ($category->childs->count()) {
        $menu = '<li class="dropdown dropdown-submenu">
                    <a href="' . route("category", $category->slug) . '" class="dropdown-toggle"
                    data-toggle="dropdown">' . $category->name . '</a>
                    <ul class="dropdown-menu">';
        foreach ($category->childs as $sub_cat) {
            $menu .= get_child_menus($sub_cat);
        }
        return $menu . '</ul></li>';
    } else {
        return '<li><a href="' . route("category", $category->slug) . '">' . $category->name . '</a></li>';
    }
}

function get_term_parent_ids($id)
{
    $parents = DB::select('SELECT T2.id
                        FROM (
                            SELECT
                                @r AS _id,
                                (SELECT @r := parent FROM ch_terms WHERE id = _id) AS parent,
                                @l := @l + 1 AS lvl
                            FROM
                                (SELECT @r := ?, @l := 0) vars,
                                ch_terms h
                            WHERE @r <> 0) T1
                        JOIN ch_terms T2
                        ON T1._id = T2.id
                        ORDER BY T1.lvl DESC', [$id]);

    return collect($parents)->pluck('id')->toArray();
}

function update_product_count()
{
    try {
        $terms = Term::where('product_count', null)->where('taxonomy', 'category')->get();

        if ($terms->count() > 0) {
            foreach ($terms as $term) {

                if ($term->product_count === null) {
                    $term->product_count = 0;
                    $term->save();
                }

                $count = $term->services()->count();

                if (Schema::hasColumn('terms', 'product_count')) {
                    $parents = get_term_parent_ids($term->id);
                    if (sizeof($parents) > 0) {
                        $term_ids = $parents;
                    } else {
                        $term_ids = $term->id;
                    }

                    DB::table('terms')->whereId($term_ids)->increment('product_count');
                }
            }
        }
    } catch (\Exception $exception) {
        $exception->getMessage();
    }
}

function get_logo()
{
    try {
        $logo = Media::where('id', \setting('app.logo'))->first();

        if (isset($logo->id)) {
            return '<img src="' . $logo->getUrl() . '" alt="' . setting('app.name', 'ChargePanda') . '">';
        } else {
            return setting('app.name', 'ChargePanda');
        }

    } catch (\Exception $exception) {
    }
}

function get_logo_url()
{
    try {
        $logo = Media::where('id', \setting('app.logo'))->first();

        if (isset($logo->id)) {
            return $logo->getUrl();
        } else {
            return null;
        }

    } catch (\Exception $exception) {
    }
}

function site_url($path = null)
{
    $path = substr($path, 0, 1) == '/' ? $path : '/' . $path;

    $url = \setting('app.url') == null ? get_base_url() : \setting('app.url');

    return $path == null ? $url : rtrim($url, '/') . $path;
}


function sanitize_title($title)
{
    $title = remove_accents($title);

    return sanitize_title_with_dashes($title);
}

function remove_accents($string)
{
    if (!preg_match('/[\x80-\xff]/', $string)) {
        return $string;
    }

    if (seems_utf8($string)) {
        $chars = array(
            // Decompositions for Latin-1 Supplement
            'ª' => 'a',
            'º' => 'o',
            'À' => 'A',
            'Á' => 'A',
            'Â' => 'A',
            'Ã' => 'A',
            'Ä' => 'A',
            'Å' => 'A',
            'Æ' => 'AE',
            'Ç' => 'C',
            'È' => 'E',
            'É' => 'E',
            'Ê' => 'E',
            'Ë' => 'E',
            'Ì' => 'I',
            'Í' => 'I',
            'Î' => 'I',
            'Ï' => 'I',
            'Ð' => 'D',
            'Ñ' => 'N',
            'Ò' => 'O',
            'Ó' => 'O',
            'Ô' => 'O',
            'Õ' => 'O',
            'Ö' => 'O',
            'Ù' => 'U',
            'Ú' => 'U',
            'Û' => 'U',
            'Ü' => 'U',
            'Ý' => 'Y',
            'Þ' => 'TH',
            'ß' => 's',
            'à' => 'a',
            'á' => 'a',
            'â' => 'a',
            'ã' => 'a',
            'ä' => 'a',
            'å' => 'a',
            'æ' => 'ae',
            'ç' => 'c',
            'è' => 'e',
            'é' => 'e',
            'ê' => 'e',
            'ë' => 'e',
            'ì' => 'i',
            'í' => 'i',
            'î' => 'i',
            'ï' => 'i',
            'ð' => 'd',
            'ñ' => 'n',
            'ò' => 'o',
            'ó' => 'o',
            'ô' => 'o',
            'õ' => 'o',
            'ö' => 'o',
            'ø' => 'o',
            'ù' => 'u',
            'ú' => 'u',
            'û' => 'u',
            'ü' => 'u',
            'ý' => 'y',
            'þ' => 'th',
            'ÿ' => 'y',
            'Ø' => 'O',
            // Decompositions for Latin Extended-A
            'Ā' => 'A',
            'ā' => 'a',
            'Ă' => 'A',
            'ă' => 'a',
            'Ą' => 'A',
            'ą' => 'a',
            'Ć' => 'C',
            'ć' => 'c',
            'Ĉ' => 'C',
            'ĉ' => 'c',
            'Ċ' => 'C',
            'ċ' => 'c',
            'Č' => 'C',
            'č' => 'c',
            'Ď' => 'D',
            'ď' => 'd',
            'Đ' => 'D',
            'đ' => 'd',
            'Ē' => 'E',
            'ē' => 'e',
            'Ĕ' => 'E',
            'ĕ' => 'e',
            'Ė' => 'E',
            'ė' => 'e',
            'Ę' => 'E',
            'ę' => 'e',
            'Ě' => 'E',
            'ě' => 'e',
            'Ĝ' => 'G',
            'ĝ' => 'g',
            'Ğ' => 'G',
            'ğ' => 'g',
            'Ġ' => 'G',
            'ġ' => 'g',
            'Ģ' => 'G',
            'ģ' => 'g',
            'Ĥ' => 'H',
            'ĥ' => 'h',
            'Ħ' => 'H',
            'ħ' => 'h',
            'Ĩ' => 'I',
            'ĩ' => 'i',
            'Ī' => 'I',
            'ī' => 'i',
            'Ĭ' => 'I',
            'ĭ' => 'i',
            'Į' => 'I',
            'į' => 'i',
            'İ' => 'I',
            'ı' => 'i',
            'Ĳ' => 'IJ',
            'ĳ' => 'ij',
            'Ĵ' => 'J',
            'ĵ' => 'j',
            'Ķ' => 'K',
            'ķ' => 'k',
            'ĸ' => 'k',
            'Ĺ' => 'L',
            'ĺ' => 'l',
            'Ļ' => 'L',
            'ļ' => 'l',
            'Ľ' => 'L',
            'ľ' => 'l',
            'Ŀ' => 'L',
            'ŀ' => 'l',
            'Ł' => 'L',
            'ł' => 'l',
            'Ń' => 'N',
            'ń' => 'n',
            'Ņ' => 'N',
            'ņ' => 'n',
            'Ň' => 'N',
            'ň' => 'n',
            'ŉ' => 'n',
            'Ŋ' => 'N',
            'ŋ' => 'n',
            'Ō' => 'O',
            'ō' => 'o',
            'Ŏ' => 'O',
            'ŏ' => 'o',
            'Ő' => 'O',
            'ő' => 'o',
            'Œ' => 'OE',
            'œ' => 'oe',
            'Ŕ' => 'R',
            'ŕ' => 'r',
            'Ŗ' => 'R',
            'ŗ' => 'r',
            'Ř' => 'R',
            'ř' => 'r',
            'Ś' => 'S',
            'ś' => 's',
            'Ŝ' => 'S',
            'ŝ' => 's',
            'Ş' => 'S',
            'ş' => 's',
            'Š' => 'S',
            'š' => 's',
            'Ţ' => 'T',
            'ţ' => 't',
            'Ť' => 'T',
            'ť' => 't',
            'Ŧ' => 'T',
            'ŧ' => 't',
            'Ũ' => 'U',
            'ũ' => 'u',
            'Ū' => 'U',
            'ū' => 'u',
            'Ŭ' => 'U',
            'ŭ' => 'u',
            'Ů' => 'U',
            'ů' => 'u',
            'Ű' => 'U',
            'ű' => 'u',
            'Ų' => 'U',
            'ų' => 'u',
            'Ŵ' => 'W',
            'ŵ' => 'w',
            'Ŷ' => 'Y',
            'ŷ' => 'y',
            'Ÿ' => 'Y',
            'Ź' => 'Z',
            'ź' => 'z',
            'Ż' => 'Z',
            'ż' => 'z',
            'Ž' => 'Z',
            'ž' => 'z',
            'ſ' => 's',
            // Decompositions for Latin Extended-B
            'Ș' => 'S',
            'ș' => 's',
            'Ț' => 'T',
            'ț' => 't',
            // Euro Sign
            '€' => 'E',
            // GBP (Pound) Sign
            '£' => '',
            // Vowels with diacritic (Vietnamese)
            // unmarked
            'Ơ' => 'O',
            'ơ' => 'o',
            'Ư' => 'U',
            'ư' => 'u',
            // grave accent
            'Ầ' => 'A',
            'ầ' => 'a',
            'Ằ' => 'A',
            'ằ' => 'a',
            'Ề' => 'E',
            'ề' => 'e',
            'Ồ' => 'O',
            'ồ' => 'o',
            'Ờ' => 'O',
            'ờ' => 'o',
            'Ừ' => 'U',
            'ừ' => 'u',
            'Ỳ' => 'Y',
            'ỳ' => 'y',
            // hook
            'Ả' => 'A',
            'ả' => 'a',
            'Ẩ' => 'A',
            'ẩ' => 'a',
            'Ẳ' => 'A',
            'ẳ' => 'a',
            'Ẻ' => 'E',
            'ẻ' => 'e',
            'Ể' => 'E',
            'ể' => 'e',
            'Ỉ' => 'I',
            'ỉ' => 'i',
            'Ỏ' => 'O',
            'ỏ' => 'o',
            'Ổ' => 'O',
            'ổ' => 'o',
            'Ở' => 'O',
            'ở' => 'o',
            'Ủ' => 'U',
            'ủ' => 'u',
            'Ử' => 'U',
            'ử' => 'u',
            'Ỷ' => 'Y',
            'ỷ' => 'y',
            // tilde
            'Ẫ' => 'A',
            'ẫ' => 'a',
            'Ẵ' => 'A',
            'ẵ' => 'a',
            'Ẽ' => 'E',
            'ẽ' => 'e',
            'Ễ' => 'E',
            'ễ' => 'e',
            'Ỗ' => 'O',
            'ỗ' => 'o',
            'Ỡ' => 'O',
            'ỡ' => 'o',
            'Ữ' => 'U',
            'ữ' => 'u',
            'Ỹ' => 'Y',
            'ỹ' => 'y',
            // acute accent
            'Ấ' => 'A',
            'ấ' => 'a',
            'Ắ' => 'A',
            'ắ' => 'a',
            'Ế' => 'E',
            'ế' => 'e',
            'Ố' => 'O',
            'ố' => 'o',
            'Ớ' => 'O',
            'ớ' => 'o',
            'Ứ' => 'U',
            'ứ' => 'u',
            // dot below
            'Ạ' => 'A',
            'ạ' => 'a',
            'Ậ' => 'A',
            'ậ' => 'a',
            'Ặ' => 'A',
            'ặ' => 'a',
            'Ẹ' => 'E',
            'ẹ' => 'e',
            'Ệ' => 'E',
            'ệ' => 'e',
            'Ị' => 'I',
            'ị' => 'i',
            'Ọ' => 'O',
            'ọ' => 'o',
            'Ộ' => 'O',
            'ộ' => 'o',
            'Ợ' => 'O',
            'ợ' => 'o',
            'Ụ' => 'U',
            'ụ' => 'u',
            'Ự' => 'U',
            'ự' => 'u',
            'Ỵ' => 'Y',
            'ỵ' => 'y',
            // Vowels with diacritic (Chinese, Hanyu Pinyin)
            'ɑ' => 'a',
            // macron
            'Ǖ' => 'U',
            'ǖ' => 'u',
            // acute accent
            'Ǘ' => 'U',
            'ǘ' => 'u',
            // caron
            'Ǎ' => 'A',
            'ǎ' => 'a',
            'Ǐ' => 'I',
            'ǐ' => 'i',
            'Ǒ' => 'O',
            'ǒ' => 'o',
            'Ǔ' => 'U',
            'ǔ' => 'u',
            'Ǚ' => 'U',
            'ǚ' => 'u',
            // grave accent
            'Ǜ' => 'U',
            'ǜ' => 'u',
        );

        $string = strtr($string, $chars);
    } else {
        $chars = array();
        // Assume ISO-8859-1 if not UTF-8
        $chars['in'] = "\x80\x83\x8a\x8e\x9a\x9e"
            . "\x9f\xa2\xa5\xb5\xc0\xc1\xc2"
            . "\xc3\xc4\xc5\xc7\xc8\xc9\xca"
            . "\xcb\xcc\xcd\xce\xcf\xd1\xd2"
            . "\xd3\xd4\xd5\xd6\xd8\xd9\xda"
            . "\xdb\xdc\xdd\xe0\xe1\xe2\xe3"
            . "\xe4\xe5\xe7\xe8\xe9\xea\xeb"
            . "\xec\xed\xee\xef\xf1\xf2\xf3"
            . "\xf4\xf5\xf6\xf8\xf9\xfa\xfb"
            . "\xfc\xfd\xff";

        $chars['out'] = 'EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy';

        $string = strtr($string, $chars['in'], $chars['out']);
        $double_chars = array();
        $double_chars['in'] = array("\x8c", "\x9c", "\xc6", "\xd0", "\xde", "\xdf", "\xe6", "\xf0", "\xfe");
        $double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
        $string = str_replace($double_chars['in'], $double_chars['out'], $string);
    }

    return $string;
}

/**
 * Checks to see if a string is utf8 encoded.
 *
 * NOTE: This function checks for 5-Byte sequences, UTF8
 *       has Bytes Sequences with a maximum length of 4.
 *
 * @param string $str The string to be checked
 * @return bool True if $str fits a UTF-8 model, false otherwise.
 * @author bmorel at ssi dot fr (modified)
 * @since 1.2.1
 *
 */
function seems_utf8($str)
{
    mbstring_binary_safe_encoding();
    $length = strlen($str);
    reset_mbstring_encoding();
    for ($i = 0; $i < $length; $i++) {
        $c = ord($str[$i]);
        if ($c < 0x80) {
            $n = 0; // 0bbbbbbb
        } elseif (($c & 0xE0) == 0xC0) {
            $n = 1; // 110bbbbb
        } elseif (($c & 0xF0) == 0xE0) {
            $n = 2; // 1110bbbb
        } elseif (($c & 0xF8) == 0xF0) {
            $n = 3; // 11110bbb
        } elseif (($c & 0xFC) == 0xF8) {
            $n = 4; // 111110bb
        } elseif (($c & 0xFE) == 0xFC) {
            $n = 5; // 1111110b
        } else {
            return false; // Does not match any model
        }
        for ($j = 0; $j < $n; $j++) { // n bytes matching 10bbbbbb follow ?
            if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80)) {
                return false;
            }
        }
    }
    return true;
}

function mbstring_binary_safe_encoding($reset = false)
{
    static $encodings = array();
    static $overloaded = null;

    if (is_null($overloaded)) {
        $overloaded = function_exists('mb_internal_encoding') && (ini_get('mbstring.func_overload') & 2);
    }

    if (false === $overloaded) {
        return;
    }

    if (!$reset) {
        $encoding = mb_internal_encoding();
        array_push($encodings, $encoding);
        mb_internal_encoding('ISO-8859-1');
    }

    if ($reset && $encodings) {
        $encoding = array_pop($encodings);
        mb_internal_encoding($encoding);
    }
}

function reset_mbstring_encoding()
{
    mbstring_binary_safe_encoding(true);
}

function sanitize_title_with_dashes($title, $raw_title = '', $context = 'display')
{
    $title = strip_tags($title);
    // Preserve escaped octets.
    $title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
    // Remove percent signs that are not part of an octet.
    $title = str_replace('%', '', $title);
    // Restore octets.
    $title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);

    if (seems_utf8($title)) {
        if (function_exists('mb_strtolower')) {
            $title = mb_strtolower($title, 'UTF-8');
        }
        $title = utf8_uri_encode($title, 200);
    }

    $title = strtolower($title);

    if ('display' == $context) {
        // Convert nbsp, ndash and mdash to hyphens
        $title = str_replace(array('%c2%a0', '%e2%80%93', '%e2%80%94'), '-', $title);
        // Convert nbsp, ndash and mdash HTML entities to hyphens
        $title = str_replace(array('&nbsp;', '&#160;', '&ndash;', '&#8211;', '&mdash;', '&#8212;'), '-', $title);
        // Convert forward slash to hyphen
        $title = str_replace('/', '-', $title);

        // Strip these characters entirely
        $title = str_replace(
            array(
                // soft hyphens
                '%c2%ad',
                // iexcl and iquest
                '%c2%a1',
                '%c2%bf',
                // angle quotes
                '%c2%ab',
                '%c2%bb',
                '%e2%80%b9',
                '%e2%80%ba',
                // curly quotes
                '%e2%80%98',
                '%e2%80%99',
                '%e2%80%9c',
                '%e2%80%9d',
                '%e2%80%9a',
                '%e2%80%9b',
                '%e2%80%9e',
                '%e2%80%9f',
                // copy, reg, deg, hellip and trade
                '%c2%a9',
                '%c2%ae',
                '%c2%b0',
                '%e2%80%a6',
                '%e2%84%a2',
                // acute accents
                '%c2%b4',
                '%cb%8a',
                '%cc%81',
                '%cd%81',
                // grave accent, macron, caron
                '%cc%80',
                '%cc%84',
                '%cc%8c',
            ),
            '',
            $title
        );

        // Convert times to x
        $title = str_replace('%c3%97', 'x', $title);
    }

    $title = preg_replace('/&.+?;/', '', $title); // kill entities
    $title = str_replace('.', '-', $title);

    $title = preg_replace('/[^%a-z0-9 _-]/', '', $title);
    $title = preg_replace('/\s+/', '-', $title);
    $title = preg_replace('|-+|', '-', $title);
    $title = trim($title, '-');

    return $title;
}

function utf8_uri_encode($utf8_string, $length = 0)
{
    $unicode = '';
    $values = array();
    $num_octets = 1;
    $unicode_length = 0;

    mbstring_binary_safe_encoding();
    $string_length = strlen($utf8_string);
    reset_mbstring_encoding();

    for ($i = 0; $i < $string_length; $i++) {

        $value = ord($utf8_string[$i]);

        if ($value < 128) {
            if ($length && ($unicode_length >= $length)) {
                break;
            }
            $unicode .= chr($value);
            $unicode_length++;
        } else {
            if (count($values) == 0) {
                if ($value < 224) {
                    $num_octets = 2;
                } elseif ($value < 240) {
                    $num_octets = 3;
                } else {
                    $num_octets = 4;
                }
            }

            $values[] = $value;

            if ($length && ($unicode_length + ($num_octets * 3)) > $length) {
                break;
            }
            if (count($values) == $num_octets) {
                for ($j = 0; $j < $num_octets; $j++) {
                    $unicode .= '%' . dechex($values[$j]);
                }

                $unicode_length += $num_octets * 3;

                $values = array();
                $num_octets = 1;
            }
        }
    }

    return $unicode;
}

function ch_get_title($title = "")
{
    // For home page
    if (Route::is('home')) {
        return \setting('seo.home_title', \setting('app.name'));
    }

    // Single product
    if (Route::is('ch_product_single')) {
        $title_format = \setting('seo.service_format', '%SERVICE_TITLE% - %SITE_NAME%');

        return str_replace(
            array('%SERVICE_TITLE%', '%SITE_NAME%'),
            array($title, \setting('app.name')),
            $title_format);
    }

    // Category Page
    if (Route::is('category')) {
        $title_format = \setting('seo.category_format', '%CATEGORY_TITLE% - %SITE_NAME%');

        return str_replace(
            array('%CATEGORY_TITLE%', '%SITE_NAME%'),
            array($title, \setting('app.name')),
            $title_format);
    }

    if ($title) {
        $title_format = \setting('seo.general_format', '%TITLE% - %SITE_NAME%');

        return str_replace(
            array('%TITLE%', '%SITE_NAME%'),
            array($title, \setting('app.name')),
            $title_format);
    } else {
        return \setting('app.name');
    }
}

function ch_get_favicon()
{
    try {
        $favicon = Media::where('id', \setting('app.favicon'))->first();

        if (isset($favicon->id)) {
            return '<link rel="icon" href="' . $favicon->getUrl() . '">';
        }

    } catch (\Exception $exception) {
    }
}

function ch_verify_recaptcha()
{
    if (setting('recaptcha.enabled') == 'on' && setting('recaptcha.api_secret_key') != null) {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'secret' => setting('recaptcha.api_secret_key'),
            'response' => \request('g-recaptcha-response'),
        ]));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $data = curl_exec($ch);

        curl_close($ch);

        $response = @json_decode($data);

        if (!isset($response->success) || $response->success != true) {
            throw ValidationException::withMessages([
                'recaptcha' => [trans('general.Human verification failed.')],
            ]);
        }
    }
}
