<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassAgenda extends Model
{
    protected $fillable = ['gym_class_id', 'title', 'order'];

    // Relasi balik ke GymClass
    public function gymClass()
    {
        return $this->belongsTo(GymClass::class, 'gym_class_id');
    }

    // Helper: Cek apakah agenda ini sudah selesai oleh user tertentu
    public function isCompletedBy($userId)
    {
        return $this->hasOne(MemberClassProgress::class, 'class_agenda_id')
                    ->where('user_id', $userId)
                    ->exists();
    }
}