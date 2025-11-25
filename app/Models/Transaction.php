<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'membership_id', 'amount', 'payment_method', 
        'payment_gateway_id', 'status', 'paid_at', 'payment_data'
    ];
    
    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'payment_data' => 'array'
    ];
   public function membership() {
    return $this->belongsTo(Membership::class);
}

public function user() {
    return $this->hasOneThrough(User::class, Membership::class, 'id', 'id', 'membership_id', 'user_id');
}
}