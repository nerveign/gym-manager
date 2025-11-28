<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $equipment = [
            [
                'equipment_name' => 'Treadmill Pro Runner',
                'brand'          => 'Life Fitness',
                'condition'      => 'Baik',
                'quantity'       => 5,
                'image_url'      => 'https://chrissports.com/cdn/shop/files/TraxRunnerProTreadmill-1_800x.png?v=1716905218',
                'description'    => 'Treadmill elektrik dengan 15 program latihan dan kecepatan hingga 18 km/jam.',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'equipment_name' => 'Dumbbell Set 1–20kg',
                'brand'          => 'Jordan Fitness',
                'condition'      => 'Baru',
                'quantity'       => 10,
                'image_url'      => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRS97TR3-TR9DvFN10nh8VEt1VPSgrgP3bxcA&s',
                'description'    => 'Set dumbbell karet anti-slip untuk latihan beban progresif.',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'equipment_name' => 'Barbell Olympic 20kg',
                'brand'          => 'Rogue',
                'condition'      => 'Baik',
                'quantity'       => 8,
                'image_url'      => 'https://rawfitnessequipment.com.au/cdn/shop/products/20kg_BLACK_01_dbf2656c-9195-4351-81df-9243c1828c6f_300x300_crop_center.jpg?v=1599856213',
                'description'    => 'Barbell olympic dengan kapasitas hingga 250kg, cocok untuk squat dan deadlift.',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'equipment_name' => 'Yoga Mat Premium',
                'brand'          => 'Lululemon',
                'condition'      => 'Baru',
                'quantity'       => 20,
                'image_url'      => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQfA9SynF_Mpuga4xzHiWUNj4eIPJndZiF-NA&s',
                'description'    => 'Matras yoga anti-slip dengan ketebalan 5mm untuk kenyamanan maksimal.',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'equipment_name' => 'Stationary Bike X1',
                'brand'          => 'Technogym',
                'condition'      => 'Sedang',
                'quantity'       => 6,
                'image_url'      => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQfA9SynF_Mpuga4xzHiWUNj4eIPJndZiF-NA&s',
                'description'    => 'Sepeda statis untuk latihan kardio dengan pengaturan level resistensi.',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'equipment_name' => 'Rowing Machine R500',
                'brand'          => 'Concept2',
                'condition'      => 'Baik',
                'quantity'       => 3,
                'image_url'      => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQlYBJrFZXYmwlTUOp83Y8n8yddnRgKaUUELg&s',
                'description'    => 'Mesin dayung untuk latihan seluruh tubuh dan peningkatan stamina.',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'equipment_name' => 'Leg Press Machine',
                'brand'          => 'Hammer Strength',
                'condition'      => 'Baik',
                'quantity'       => 2,
                'image_url'      => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTSf6nd7i5Ki8WgwVbh4sO7zp5mKb6PtQbiKg&s',
                'description'    => 'Mesin khusus latihan otot kaki dengan kapasitas beban hingga 200kg.',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'equipment_name' => 'Smith Machine Pro',
                'brand'          => 'Matrix',
                'condition'      => 'Baru',
                'quantity'       => 1,
                'image_url'      => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT7eZq_8J0_0_0_0_0_0_0_0_0_0_0_0_0', // URL dummy dari file asli
                'description'    => 'Mesin Smith multifungsi untuk latihan bench press dan squat yang aman.',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ];

        DB::table('equipment')->insert($equipment);
    }
}
