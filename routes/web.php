<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\Http\Controllers\WaterUsageController;

Route::get('/water-usages', [WaterUsageController::class, 'index'])->name('water_usages.index');
Route::get('/water-usages/create', [WaterUsageController::class, 'create'])->name('water_usages.create');
Route::post('/water-usages', [WaterUsageController::class, 'store'])->name('water_usages.store');

Route::get('/water-usages/export', [WaterUsageController::class, 'exportExcel'])->name('water_usages.export');

Route::get('/dashboard', [WaterUsageController::class, 'dashboard'])->name('dashboard');


