<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassMember extends Model
{
    protected $fillable = [
        'class_id',
        'user_id', 
        'status',
    ];

    // Relasi ke GymClass
    public function gymClass()
    {
        return $this->belongsTo(GymClass::class, 'class_id');
    }

    // Relasi ke User (Member)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}