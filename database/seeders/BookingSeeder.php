<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Membership;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil data trainer
        $trainer = User::where('role', 'trainer')->get();

        // PERBAIKAN: Ambil semua membership (jangan filter 'active' saja karena di seeder semua inactive)
        $membership = Membership::all();

        // Cek dulu apakah data trainer dan membership cukup sebelum insert
        if ($trainer->count() < 3 || $membership->count() < 5) {
            $this->command->warn('Data Trainer atau Membership kurang. BookingSeeder dilewati.');
            return;
        }

        DB::table('bookings')->insert([
            [
                'trainer_id'    => $trainer[0]->id,
                'membership_id' => $membership[0]->id,
                'duration'      => 60,
                'date'          => '2025-09-25',
                'time'          => '09:00:00',
                'created_at'    => now(), // Baiknya ditambahkan
                'updated_at'    => now(),
            ],
            [
                'trainer_id'    => $trainer[1]->id,
                'membership_id' => $membership[1]->id,
                'duration'      => 90,
                'date'          => '2025-09-26',
                'time'          => '07:30:00',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'trainer_id'    => $trainer[2]->id,
                'membership_id' => $membership[2]->id,
                'duration'      => 45,
                'date'          => '2025-09-27',
                'time'          => '18:00:00',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'trainer_id'    => $trainer[0]->id,
                'membership_id' => $membership[3]->id,
                'duration'      => 60,
                'date'          => '2025-09-28',
                'time'          => '16:00:00',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'trainer_id'    => $trainer[1]->id,
                'membership_id' => $membership[4]->id,
                'duration'      => 50,
                'date'          => '2025-09-29',
                'time'          => '08:00:00',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}
