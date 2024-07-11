<?php
namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ZonesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $zones = [
            'Kollam',
            'Ernakulam',
            'Malappuram',
            'Kannur',
            'Jeddah',
            'Dubai',
            'Doha',
            'Bahrain',
            'Muscat',
            'Kuwait'
        ];

        foreach ($zones as $zone) {
            DB::table('zones')->insert([
                'name' => $zone,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
