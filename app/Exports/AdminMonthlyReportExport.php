<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class AdminMonthlyReportExport implements FromCollection, WithHeadings, WithStyles, WithDrawings
{
    protected $reports;
    protected $month;

    public function __construct($reports, $month)
    {
        $this->reports = $reports;
        $this->month = $month;
    }

    public function collection()
    {
        return $this->reports->map(function ($report) {
            return [
                'date' => \Carbon\Carbon::parse($report->date)->format('d M Y'),
                'user_name' => $report->user->name ?? '-',
                'user_plat' => $report->user && $report->user->plat_nomor ? $report->user->plat_nomor : '-',
                'driver_name' => $report->driver_name ?? '-',
                'type' => $report->subject_type === \App\Models\DriverItem::class ? 'Driver' : 'Armada',
                'safety_items' => $report->subject ? $report->subject->safety_items : '-',
                'standard_items' => $report->subject ? $report->subject->standard_items : '-',
                'status' => $report->status,
                'notes' => $report->notes ?? '-',
                'image_url' => $report->image_path ? asset('storage/' . $report->image_path) : '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama User',
            'Plat User',
            'Nama Driver',
            'Tipe',
            'Safety Items',
            'Standard Items',
            'Status',
            'Catatan',
            'Gambar',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '31ce36']],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function drawings()
    {
        $drawings = [];
        $row = 2;

        foreach ($this->reports as $report) {
            if ($report->image_path && file_exists(storage_path('app/public/' . $report->image_path))) {
                $drawing = new Drawing();
                $drawing->setName('Gambar');
                $drawing->setDescription('Gambar Laporan');
                $drawing->setPath(storage_path('app/public/' . $report->image_path));
                $drawing->setHeight(60);
                $drawing->setWidth(80);
                $drawing->setCoordinates('I' . $row);

                $drawings[] = $drawing;
            }
            $row++;
        }

        return $drawings;
    }
}
