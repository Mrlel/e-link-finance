<?php

namespace App\Exports;

use App\Models\Mobilier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\Auth;

class MobiliersExport implements FromCollection, WithHeadings, WithEvents
{
    public function collection()
    {
        return Mobilier::with('affectationActive.user')
            ->where('direction_id', Auth::user()->direction_id)
            ->get()
            ->map(fn($m) => [
                $m->num_inventaire ?? '—',
                $m->designation,
                $m->marque ?? '—',
                $m->reference ?? '—',
                $m->date_acquisition?->format('d/m/Y') ?? '—',
                $m->date_fin_vie?->format('d/m/Y') ?? '—',
                ucfirst($m->etat),
                ucfirst($m->statut),
                ucfirst($m->mode_acquisition),
                $m->affectationActive?->user
                    ? $m->affectationActive->user->nom . ' ' . $m->affectationActive->user->prenom
                    : '—',
            ]);
    }

    public function headings(): array
    {
        return [
            // Pas de colonne Catégorie : ce champ n'existe pas sur le modèle Mobilier
            // (voir le fix dans conversation - Mobilier::$categories n'a jamais existé).
            'N° Inventaire', 'Désignation', 'Marque', 'Référence',
            'Date acquisition', 'Fin de vie', 'État', 'Statut',
            'Mode acquisition', 'Affecté à',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $range = 'A1:J1';
                $event->sheet->getDelegate()->getStyle($range)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 11],
                    'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFD9EAD3']],
                    'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
                ]);
                foreach (range('A', 'J') as $col) {
                    $event->sheet->getDelegate()->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
