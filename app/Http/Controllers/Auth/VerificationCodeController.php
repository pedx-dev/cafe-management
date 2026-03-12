<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationCodeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class VerificationCodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('throttle:6,1')->only('resend');
    }

    /**
     * Show the verification code form.
     */
    public function show()
    {
        $user = auth()->user();

        // If already verified, redirect
        if ($user->email_verified_at) {
            return redirect()->route('menu')
                ->with('success', 'Your email is already verified!')
                ->with('account_success', 'Your email is already verified!');
        }

        return view('auth.verify-code');
    }

    /**
     * Verify the code entered by user.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = auth()->user();

        // Check if already verified
        if ($user->email_verified_at) {
            return redirect()->route('menu')
                ->with('success', 'Your email is already verified!')
                ->with('account_success', 'Your email is already verified!');
        }

        // Check if code matches
        if ($user->verification_code !== $request->code) {
            return back()->with('error', 'Invalid verification code. Please try again.');
        }

        // Check if code has expired
        if (Carbon::now()->greaterThan($user->verification_code_expires_at)) {
            return back()->with('error', 'Verification code has expired. Please request a new one.');
        }

        // Verify the user
        $user->email_verified_at = now();
        $user->verification_code = null;
        $user->verification_code_expires_at = null;
        $user->save();

        return redirect()->route('menu')
            ->with('success', 'Email verified successfully! Welcome to Café Delight!')
            ->with('account_success', 'Email verified successfully! Welcome to Cafe Delight!');
    }

    /**
     * Resend the verification code.
     */
    public function resend()
    {
        $user = auth()->user();

        // If already verified, redirect
        if ($user->email_verified_at) {
            return redirect()->route('menu')
                ->with('success', 'Your email is already verified!')
                ->with('account_success', 'Your email is already verified!');
        }

        // Generate new code
        $this->generateAndSendCode($user);

        return back()
            ->with('success', 'A new verification code has been sent to your email!')
            ->with('account_success', 'A new verification code has been sent to your email!');
    }

    /**
     * Generate a verification code and send it via email.
     */
    public static function generateAndSendCode($user)
    {
        // Generate 6-digit code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Save code and expiry (10 minutes)
        $user->verification_code = $code;
        $user->verification_code_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        // Send email
        Mail::to($user->email)->send(new VerificationCodeMail($code, $user->name));

        return $code;
    }
}
