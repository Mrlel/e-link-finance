<?php

namespace App\Exports;

use App\Models\Vehicule;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\Auth;

class VehiculesExport implements FromCollection, WithHeadings, WithEvents
{
    public function collection()
    {
        return Vehicule::with('affectationActive.user')
            ->where('direction_id', Auth::user()->direction_id)
            ->get()
            ->map(fn ($v) => [
                $v->immatriculation,
                ucfirst($v->categorie),
                $v->marque ?? '—',
                $v->modele ?? '—',
                $v->couleur ?? '—',
                $v->etat,
                ucfirst($v->statut),
                ucfirst($v->mode_acquisition ?? '—'),
                $v->affectationActive?->user
                    ? $v->affectationActive->user->nom . ' ' . $v->affectationActive->user->prenom
                    : '—',
            ]);
    }

    public function headings(): array
    {
        return [
            'Immatriculation', 'Catégorie', 'Marque', 'Modèle', 'Couleur',
            'État', 'Statut', 'Mode acquisition', 'Affecté à',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $range = 'A1:I1';
                $event->sheet->getDelegate()->getStyle($range)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 11],
                    'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFD9EAD3']],
                    'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
                ]);
                foreach (range('A', 'I') as $col) {
                    $event->sheet->getDelegate()->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
