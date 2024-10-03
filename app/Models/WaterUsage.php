<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'month',
        'year',
        'water_usage',
        'total_payment',
        'meter_size',
        'maintenance_fee',
        'late_fee',
        'category_id',
        'payment_delay', // pastikan payment_delay dapat diisi
    ];
    
    // Add relationship with the Category model
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function calculateMaintenanceFee()
    {
        $maintenanceFees = [
            1 => 5000,   // ½”
            2 => 7500,   // ¾”
            3 => 12500,  // 1”
            4 => 17500,  // 1 ½”
            5 => 27500,  // 2”
            6 => 52500,  // 3”
        ];

        return $maintenanceFees[$this->meter_size] ?? 0;
    }

    public function calculateLateFee($currentDate)
    {
        $lateFee = 0;
        // Add logic based on category and duration of late payment

        return $lateFee;
    }
}


