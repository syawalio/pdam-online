<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWaterUsagesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('water_usages', function (Blueprint $table) {
        $table->id();
        $table->string('customer_name');
        $table->unsignedTinyInteger('month');
        $table->unsignedSmallInteger('year');
        $table->float('water_usage', 8, 2);
        $table->decimal('total_payment', 10, 2)->nullable();
        $table->unsignedTinyInteger('meter_size')->nullable(); // Ukuran meter air
        $table->decimal('maintenance_fee', 10, 2)->nullable(); // Biaya pemeliharaan
        $table->decimal('late_fee', 10, 2)->nullable(); // Denda keterlambatan
        $table->foreignId('category_id')->constrained()->onDelete('cascade'); 
        $table->timestamps();
        
        // Indeks untuk kolom month dan year
        $table->index(['month', 'year']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('water_usages');
    }
}
