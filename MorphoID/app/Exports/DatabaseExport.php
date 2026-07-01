<?php

namespace App\Exports;

use App\Models\Specimen;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DatabaseExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        return Specimen::with(['category', 'parent'])->orderBy('category_id')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Specimen Name',
            'Main Group (Category)',
            'Parent Specimen',
            'Description',
            'Characteristics',
            'Registered At'
        ];
    }

    public function map($specimen): array
    {
        $ciriCiri = 'N/A';
        if (is_array($specimen->ciri_ciri)) {
            $ciriCiri = implode(', ', $specimen->ciri_ciri);
        } elseif (is_string($specimen->ciri_ciri)) {
            $decoded = json_decode($specimen->ciri_ciri, true);
            if (is_array($decoded)) {
                $ciriCiri = implode(', ', $decoded);
            } else {
                $ciriCiri = $specimen->ciri_ciri;
            }
        }

        return [
            $specimen->id,
            $specimen->nama_spesimen,
            $specimen->category ? $specimen->category->nama_kategori : 'N/A',
            $specimen->parent ? $specimen->parent->nama_spesimen : 'None (Main Specimen)',
            $specimen->penerangan ?? 'N/A',
            $ciriCiri,
            $specimen->created_at ? $specimen->created_at->format('Y-m-d H:i:s') : 'N/A',
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
