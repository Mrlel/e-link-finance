<?php

namespace App\Exports;

use App\Models\Equipement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class EquipementsExport implements FromCollection, WithHeadings, WithEvents
{
    protected $filters;
    protected $fields;

    public function __construct($filters, $fields)
    {
        $this->filters = $filters;
        $this->fields = $fields;
    }

    public function collection()
    {
        $query = \App\Models\Equipement::query();
        foreach ($this->filters as $key => $value) {
            if (!empty($value) && in_array($key, ['categorie', 'nature', 'etat', 'statut', 'direction_id'])) {
                $query->where($key, $value);
            }
        }
        return $query->get($this->fields);
    }

    public function headings(): array
    {
        // Traduction des entêtes
        $labels = [
            'des_equipement' => 'Désignation',
            'marque' => 'Marque',
            'modele' => 'Modèle',
            'categorie' => 'Catégorie',
            'nature' => 'Nature',
            'num_inventaire' => 'N° Inventaire',
            'adresse_mac' => 'Adresse MAC',
            'numero_serie' => 'N° Série',
            'date_acquis' => 'Date acquisition',
            'source_acquisition' => 'Source acquisition',
            'etat' => 'État',
            'processeur' => 'Processeur',
            'capacite' => 'Capacité',
            'systeme' => 'Système',
            'statut' => 'Statut',
        ];
        return array_map(fn($f) => $labels[$f] ?? ucfirst($f), $this->fields);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Style pour l'entête
                $cellRange = 'A1:' . chr(65 + count($this->fields) - 1) . '1';
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFEFEFEF'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);
                // Largeur automatique
                foreach (range(1, count($this->fields)) as $col) {
                    $event->sheet->getDelegate()->getColumnDimension(chr(64 + $col))->setAutoSize(true);
                }
            },
        ];
    }
}