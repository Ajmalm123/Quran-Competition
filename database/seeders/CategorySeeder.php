<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Category 1',
                'gender_restriction' => 'Both',
                'is_active' => true,
                'description' => 'Open for both male and female participants'
            ],
            [
                'name' => 'Category 2',
                'gender_restriction' => 'Male',
                'is_active' => true,
                'description' => 'Only for male participants'
            ],
            [
                'name' => 'Category 3',
                'gender_restriction' => 'Female',
                'is_active' => true,
                'description' => 'Only for female participants'
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
} 