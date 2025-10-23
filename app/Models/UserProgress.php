<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserProgress extends Model
{
    protected $fillable = [
        'user_id',
        'duration',
        'exercise',
        'description',
    ];

    // Relasi ke User  public function user()
   public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
