<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'membership_id', 'amount', 'payment_method', 
        'payment_gateway_id', 'status', 'paid_at'
    ];
    
    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];
   public function membership() {
    return $this->belongsTo(Membership::class);
}
}