<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GymClass extends Model
{
    protected $table = 'gym_classes';
    protected $fillable = [
        'trainer_id',
        'schedule',
        'capacity',        
        'type',
        'description',
    ];

    // Relasi ke Trainer
    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    // Relasi many-to-many ke members melalui class_members
    public function members()
    {
        return $this->belongsToMany(User::class, 'class_members', 'class_id', 'user_id')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    // Relasi ke class_members
    public function classMembers()
    {
        return $this->hasMany(ClassMember::class, 'class_id');
    }
}