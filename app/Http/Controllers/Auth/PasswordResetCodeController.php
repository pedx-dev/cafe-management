<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationCodeMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PasswordResetCodeController extends Controller
{
    /**
     * Show the forgot password email form.
     */
    public function showRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Send a password reset code to the user's email.
     */
    public function sendCode(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'This email is not registered in the system.',
        ]);

        $user = User::where('email', $validated['email'])->firstOrFail();

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->password_reset_code = $code;
        $user->password_reset_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        Mail::to($user->email)->send(new VerificationCodeMail($code, $user->name, 'password_reset'));

        $request->session()->put('password_reset_email', $validated['email']);

        return redirect()
            ->route('password.code')
            ->with('status', 'We sent a verification code to your email if it exists in our system.')
            ->with('account_success', 'We sent a verification code to your email if it exists in our system.');
    }

    /**
     * Show the code verification form.
     */
    public function showCodeForm(Request $request)
    {
        $email = $request->session()->get('password_reset_email');

        return view('auth.passwords.code', compact('email'));
    }

    /**
     * Verify the reset code.
     */
    public function verifyCode(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !$user->password_reset_code || $user->password_reset_code !== $validated['code']) {
            return back()->with('error', 'Invalid reset code. Please try again.');
        }

        if (!$user->password_reset_expires_at || Carbon::now()->greaterThan($user->password_reset_expires_at)) {
            return back()->with('error', 'Reset code has expired. Please request a new one.');
        }

        $request->session()->put('password_reset_verified', true);
        $request->session()->put('password_reset_user_id', $user->id);
        $request->session()->put('password_reset_email', $user->email);

        return redirect()
            ->route('password.reset')
            ->with('status', 'Code verified. You can now set a new password.')
            ->with('account_success', 'Code verified. You can now set a new password.');
    }

    /**
     * Show the new password form.
     */
    public function showResetForm(Request $request)
    {
        $verified = $request->session()->get('password_reset_verified');
        $userId = $request->session()->get('password_reset_user_id');

        if (!$verified || !$userId) {
            return redirect()
                ->route('password.request')
                ->with('error', 'Please verify your reset code first.');
        }

        $email = $request->session()->get('password_reset_email');

        return view('auth.passwords.reset', compact('email'));
    }

    /**
     * Update the user's password.
     */
    public function resetPassword(Request $request)
    {
        $verified = $request->session()->get('password_reset_verified');
        $userId = $request->session()->get('password_reset_user_id');

        if (!$verified || !$userId) {
            return redirect()
                ->route('password.request')
                ->with('error', 'Please verify your reset code first.');
        }

        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::findOrFail($userId);
        if (!$user->password_reset_expires_at || Carbon::now()->greaterThan($user->password_reset_expires_at)) {
            return redirect()
                ->route('password.request')
                ->with('error', 'Reset code has expired. Please request a new one.');
        }
        $user->password = Hash::make($validated['password']);
        $user->password_reset_code = null;
        $user->password_reset_expires_at = null;
        $user->save();

        $request->session()->forget([
            'password_reset_verified',
            'password_reset_user_id',
            'password_reset_email',
        ]);

        return redirect()
            ->route('login')
            ->with('status', 'Your password has been reset. Please log in.')
            ->with('account_success', 'Your password has been reset. Please log in.');
    }
}
