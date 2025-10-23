<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserProgressSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->take(6)->get();
        $progress = [
            [
                'user_id'    => $customers[0]->id, 
                'duration'   => 45,
                'exercise'   => 'Abs',
                'description'=> 'Matras, 3 set sit-up (12 reps), 3 set plank (60 detik), beban 5kg',
            ],
            [
                'user_id'    => $customers[1]->id, 
                'duration'   => 60,
                'exercise'   => 'Chest',
                'description'=> 'Bench press 4 set (12 reps), dumbbell fly 3 set (10 reps), beban terakhir 50kg',
            ],
            [
                'user_id'    => $customers[2]->id, 
                'duration'   => 40,
                'exercise'   => 'Shoulder',
                'description'=> 'Overhead press 3 set (12 reps), lateral raise 3 set (15 reps), beban terakhir 12kg',
            ],
            [
                'user_id'    => $customers[3]->id, 
                'duration'   => 50,
                'exercise'   => 'Legs',
                'description'=> 'Squat rack 4 set (10 reps), lunges 3 set (12 reps tiap kaki), beban terakhir 40kg',
            ],
            [
                'user_id'    => $customers[4]->id, 
                'duration'   => 35,
                'exercise'   => 'Biceps',
                'description'=> 'Bicep curl 4 set (15 reps), hammer curl 3 set (12 reps), beban terakhir 10kg',
            ],
            [
                'user_id'    => $customers[5]->id, 
                'duration'   => 55,
                'exercise'   => 'Back',
                'description'=> 'Lat pulldown 4 set (12 reps), deadlift 3 set (8 reps), beban terakhir 60kg',
            ]
        ];

        DB::table('user_progress')->insert($progress);
    }
}
