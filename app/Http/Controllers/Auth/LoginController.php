<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
    protected $redirectTo = '/menu';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated($request, $user)
    {
        // If email is not verified, redirect to verification page
        if (! $user->email_verified_at) {
            // Resend code if none exists or it has expired
            if (
                ! $user->verification_code ||
                ! $user->verification_code_expires_at ||
                \Carbon\Carbon::now()->greaterThan($user->verification_code_expires_at)
            ) {
                \App\Http\Controllers\Auth\VerificationCodeController::generateAndSendCode($user);
            }

            return redirect()->route('verification.code.show')
                ->with('account_success', 'Please verify your email to continue.');
        }

        // Redirect admin to dashboard, regular user to menu
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('account_success', 'Welcome back, ' . $user->name . '!');
        }

        return redirect()->route('menu')
            ->with('account_success', 'Welcome back, ' . $user->name . '!');
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function loggedOut(Request $request)
    {
        return redirect()->route('login')
            ->with('account_success', 'You have been logged out successfully.');
    }
}
