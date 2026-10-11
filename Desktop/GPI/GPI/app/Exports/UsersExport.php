<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class UsersExport implements FromCollection, WithHeadings, WithEvents
{
    protected $users;

    public function __construct($users)
    {
        // Expecting a Collection or array of user models/arrays
        $this->users = $users;
    }

    public function collection()
    {
        $rows = [];
        $collection = $this->users instanceof Collection ? $this->users : collect($this->users);
        foreach ($collection as $u) {
            // if model, access properties; if array, use keys
         $rows[] = [
    data_get($u, 'nom'),
    data_get($u, 'prenom'),
    data_get($u, 'matricule'),
    data_get($u, 'email'),
    data_get($u, 'contact'),
    data_get($u, 'emploie'),
    data_get($u, 'fonction.fonction', ''), // ✅ fonction liée
    data_get($u, 'grade'),
    data_get($u, 'role'),
];

        }

        return collect($rows);
    }

    public function headings(): array
    {
        // "Emploie" (et non "Emploi") : doit slugifier vers la même clé que App\Imports\UserImport
        // attend ('emploie', qui correspond au nom réel de la colonne en base), sinon un fichier
        // réexporté puis réimporté tel quel perd cette colonne silencieusement.
        return [
            'Nom', 'Prénom', 'Matricule', 'Email', 'Contact', 'Emploie', 'Fonction', 'Grade', 'Role'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
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
