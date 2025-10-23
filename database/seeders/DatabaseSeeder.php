<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            MembershipSeeder::class,
            UserProgressSeeder::class,
            EquipmentSeeder::class,
            GymClassesSeeder::class,
            ClassMemberSeeder::class, 
            BookingSeeder::class,
        ]);
    }
}