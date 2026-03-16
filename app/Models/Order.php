<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'order_code',
        'user_id',
        'total_amount',
        'status',
        'delivery_type',
        'courier_provider',
        'courier_reference',
        'courier_status',
        'delivery_address',
        'payment_method',
        'payment_status',
        'stripe_session_id',
        'stripe_payment_intent_id',
        'notes'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            $order->order_number = 'ORD-' . strtoupper(uniqid());
            $order->order_code = 'CAFEDL' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderTracking()
    {
        return $this->hasOne(OrderTracking::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}