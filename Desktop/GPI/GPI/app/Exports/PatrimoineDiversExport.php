<?php

namespace App\Exports;

use App\Models\PatrimoineDivers;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\Auth;

class PatrimoineDiversExport implements FromCollection, WithHeadings, WithEvents
{
    public function collection()
    {
        return PatrimoineDivers::with('utilisateursActuels')
            ->where('direction_id', Auth::user()->direction_id)
            ->get()
            ->map(fn ($item) => [
                $item->libelle,
                ucfirst($item->categorie ?? '—'),
                $item->couleur ?? '—',
                $item->nombre,
                ucfirst($item->etat),
                ucfirst($item->statut),
                $item->date_acquisition ?? '—',
                $item->utilisateursActuels->isEmpty()
                    ? '—'
                    : $item->utilisateursActuels->map(fn ($u) => "{$u->nom} {$u->prenom} (x{$u->pivot->quantite})")->implode(', '),
            ]);
    }

    public function headings(): array
    {
        return [
            'Libellé', 'Catégorie', 'Couleur', 'Quantité en stock', 'État', 'Statut',
            'Date acquisition', 'Assigné à',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $range = 'A1:H1';
                $event->sheet->getDelegate()->getStyle($range)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 11],
                    'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFD9EAD3']],
                    'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
                ]);
                foreach (range('A', 'H') as $col) {
                    $event->sheet->getDelegate()->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
