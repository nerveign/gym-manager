<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberClassProgress extends Model
{
    protected $table = 'member_class_progress';

    protected $fillable = [
        'user_id',
        'class_agenda_id',
        'marked_by',
        'completed_at'
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function agenda()
    {
        return $this->belongsTo(ClassAgenda::class, 'class_agenda_id');
    }

    public function marker()
    {
        return $this->belongsTo(User::class, 'marked_by');
    }
}
