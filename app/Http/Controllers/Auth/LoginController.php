<?php

namespace App\Http\Controllers\Auth;

use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;
use Auth;
use ReCaptcha\ReCaptcha;
use Socialite;
use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect the user to the OAuth Provider.
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::with($provider)->redirect();
    }


    /**
     * Obtain the user information from provider.  Check if the user already exists in our
     * database by looking up their provider_id in the database.
     * If the user exists, log them in. Otherwise, create a new user then log them in. After that
     * redirect them to the authenticated users homepage.
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();

        $authUser = $this->findOrCreateUser($user, $provider);

        if ($authUser instanceof RedirectResponse) {
            return $authUser;
        }

        Auth::login($authUser, true);

        return redirect($this->redirectTo);
    }

    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    public function findOrCreateUser($user, $provider)
    {

        if ($provider == 'envato') {
            $userID = $user->nickname;
        } else {
            $userID = $user->id;
        }

        $authUser = User::where('provider_id', $userID)->first();

        if ($authUser) {
            return $authUser;
        }

        $parts = explode("@", $user->email);
        $username = $parts[0];
        $email = $user->email;

        $next = 1;

        while (User::where('username', '=', $username)->first()) {
            $username = "{$username}{$next}";
            $next++;
        }

        if (User::where('email', '=', $email)->first()) {
            return redirect()->route('login')->withErrors(['email' => 'The email address '. $email . ' is already registered.'])->withInput(['email' => $email]);
        }


        $user = User::create([
            'username' => $username,
            'name' => $user->name,
            'email' => $user->email,
            'provider' => $provider,
            'provider_id' => $userID,
            'verified_at' => Carbon::now(),
        ]);

        $cus_role = Role::where('name', 'customer')->first();

        $user->roles()->attach($cus_role);

        return $user;
    }

    /**
     * Validate the user login request.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        ch_verify_recaptcha();

        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.These credentials do not match our records.')],
        ]);
    }
}
