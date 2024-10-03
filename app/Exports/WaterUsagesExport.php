<?php

namespace App\Exports;

use App\Models\WaterUsage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Font;

class WaterUsagesExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return WaterUsage::with('category')->get()->map(function ($usage) {
            return [
                'Nama Pelanggan' => $usage->customer_name,
                'Bulan' => \Carbon\Carbon::create()->month($usage->month)->format('F'),
                'Tahun' => $usage->year,
                'Golongan Rumah' => optional($usage->category)->name,
                'Ukuran Meter' => $usage->meter_size,
                'Penggunaan Air (m³)' => $usage->water_usage,
                'Harga per m³' => optional($usage->category)->rate,
                'Keterlambatan Pembayaran (bulan)' => $usage->payment_delay,
                'Biaya Pemeliharaan' => $usage->maintenance_fee,
                'Denda Keterlambatan' => $usage->late_fee,
                'Total Pembayaran' => $usage->total_payment,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Pelanggan',
            'Bulan',
            'Tahun',
            'Golongan Rumah',
            'Ukuran Meter',
            'Penggunaan Air (m³)',
            'Harga per m³',
            'Keterlambatan Pembayaran (bulan)',
            'Biaya Pemeliharaan',
            'Denda Keterlambatan',
            'Total Pembayaran',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Set header styling
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);
        $sheet->getStyle('A1:K1')->getFont()->setSize(12);
        $sheet->getStyle('A1:K1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A1:K1')->getFill()->getStartColor()->setARGB(Color::COLOR_YELLOW);
        
        // Set alignment
        $sheet->getStyle('A1:K1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Set border for header
        $sheet->getStyle('A1:K1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        
        // Set border for all data cells
        $sheet->getStyle('A2:K' . (count($this->collection()) + 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    }
}
