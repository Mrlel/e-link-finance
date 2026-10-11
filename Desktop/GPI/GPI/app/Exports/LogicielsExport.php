<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class LogicielsExport implements FromCollection, WithHeadings, WithEvents
{
    protected $logiciels;

    public function __construct($logiciels)
    {
        $this->logiciels = $logiciels;
    }

    /**
     * Transformation des données pour l'export
     */
    public function collection()
    {
        $collection = $this->logiciels instanceof Collection ? $this->logiciels : collect($this->logiciels);

        return $collection->map(function ($u) {
            return [
                data_get($u, 'designation_licence'),
                data_get($u, 'type_licence'),
                data_get($u, 'cle_licence'),
                data_get($u, 'environnement'),
                data_get($u, 'langage_version'),
                data_get($u, 'date_expiration') ? \Carbon\Carbon::parse(data_get($u, 'date_expiration'))->format('d/m/Y') : 'N/A',
            ];
        });
    }

    /**
     * En-têtes conformes aux données logicielles
     */
    public function headings(): array
    {
        return [
            'Désignation',
            'Type de Licence',
            'Clé / Licence',
            'Environnement',
            'Langage / Version',
            'Date d\'expiration'
        ];
    }

    /**
     * Style et mise en forme
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Définition de la plage (A à F car il y a 6 colonnes désormais)
                $cellRange = 'A1:F1';
                $sheet = $event->sheet->getDelegate();

                // Style de l'en-tête
                $sheet->getStyle($cellRange)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'color' => ['argb' => 'FFFFFFFF'], // Texte blanc
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF28A745'], // Vert "Success" Bootstrap
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                    ],
                ]);

                // Auto-taille pour les colonnes A à F
                foreach (range('A', 'F') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Bordures pour tout le tableau de données
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle('A1:F' . $highestRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            },
        ];
    }
}