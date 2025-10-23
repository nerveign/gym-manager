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
                'image_url'      => 'https://picsum.photos/200?random=11',
                'description'    => 'Treadmill elektrik dengan 15 program latihan dan kecepatan hingga 18 km/jam.',
            ],
            [
                'equipment_name' => 'Dumbbell Set 1–20kg',
                'brand'          => 'Jordan Fitness',
                'condition'      => 'Baru',
                'image_url'      => 'https://picsum.photos/200?random=12',
                'description'    => 'Set dumbbell karet anti-slip untuk latihan beban progresif.',
            ],
            [
                'equipment_name' => 'Barbell Olympic 20kg',
                'brand'          => 'Rogue',
                'condition'      => 'Baik',
                'image_url'      => 'https://picsum.photos/200?random=13',
                'description'    => 'Barbell olympic dengan kapasitas hingga 250kg, cocok untuk squat dan deadlift.',
            ],
            [
                'equipment_name' => 'Bench Press Adjustable',
                'brand'          => 'Technogym',
                'condition'      => 'Baik',
                'image_url'      => 'https://picsum.photos/200?random=14',
                'description'    => 'Bangku latihan dengan posisi flat, incline, dan decline.',
            ],
            [
                'equipment_name' => 'Pull Up Bar Wall Mount',
                'brand'          => 'Decathlon',
                'condition'      => 'Baik',
                'image_url'      => 'https://picsum.photos/200?random=15',
                'description'    => 'Pull up bar yang dipasang di dinding, cocok untuk bodyweight training.',
            ],
            [
                'equipment_name' => 'Kettlebell 16kg',
                'brand'          => 'Eleiko',
                'condition'      => 'Baru',
                'image_url'      => 'https://picsum.photos/200?random=16',
                'description'    => 'Kettlebell baja solid untuk latihan ayunan, squat, dan conditioning.',
            ],
            [
                'equipment_name' => 'Stationary Bike X-Spin',
                'brand'          => 'Schwinn',
                'condition'      => 'Baik',
                'image_url'      => 'https://picsum.photos/200?random=17',
                'description'    => 'Sepeda statis untuk latihan kardio dengan pengaturan level resistensi.',
            ],
            [
                'equipment_name' => 'Rowing Machine R500',
                'brand'          => 'Concept2',
                'condition'      => 'Baik',
                'image_url'      => 'https://picsum.photos/200?random=18',
                'description'    => 'Mesin dayung untuk latihan seluruh tubuh dan peningkatan stamina.',
            ],
            [
                'equipment_name' => 'Leg Press Machine',
                'brand'          => 'Hammer Strength',
                'condition'      => 'Baik',
                'image_url'      => 'https://picsum.photos/200?random=19',
                'description'    => 'Mesin khusus latihan otot kaki dengan kapasitas beban hingga 200kg.',
            ],
            [
                'equipment_name' => 'Smith Machine Pro',
                'brand'          => 'Matrix',
                'condition'      => 'Baru',
                'image_url'      => 'https://picsum.photos/200?random=20',
                'description'    => 'Mesin smith untuk latihan beban dengan keamanan tinggi.',
            ],
        ];

        DB::table('equipment')->insert($equipment);
    }
}
