<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class UserImportTemplateExport implements FromArray, WithHeadings, WithEvents
{
    public function headings(): array
    {
        // Doit rester aligné avec les clés lues par App\Imports\UserImport::model()/rules().
        return ['Nom', 'Prenom', 'Matricule', 'Email', 'Contact', 'Emploie', 'Fonction', 'Grade', 'Role'];
    }

    public function array(): array
    {
        return [
            ['Diallo', 'Awa', 'MAT-0001', 'awa.diallo@example.com', '+221770000000', 'CDI', 'Informaticien', 'Cadre', 'technicien'],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:I1';
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFEFEFEF'],
                    ],
                    'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
                ]);
                foreach (range('A', 'I') as $col) {
                    $event->sheet->getDelegate()->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
