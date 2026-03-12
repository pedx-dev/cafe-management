<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Auth\VerificationCodeController;
use App\Rules\ValidEmail;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/verify-code';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                'unique:users',
                'regex:/@gmail\.com$/i',
                new ValidEmail(),
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'string', 'max:20'],
        ], [
            'email.regex' => 'Only Gmail addresses are allowed for registration.',
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'role' => 'user',
            // email_verified_at is null - user must verify with code
        ]);
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered($request, $user)
    {
        // Generate and send verification code
        VerificationCodeController::generateAndSendCode($user);

        return redirect()->route('verification.code.show')
            ->with('success', 'Registration successful! Please check your email for the verification code.')
            ->with('account_success', 'Registration successful! Please check your email for the verification code.');
    }
}