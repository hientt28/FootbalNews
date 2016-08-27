<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Mail;
use Auth;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';
    protected $redirectAfterLogout = '/login';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function redirectPath()
    {
        if (auth()->user()->isAdmin()) {
            $this->redirectTo = '/admin';
        } else {
            $this->redirectTo = '/users';
        }

        return $this->redirectTo;
    }

    //Register handle
    public function register(Request $request)
    {
        // Validate data
        //$validator = $this->validator($request->all());
        // Create user + create confirmation_code: str_random(10)
        $user = new User();
        $confirmationCode = str_random(100);
        $data['name'] = $user->name = $request->get('name', '');
        $data['email'] = $user->email = $request->get('email', '');
        $data['password'] = $user->password = bcrypt($request->get('password',''));
        $user->confirmed = 0;
        $user->confirmation_code = $confirmationCode;
        $user->save();
        $data['confirmation_code'] = $confirmationCode;

        //Send email : $data['confirmation_code'] =  $confirmation_code
        if($user->save()) {
            Mail::send('auth.emails.mail', $data , function ($message) use ($data) {
                $message->to($data['email'])->subject('Welcome to TMS!');
            });
        } else {
            return trans('message.send_error');
        }

        return back();

    }

    //Active for user
    public function confirm($confirmation_code)
    {
        //update = confirmed = 1 ; confirm_code = '
        $user = User::where('confirmation_code', $confirmation_code)->first();
        $user->confirmed = 1;
        $user->confirmation_code = '';
        $user->save();
        auth()->login($user);
        return redirect()->route('/');
    }


    //Login user handle
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $lockedOut = $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->getCredentials($request);
        $credentials['confirmed'] = 1;

        if (Auth::guard($this->getGuard())->attempt($credentials, $request->has('remember'))) {
            return $this->handleUserWasAuthenticated($request, $throttles);
        }else {
            return redirect()->back();
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles && ! $lockedOut) {
            $this->incrementLoginAttempts($request);
        }

        return $this->sendFailedLoginResponse($request);
    }
}
