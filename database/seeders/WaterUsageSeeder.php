<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WaterUsage;

class WaterUsageSeeder extends Seeder
{
    public function run()
    {
        WaterUsage::create([
            'customer_name' => 'John Doe',
            'month' => 8,
            'year' => 2023,
            'water_usage' => 25.50,
            'total_payment' => 50000,
            'meter_size' => 1, // Example meter size ½”
            'maintenance_fee' => 5000,
            'late_fee' => 0,
            'category_id' => 1, // Make sure category exists
        ]);

    }
}