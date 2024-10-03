<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Kelompok 1A (Rumah Tangga Sederhana)',
                'rate_0_10' => 2750,
                'rate_11_20' => 3500,
                'rate_21_plus' => 4500,
            ],
            [
                'name' => 'Kelompok 1B (Rumah Tangga Menengah)',
                'rate_0_10' => 3750,
                'rate_11_20' => 5000,
                'rate_21_plus' => 6500,
            ],
            [
                'name' => 'Kelompok 1C (Rumah Tangga Menengah)',
                'rate_0_10' => 3750,
                'rate_11_20' => 5000,
                'rate_21_plus' => 6500,
            ],
            [
                'name' => 'Kelompok 2 (Rumah Tangga Mampu/Menengah Atas)',
                'rate_0_10' => 5000,
                'rate_11_20' => 7500,
                'rate_21_plus' => 9250,
            ],
        ];

        // Menyimpan data ke database
        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
