<?php

use App\Mail\InstallationCompleteMail;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('clear', function () {
    Artisan::call('optimize:clear');
    dump(Artisan::output());
});

Route::get('locale/{locale}', function ($locale) {
    Session::put('locale', $locale);

    session()->forget('lang_dir');

    return redirect()->back();
})->name('switch_lang');

Route::group(['namespace' => 'Frontend', 'middleware' => ['web']], function () {
    Route::get('/installer', 'InstallerController@index');
    Route::post('/installer', 'InstallerController@updateEnv')->name('ch_update_env');
    Route::get('/installer/last-step', 'InstallerController@lastStep')->name('ch_install_last_step');
    Route::post('/installer/complete', 'InstallerController@completeInstallation')->name('ch_complete_installation');

    Route::get('/page/{slug}', 'PageController@show');

    Route::post('/ipn/{gateway}', 'OrderController@ipn')->name('ch_ipn');

    Route::post('/order/submit', 'OrderController@submit')->name('ch_order_save');
    Route::get('/account/orders/{order_id}', 'AccountController@view_order')->name('ch_order_view');
    Route::get('attachment/{id}', 'HomeController@attachment')->name('download_attachment');
    Route::post('cart/upload', 'CartController@upload')->name('upload_attachment');
});

Route::group(['namespace' => 'Frontend', 'middleware' => ['web', 'check_private_mode']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/category/{slug?}', 'HomeController@category')->name('category')->where('slug', '(.*)');
    Route::get('/product/{slug}', 'ProductController@show')->name('ch_product_single');
    Route::post('/product/pre_order_query', 'ProductController@pre_order_query')->name('ch_product_pre_order_query');
    Route::get('/product', 'HomeController@search')->name('search');

    Route::get('/order/failed/{order_id}', 'OrderController@failed')->name('ch_order_failed');
    Route::get('/order/cancel/{order_id}', 'OrderController@cancel')->name('ch_order_cancel');
    Route::get('/order/thank-you/{order_id}', 'OrderController@completed')->name('ch_order_submitted');
    Route::get('/order/format_price', 'OrderController@formatPrice')->name('ch_format_price');
    Route::any('/order/update_tax', 'OrderController@update_tax')->name('ch_update_tax');
    Route::post('/order/update_cart', 'OrderController@update_cart')->name('ch_update_cart');

    /**
     * Cart Routes
     */
    Route::get('/cart', 'CartController@show')->name('ch_cart');
    Route::post('/cart/store', 'CartController@store')->name('ch_cart_save');
    Route::post('/cart', 'CartController@update')->name('ch_cart_update');
});

Route::group(['namespace' => 'Frontend', 'middleware' => ['auth', 'verified']], function () {

    Route::get('/order/pay/{order_id}', 'OrderController@showPaymentForm')->name('ch_pay_form');
    Route::post('/order/pay/{order_id}', 'OrderController@verifyPayment')->name('ch_verify_payment');

    Route::get('/account/orders', 'AccountController@orders')->name('ch_user_orders');
    Route::get('/account/notifications', 'AccountController@notifications')->name('ch_user_notifications');

    Route::put('/account/orders/{order_id}', 'AccountController@post_message');
    Route::get('/account/edit-details', 'AccountController@edit')->name('ch_edit_details');
    Route::put('/account/edit-details', 'AccountController@update')->name('ch_update_details');

    Route::get('/chat/messages/{order_id}', 'ChatController@index')->name('ch_get_messages');
    Route::post('/chat/messages/{order_id}', 'ChatController@store')->name('ch_store_message');
    Route::post('/chat/upload', 'ChatController@uploadAttachment')->name('ch_chat_upload');
    Route::delete('/chat/deleteAttachment', 'ChatController@deleteAttachment')->name('ch_delete_attachment');
});


Route::post('ch-admin/ajax', 'Admin\AdminSettingController@ajax');


Auth::routes(['verify' => true]);
Route::get('auth/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->name('social_login_callback');


Route::group(['prefix' => 'ch-admin', 'as' => 'ch-admin.', 'namespace' => 'Admin', 'middleware' => ['auth', 'role:administrator']], function () {
    Route::get('/', 'AdminDashboardController@index')->name('ch_admin_dashboard');
    Route::get('/notifications', 'AdminDashboardController@notifications')->name('ch_admin_notifications');
    Route::get('/updates', 'AdminUpdateController@index')->name('ch_admin_update');
    Route::post('/updates', 'AdminUpdateController@update')->name('ch_update_post');
    Route::get('/update/check_status', 'AdminUpdateController@update_status')->name('ch_check_status');
    Route::resource('coupon', 'AdminCouponController');
    Route::resource('category', 'AdminCategoryController');
    Route::resource('form', 'AdminFormController');
    Route::resource('product', 'AdminProductController');
    Route::resource('user', 'AdminUserController')->except(['destroy']);
    Route::delete('user/{user}', 'AdminUserController@destroy')
        ->name('user.destroy')
        ->middleware('can:delete,user');

    Route::resource('settings', 'AdminSettingController');
    Route::resource('media', 'AdminMediaController');

    Route::resource('order', 'AdminOrderController');

    Route::get('language/{lang_id}/phrases/{group?}', 'AdminLanguageController@phrases')->name('phrases.edit');
    Route::put('language/phrases/{lang_id}/{group}', 'AdminLanguageController@phrasesUpdate')->name('phrases.update');
    Route::get('language/phrases/sync', 'AdminLanguageController@sync')->name('phrases.sync');
    Route::resource('language', 'AdminLanguageController');

    Route::get('messages', 'AdminMessagesController@index')->name('messages.index');
    Route::get('order/{order_id}/messages', 'AdminOrderController@messages')->name('order.messages');

});
