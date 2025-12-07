<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassMember extends Model
{
    protected $table = 'class_members';

    protected $fillable = [
        'class_id',
        'user_id',
        'status', // Enum: 'registered', 'attended', 'cancelled'
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

    /**
     * Menghitung persentase progress user di kelas ini.
     * Mengembalikan nilai float 0 - 100.
     * Otomatis update status ke 'attended' (Selesai/Lulus) jika 100%.
     */
    public function calculateProgress()
    {
        // 1. Ambil total agenda di kelas ini
        // Kita gunakan full path class agar aman jika tidak di-import
        $totalAgendas = \App\Models\ClassAgenda::where('gym_class_id', $this->class_id)->count();

        // Jika tidak ada agenda, return 0
        if ($totalAgendas === 0) {
            return 0;
        }

        // 2. Hitung berapa agenda yang sudah diselesaikan
        $completedCount = \App\Models\MemberClassProgress::where('user_id', $this->user_id)
            ->whereHas('agenda', function ($query) {
                $query->where('gym_class_id', $this->class_id);
            })
            ->count();

        // 3. Hitung persentase
        $percentage = round(($completedCount / $totalAgendas) * 100);

        // 4. Logika Update Status Lulus
        // [PERBAIKAN]: Ganti 'passed' menjadi 'attended' sesuai kolom enum di database
        if ($percentage >= 100 && $this->status !== 'attended') {
            $this->update(['status' => 'attended']);
        }

        return $percentage;
    }
}
