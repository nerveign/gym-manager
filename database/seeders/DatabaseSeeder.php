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
            EquipmentSeeder::class,
            GymClassesSeeder::class,
            BookingSeeder::class,
            ClassMemberSeeder::class,
            UserProgressSeeder::class,
            TransactionSeeder::class, // Tambahkan ini
        ]);
    }
}