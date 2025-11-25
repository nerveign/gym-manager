<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'trainer_id',
        'membership_id',
        'duration',
        'date',
        'time',
    ];

    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class, 'membership_id');
    }

    public function user()
    {
        return $this->hasOneThrough(User::class, Membership::class, 'id', 'id', 'membership_id', 'user_id');
    }
}
