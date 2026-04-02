<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class UserHistoryReportExport implements FromCollection, WithHeadings, WithStyles, WithDrawings
{
    protected $reports;

    public function __construct($reports)
    {
        $this->reports = $reports;
    }

    public function collection()
    {
        return $this->reports->map(function ($report) {
            // Map subject_type to readable type
            $typeMap = [
                \App\Models\DriverItem::class => 'Driver',
                \App\Models\ArmadaItem::class => 'Armada',
                \App\Models\Car::class => 'Mobil',
                \App\Models\Driver::class => 'Driver',
                \App\Models\DailyReport::class => 'Laporan Harian',
                \App\Models\Safety::class => 'Safety Warning',
                \App\Models\User::class => 'User',
                \App\Models\Environment::class => 'Environment',
                \App\Models\Dokument::class => 'Document',
                \App\Exports\AdminMonthlyReportExport::class => 'Admin Report',
                \App\Exports\UserHistoryReportExport::class => 'User Report',
                // Tambahkan mapping lain jika ada
            ];
            if (isset($typeMap[$report->subject_type])) {
                $type = $typeMap[$report->subject_type];
            } elseif (
                isset($report->subject) && (
                    $report->subject instanceof \App\Models\Safety ||
                    (is_string($report->subject_type) && preg_match('/Safety/i', $report->subject_type))
                )
            ) {
                $type = 'Safety Warning';
            } elseif (
                isset($report->subject) && (
                    $report->subject instanceof \App\Models\Environment ||
                    (is_string($report->subject_type) && preg_match('/Environment/i', $report->subject_type))
                )
            ) {
                $type = 'Environment';
            } elseif (
                isset($report->subject) && (
                    $report->subject instanceof \App\Models\Dokument ||
                    (is_string($report->subject_type) && preg_match('/Dokument/i', $report->subject_type))
                )
            ) {
                $type = 'Document';
            } else {
                $type = $report->subject_type ?? 'Unknown';
            }
            return [
                'date' => \Carbon\Carbon::parse($report->date)->format('d M Y'),
                'driver_name' => $report->driver_name ?? '-',
                'type' => $type,
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
        // Hanya style header saja, baris lain default
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
                $drawing->setCoordinates('G' . $row);

                $drawings[] = $drawing;
            }
            $row++;
        }

        return $drawings;
    }
}
