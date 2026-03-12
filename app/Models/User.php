<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'profile_image',
        'role',
        'loyalty_points',
        'verification_code',
        'verification_code_expires_at',
        'password_reset_code',
        'password_reset_expires_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'verification_code',
        'password_reset_code',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'verification_code_expires_at' => 'datetime',
        'password_reset_expires_at' => 'datetime',
    ];

    /**
     * Override default email verification notification.
     * We use custom code-based verification instead.
     */
    public function sendEmailVerificationNotification()
    {
        // Do nothing - we handle verification via VerificationCodeController
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}