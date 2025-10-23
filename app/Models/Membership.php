<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $fillable = [
        'user_id',
        'start_time',
        'end_time',
        'status',
        'total_amount',
        'payment_status',
       
    ];

    protected $casts = [ 'start_time' => 'datetime', 'end_time' => 'datetime', 'total_amount' => 'decimal:2', 'created_at' => 'datetime',
        'updated_at' => 'datetime', ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // ✅
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'membership_id'); // ✅
    }
}
