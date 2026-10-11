<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class EquipementImportTemplateExport implements FromArray, WithHeadings, WithEvents
{
    public function headings(): array
    {
        // Doit rester aligné avec les clés lues par App\Imports\EquipementImport::model()/rules().
        return [
            'des_equipement', 'marque', 'modele', 'categorie', 'nature', 'num_inventaire',
            'adresse_mac', 'numero_serie', 'date_acquis', 'capacite', 'ram',
            'source_acquisition', 'nom_fn', 'processeur', 'systeme', 'etat', 'statut',
        ];
    }

    public function array(): array
    {
        return [
            [
                'Ordinateur portable Direction', 'Dell', 'Latitude 5440', 'Ordinateur portable',
                'informatiques et bureautiques', 'INV-0001', '00:1A:2B:3C:4D:5E', 'SN-0001',
                '2026-01-15', 16, 16, 'Etat', 'Fournisseur X', 'Intel Core i5', 'Windows 11', 'bon', 'en stock',
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:Q1';
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFEFEFEF'],
                    ],
                    'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
                ]);
                foreach (range('A', 'Q') as $col) {
                    $event->sheet->getDelegate()->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
