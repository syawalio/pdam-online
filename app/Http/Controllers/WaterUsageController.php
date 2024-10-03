<?php

namespace App\Http\Controllers;

use App\Models\WaterUsage;
use Illuminate\Http\Request;
use App\Models\Category; 
use App\Exports\WaterUsagesExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class WaterUsageController extends Controller
{
    public function index(Request $request)
    {
        // Query untuk filter berdasarkan bulan dan tahun
        $query = WaterUsage::query();

        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        // Pagination dengan 10 data per halaman
        $usages = $query->paginate(10);

        return view('water_usages.index', compact('usages'));
    }

    public function create()
    {
        $categories = Category::all(); // Ambil semua kategori
        return view('water_usages.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'usage_date' => 'required|date',
            'category' => 'required|exists:categories,id',
            'water_usage' => 'required|numeric',
            'meter_size' => 'required|integer',
            'payment_delay' => 'required|integer', // Menyimpan berapa bulan terlambat
        ]);

        // Ambil kategori
        $category = Category::find($request->category);

        // Hitung biaya pemeliharaan berdasarkan ukuran meter air
        $maintenanceFee = $this->calculateMaintenanceFee($request->meter_size);

        // Hitung denda keterlambatan berdasarkan kategori dan keterlambatan
        $lateFee = $this->calculateLateFee($category, $request->payment_delay);

        // Ambil total payment dari input hidden_total_payment
        $totalPayment = $request->hidden_total_payment + $maintenanceFee + $lateFee;

        // Simpan data
        WaterUsage::create([
            'customer_name' => $request->customer_name,
            'month' => Carbon::parse($request->usage_date)->month,
            'year' => Carbon::parse($request->usage_date)->year,
            'water_usage' => $request->water_usage,
            'total_payment' => $totalPayment,
            'category_id' => $category->id,
            'meter_size' => $request->meter_size,
            'maintenance_fee' => $maintenanceFee,
            'late_fee' => $lateFee,
        ]);

        return redirect()->route('water_usages.index')->with('success', 'Data berhasil disimpan.');
    }

    // Fungsi untuk menghitung biaya pemeliharaan
    private function calculateMaintenanceFee($meterSize)
    {
        $maintenanceFees = [
            1 => 5000,   // ½”
            2 => 7500,   // ¾”
            3 => 12500,  // 1”
            4 => 17500,  // 1 ½”
            5 => 27500,  // 2”
            6 => 52500,  // 3”
        ];

        return $maintenanceFees[$meterSize] ?? 0;
    }

    // Fungsi untuk menghitung denda keterlambatan
    private function calculateLateFee($category, $delayInMonths)
    {
        $lateFees = [
            'Sosial' => [2000, 4500, 7000],
            'Non Niaga' => [5000, 12000, 20000],
            'Niaga Kecil' => [7000, 15000, 25000],
            'Niaga Besar' => [10000, 25000, 40000],
            'Industri' => [10000, 25000, 45000],
            'Khusus' => [20000, 45000, 70000],
        ];

        $categoryName = $category->name; // Pastikan kategori memiliki atribut name
        $feeIndex = min($delayInMonths - 1, 2); // Pastikan nilai maksimum adalah 2 (untuk 3 bulan)
        return $lateFees[$categoryName][$feeIndex] ?? 0;
    }

    // Fungsi untuk menghitung total pembayaran
    private function calculateTotalPayment($waterUsage, $category)
    {
        $total = 0;

        // Ambil tarif berdasarkan kategori
        $rate0_10 = $category->rate_0_10;
        $rate11_20 = $category->rate_11_20;
        $rate21Plus = $category->rate_21_plus;

        // Hitung total berdasarkan pemakaian
        if ($waterUsage <= 10) {
            $total = $waterUsage * $rate0_10;
        } elseif ($waterUsage <= 20) {
            $total = (10 * $rate0_10) + (($waterUsage - 10) * $rate11_20);
        } else {
            $total = (10 * $rate0_10) + (10 * $rate11_20) + (($waterUsage - 20) * $rate21Plus);
        }

        return $total;
    }

    public function exportExcel()
    {
        return Excel::download(new WaterUsagesExport, 'data-penggunaan-air.xlsx');
    }

    public function dashboard()
    {
        $monthlyUsage = WaterUsage::selectRaw('month, SUM(water_usage) as total_usage')
                                ->groupBy('month')
                                ->get();

        $labels = $monthlyUsage->pluck('month');
        $data = $monthlyUsage->pluck('total_usage');

        return view('dashboard', compact('labels', 'data'));
    }
}
