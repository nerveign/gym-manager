<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name'      => 'Tiffanxaa',
                'email'     => 'tiffanxaa@outlook.com',
                'phone'     => '081234567890',
                'password'  =>  Hash::make('tiffanxaa123'),
                'address'   => 'Jl. Merpati No. 1, Jakarta',
                'image_url' => 'https://cdn.rafled.com/anime-icons/images/d702d7799ce8a95343cc88d0badc6f2b665fb07b7e85d46ab8b35d8fe66f9433.jpg',
                'role'      => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'      => 'Bob Missile',
                'email'     => 'bob@example.com',
                'phone'     => '089674567891',
                'password'  =>  Hash::make('bob123'),
                'address'   => 'Jl. Kenari No. 2, Bandung',
                'image_url' => 'https://blog-pixomatic.s3.appcnt.com/image/22/01/26/61f166e1e3b25/_orig/pixomatic_1572877090227.png',
                'role'      => 'trainer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'      => 'Charlie',
                'email'     => 'charlie@example.com',
                'phone'     => '081234567892',
                'password'  =>  Hash::make('charlie123'),
                'address'   => 'Jl. Anggrek No. 3, Jakarta Barat',
                'image_url' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&q=80&w=880',
                'role'      => 'trainer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'      => 'Sela Saravati',
                'email'     => 'sersarv@example.com',
                'phone'     => '081234567893',
                'password'  =>  Hash::make('sela123'),
                'address'   => 'Jl. Melati No. 4, Kab Tangerang',
                'image_url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&q=80&w=764',
                'role'      => 'trainer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [   
                'name'      => 'Erik Yunanda',
                'email'     => 'erik@example.com',
                'phone'     => '081234567894',
                'password'  =>  Hash::make('erik123'),
                'address'   => 'Jl. Mawar No. 5, Medan',
                'image_url' => 'https://img.freepik.com/free-photo/portrait-white-man-isolated_53876-40306.jpg?semt=ais_hybrid&w=740&q=80',
                'role'      => 'customer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'      => 'Fiona Anatasya',
                'email'     => 'fionaalatte@example.com',
                'phone'     => '081234567895',
                'password'  =>  Hash::make('fiona123'),
                'address'   => 'Jl. Kamboja No. 6, Malang',
                'image_url' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&q=80&w=1170',
                'role'      => 'customer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'      => 'George Bush',
                'email'     => 'george@example.com',
                'phone'     => '081234567896',
                'password'  =>  Hash::make('george123'),
                'address'   => 'Jl. Teratai No. 7, Semarang',
                'image_url' => 'https://images.unsplash.com/photo-1531891437562-4301cf35b7e4?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&q=80&w=764',
                'role'      => 'customer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'      => 'Xenxaa',
                'email'     => 'tiffanxaa@gmail.com',
                'phone'     => '081234567897',
                'password'  =>  Hash::make('hannah123'),
                'address'   => 'Jl. Dahlia No. 8, Kota Tangerang',
                'image_url' => 'https://www.shutterstock.com/image-photo/close-head-shot-portrait-preppy-600nw-1433809418.jpg',
                'role'      => 'customer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'      => 'Ivan Liem',
                'email'     => 'ivan@example.com',
                'phone'     => '081234567898',
                'password'  =>  Hash::make('ivan123'),
                'address'   => 'Jl. Flamboyan No. 9, Balikpapan',
                'image_url' => 'https://www.catholicsingles.com/wp-content/uploads/2020/06/blog-header-3.png',
                'role'      => 'customer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'      => 'Jennifer Laura',
                'email'     => 'jennlaura10@mail.com',
                'phone'     => '081234567899',
                'password'  =>  Hash::make('julia123'),
                'address'   => 'Jl. Cendana No. 10, Makassar',
                'image_url' => 'https://img.freepik.com/free-photo/front-view-business-woman-suit_23-2148603018.jpg?semt=ais_hybrid&w=740&q=80',
                'role'      => 'customer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
