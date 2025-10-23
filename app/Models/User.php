<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
    'name', 'email', 'password', 'phone', 'role', 'image_url', 'address'
    ];

    protected $hidden = [
    'password', 'remember_token',
    ];

 // Get user's role name
    public function getRoleName(): string
    {
        return $this->role ?? 'No Role';
    }

    // Helper methods for role checking - PERBAIKI INI
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    
    public function isTrainer(): bool
    {
        return $this->role === 'trainer';
    }
    
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function membership()
    {
        return $this->hasOne(Membership::class, 'user_id');
    }

    // Tambahkan di User.php
    public function enrolledClasses()
    {
        return $this->belongsToMany(GymClass::class, 'class_members', 'user_id', 'class_id')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    public function activeMembership()
    {
        return $this->hasOne(Membership::class, 'user_id')->where('status', 'active');
    }

    public function userProgress()
    {
        return $this->hasMany(UserProgress::class, 'user_id');
    }

     public function hasActiveMembership(): bool
    {
        return $this->activeMembership()->exists();
    }
}
