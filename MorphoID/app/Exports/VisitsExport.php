<?php

namespace App\Exports;

use App\Models\LoginLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VisitsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        return LoginLog::orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Date & Time',
            'User ID',
            'Name',
            'Email Address',
            'Account Role',
            'Status'
        ];
    }

    public function map($log): array
    {
        return [
            $log->id,
            $log->created_at ? $log->created_at->format('Y-m-d H:i:s') : 'N/A',
            $log->userid,
            $log->name ?? '-',
            $log->email,
            $log->role,
            $log->status,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']], 
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF4B5563']]
            ],
        ];
    }
}
