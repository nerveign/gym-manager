<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\GymClass;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassMemberSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $classes = GymClass::all();

        $classMembers = [
            // Class 1: Strength Training - 3 members
            [
                'class_id' => $classes[0]->id,
                'user_id'  => $customers[0]->id, // erik
                'status'   => 'registered',
            ],
            [
                'class_id' => $classes[0]->id,
                'user_id'  => $customers[1]->id, // fiona
                'status'   => 'attended',
            ],
            [
                'class_id' => $classes[0]->id,
                'user_id'  => $customers[2]->id, // george
                'status'   => 'registered',
            ],

            // Class 2: Yoga - 4 members
            [
                'class_id' => $classes[1]->id,
                'user_id'  => $customers[1]->id, // fiona
                'status'   => 'attended',
            ],
            [
                'class_id' => $classes[1]->id,
                'user_id'  => $customers[3]->id, // Xena
                'status'   => 'registered',
            ],
            [
                'class_id' => $classes[1]->id,
                'user_id'  => $customers[4]->id, // ivan
                'status'   => 'registered',
            ],
            [
                'class_id' => $classes[1]->id,
                'user_id'  => $customers[5]->id, // Jennifer
                'status'   => 'cancelled',
            ],

            // Class 3: Zumba - 2 members
            [
                'class_id' => $classes[2]->id,
                'user_id'  => $customers[0]->id, // erik
                'status'   => 'attended',
            ],
            [
                'class_id' => $classes[2]->id,
                'user_id'  => $customers[5]->id, // Jennifer
                'status'   => 'attended',
            ],

            // Class 4: Pilates - 3 members
            [
                'class_id' => $classes[3]->id,
                'user_id'  => $customers[2]->id, // george
                'status'   => 'registered',
            ],
            [
                'class_id' => $classes[3]->id,
                'user_id'  => $customers[3]->id, // Xena
                'status'   => 'registered',
            ],
            [
                'class_id' => $classes[3]->id,
                'user_id'  => $customers[4]->id, // ivan
                'status'   => 'registered',
            ],

            // Class 5: Aerobics - 2 members
            [
                'class_id' => $classes[4]->id,
                'user_id'  => $customers[0]->id, // erik
                'status'   => 'attended',
            ],
            [
                'class_id' => $classes[4]->id,
                'user_id'  => $customers[1]->id, // fiona
                'status'   => 'registered',
            ],

            // Class 6: Strength & Conditioning - 1 member
            [
                'class_id' => $classes[5]->id,
                'user_id'  => $customers[4]->id, // ivan
                'status'   => 'registered',
            ],
        ];

        DB::table('class_members')->insert($classMembers);
    }
}