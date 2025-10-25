<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EquipmentSeeder extends Seeder
{
    public function run(): void
    {
        $equipment = [
            [
                'equipment_name' => 'Treadmill Pro Runner',
                'brand'          => 'Life Fitness',
                'condition'      => 'Baik',
                'image_url'      => 'https://chrissports.com/cdn/shop/files/TraxRunnerProTreadmill-1_800x.png?v=1716905218',
                'description'    => 'Treadmill elektrik dengan 15 program latihan dan kecepatan hingga 18 km/jam.',
            ],
            [
                'equipment_name' => 'Dumbbell Set 1–20kg',
                'brand'          => 'Jordan Fitness',
                'condition'      => 'Baru',
                'image_url'      => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRS97TR3-TR9DvFN10nh8VEt1VPSgrgP3bxcA&s',
                'description'    => 'Set dumbbell karet anti-slip untuk latihan beban progresif.',
            ],
            [
                'equipment_name' => 'Barbell Olympic 20kg',
                'brand'          => 'Rogue',
                'condition'      => 'Baik',
                'image_url'      => 'https://rawfitnessequipment.com.au/cdn/shop/products/20kg_BLACK_01_dbf2656c-9195-4351-81df-9243c1828c6f_300x300_crop_center.jpg?v=1599856213',
                'description'    => 'Barbell olympic dengan kapasitas hingga 250kg, cocok untuk squat dan deadlift.',
            ],
            [
                'equipment_name' => 'Bench Press Adjustable',
                'brand'          => 'Technogym',
                'condition'      => 'Baik',
                'image_url'      => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQv_q3tw78sUKhhPUyy4GSBVMKudZUrZyK0xA&s',
                'description'    => 'Bangku latihan dengan posisi flat, incline, dan decline.',
            ],
            [
                'equipment_name' => 'Pull Up Bar Wall Mount',
                'brand'          => 'Decathlon',
                'condition'      => 'Baik',
                'image_url'      => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ22D4p6ACh6EaIEQZqMWQDjEHfSogO61tC_w&s',
                'description'    => 'Pull up bar yang dipasang di dinding, cocok untuk bodyweight training.',
            ],
            [
                'equipment_name' => 'Kettlebell 16kg',
                'brand'          => 'Eleiko',
                'condition'      => 'Baru',
                'image_url'      => 'https://www.spsports.ie/wp-content/uploads/2021/01/UrbanKettle16.jpg',
                'description'    => 'Kettlebell baja solid untuk latihan ayunan, squat, dan conditioning.',
            ],
            [
                'equipment_name' => 'Stationary Bike X-Spin',
                'brand'          => 'Schwinn',
                'condition'      => 'Baik',
                'image_url'      => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQfA9SynF_Mpuga4xzHiWUNj4eIPJndZiF-NA&s',
                'description'    => 'Sepeda statis untuk latihan kardio dengan pengaturan level resistensi.',
            ],
            [
                'equipment_name' => 'Rowing Machine R500',
                'brand'          => 'Concept2',
                'condition'      => 'Baik',
                'image_url'      => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQlYBJrFZXYmwlTUOp83Y8n8yddnRgKaUUELg&s',
                'description'    => 'Mesin dayung untuk latihan seluruh tubuh dan peningkatan stamina.',
            ],
            [
                'equipment_name' => 'Leg Press Machine',
                'brand'          => 'Hammer Strength',
                'condition'      => 'Baik',
                'image_url'      => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTSf6nd7i5Ki8WgwVbh4sO7zp5mKb6PtQbiKg&s',
                'description'    => 'Mesin khusus latihan otot kaki dengan kapasitas beban hingga 200kg.',
            ],
            [
                'equipment_name' => 'Smith Machine Pro',
                'brand'          => 'Matrix',
                'condition'      => 'Baru',
                'image_url'      => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ9OXNTtiOOYwrQgUzM1uQVG_lvo_b_czEK0A&s',
                'description'    => 'Mesin smith untuk latihan beban dengan keamanan tinggi.',
            ],
        ];

        DB::table('equipment')->insert($equipment);
    }
}
