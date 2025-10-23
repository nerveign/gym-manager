<?php

namespace Database\Seeders;
use App\Models\Membership;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GymClassesSeeder extends Seeder
{
    public function run(): void
    {
        $trainer = User::where('role', 'trainer')->get();
        DB::table('gym_classes')->insert([
            [
                'trainer_id'   => $trainer[0]->id, 
                'schedule'     => '2025-09-25 09:00:00',
                'capacity'     => 5,
                'type'         => 'Strength Training',
                'description'  => 'Latihan kekuatan untuk meningkatkan massa otot',
            ],
            [
                'trainer_id'   => $trainer[1]->id, 
                'schedule'     => '2025-09-26 07:30:00',
                'capacity'     => 8,
                'type'         => 'Yoga',
                'description'  => 'Kelas yoga untuk fleksibilitas dan relaksasi',
            ],
            [
                'trainer_id'   => $trainer[2]->id,
                'schedule'     => '2025-09-27 18:00:00',
                'capacity'     => 7,
                'type'         => 'Zumba',
                'description'  => 'Zumba dance untuk membakar kalori dengan musik energik',
            ],
            [
                'trainer_id'   => $trainer[0]->id, 
                'schedule'     => '2025-09-28 16:00:00',
                'capacity'     => 6,
                'type'         => 'Pilates',
                'description'  => 'Latihan pilates fokus pada core dan postur tubuh',
            ],
            [
                'trainer_id'   => $trainer[1]->id, 
                'schedule'     => '2025-09-29 08:00:00',
                'capacity'     => 7,
                'type'         => 'Aerobics',
                'description'  => 'Kelas aerobik dengan gerakan dinamis untuk stamina',
            ],
            [
                'trainer_id'   => $trainer[2]->id, 
                'schedule'     => '2025-09-30 19:00:00',
                'capacity'     => 6,
                'type'         => 'Strength & Conditioning',
                'description'  => 'Latihan untuk kekuatan dan ketahanan tubuh',
            ],
        ]);
    }
}
