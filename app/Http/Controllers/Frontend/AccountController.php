<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Order;
use App\Models\Role;
use App\Notifications\Order\MessageAdded;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rules\ValidatePassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;
use App\Models\Country;
use App\Models\State;
use App\Services\UserService;
use Hash;
use Swift_TransportException;

class AccountController extends Controller
{

    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function edit()
    {
        $user = \Auth::user();
        $countries = Country::all()->pluck('name', 'id');
        $states = collect([]);

        if (isset($user->billing_country)) {
            $states = $user->billing_country->states->pluck('name', 'id') ?? collect([]);
        }

        if (old('usermeta.billing_country')) {
            $states = State::where('country_id', old('usermeta.billing_country'))->pluck('name', 'id');
        }

        return view('themes.default.account.edit', compact('user', 'countries', 'states'));
    }


    public function orders()
    {
        $orders = \Auth::user()->orders()->orderBy('id', 'DESC')->paginate(15);
        return view('themes.default.account.orders', compact('orders'));
    }


    public function view_order(Request $request, $order_id)
    {
        if (\auth()->check()) {
            $order = \Auth::user()->orders()->findOrFail($order_id);
        } else {
            $order = Order::findOrFail($order_id);
            $order_key = $order->getMeta('order_key');

            if (is_null($order_key) || $order_key != $request->input('key')) {
                abort(404);
            }
        }

        return \auth()->guest()
            ? view('themes.default.account.guest_order_view', compact('order'))
            : view('themes.default.account.order_view', compact('order'));
    }


    public function post_message(Request $request, $order_id)
    {
        $order = \Auth::user()->orders()->findOrFail($order_id);

        $order->messages()->create([
            'content' => $request->input('content'),
            'type' => $request->input('post_message') ? 'message' : 'feedback',
            'user_id' => \Auth::user()->id,
            'rating' => $request->input('rating') ? $request->input('rating') : 5
        ]);

        Flash::success(trans('account.Thank you for your rating and feedback.'));

        return redirect()->route('ch_order_view', [$order->id]);
    }


    public function update(Request $request)
    {

        $input = $request->all();

        $rules['first_name'] = 'string|max:255';
        $rules['last_name'] = 'string|max:255';
        $rules['email'] = 'required|string|email|max:255|unique:users,email,' . \Auth::user()->id;

        if (isset($input['current_password'])) {
            $rules['current_password'] = [new ValidatePassword(auth()->user())];
            $rules['new_password'] = 'required|string|min:6';
            $input['password'] = Hash::make($input['new_password']);
        }

        $request->validate($rules);

        try {
            $this->userService->update($input, auth()->user()->id);
        } catch (\Exception $ex) {
            dd($ex);
            return redirect()->back()
                ->withInput()
                ->withErrors(trans('account.An error occurred during your profile update. Please try again later.'));
        }

        Flash::success(trans('account.Profile updated successfully.'));

        return redirect()->route('ch_edit_details');
    }


    public function notifications(Request $request)
    {
        if ($request->ajax()) {

            if ($request->has('mark_all_as_read')) {
                auth()->user()->unreadNotifications->markAsRead();
            }

            if ($request->has('delete_notifications')) {
                auth()->user()->notifications()->delete();
            }

            $view = (string)View::make('themes.default.account.notifications_popup');
            return response()->json(['success' => true, 'html' => $view]);
        }
    }
}
