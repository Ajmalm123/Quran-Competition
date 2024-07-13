<?php
namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ZonesTableSeeder extends Seeder
{
    public function run()
    {
        $zones = [
            [
                'name' => 'Kollam',
                'email' => 'kollam@test.com',
                'password' => Hash::make('password123'),
                'area' => 'Native',
            ],
            [
                'name' => 'Ernakulam',
                'email' => 'ernakulam@test.com',
                'password' => Hash::make('password123'),
                'area' => 'Native',
            ],
            [
                'name' => 'Malappuram',
                'email' => 'malappuram@test.com',
                'password' => Hash::make('password123'),
                'area' => 'Native',
            ],
            [
                'name' => 'Kannur',
                'email' => 'kannur@test.com',
                'password' => Hash::make('password123'),
                'area' => 'Native',
            ],
            [
                'name' => 'Jeddah',
                'email' => 'jeddah@test.com',
                'password' => Hash::make('password123'),
                'area' => 'Abroad',
            ],
            [
                'name' => 'Dubai',
                'email' => 'dubai@test.com',
                'password' => Hash::make('password123'),
                'area' => 'Abroad',
            ],
            [
                'name' => 'Doha',
                'email' => 'doha@test.com',
                'password' => Hash::make('password123'),
                'area' => 'Abroad',
            ],
            [
                'name' => 'Bahrain',
                'email' => 'bahrain@test.com',
                'password' => Hash::make('password123'),
                'area' => 'Abroad',
            ],
            [
                'name' => 'Muscat',
                'email' => 'muscat@test.com',
                'password' => Hash::make('password123'),
                'area' => 'Abroad',
            ],
            [
                'name' => 'Kuwait',
                'email' => 'kuwait@test.com',
                'password' => Hash::make('password123'),
                'area' => 'Abroad',
            ],
        ];

        DB::table('zones')->insert($zones);
    }
}
