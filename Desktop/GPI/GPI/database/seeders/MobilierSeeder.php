<?php

namespace Database\Seeders;

use App\Models\Direction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MobilierSeeder extends Seeder
{
    public function run(): void
    {
        $directionIds = Direction::query()->pluck('id')->all();
        $createdBy = User::query()->value('id');

        if ($directionIds === [] || $createdBy === null) {
            return;
        }

        $rows = [
            ['Bureau en bois', 'Ikea', 'BUR-001', 'INV-MOB-001', '2021-01-10', '2031-01-10', false, 'bon', 'en stock', 'budget etat', 'Bureau administratif standard'],
            ['Chaise ergonomique', 'Herman Miller', 'CHR-002', 'INV-MOB-002', '2023-04-18', '2030-04-18', false, 'neuf', 'affecté', 'don', 'Siege pour poste de travail'],
            ['Armoire metallique', 'Godrej', 'ARM-003', 'INV-MOB-003', '2019-07-01', '2029-07-01', false, 'moyen', 'en stock', 'budget etat', 'Armoire a dossiers'],
            ['Table de reunion', 'Steelcase', 'TAB-004', 'INV-MOB-004', '2020-09-14', '2030-09-14', false, 'bon', 'en stock', 'autre', 'Salle de conference'],
            ['Caisson mobile', 'Kinnarps', 'CSN-005', 'INV-MOB-005', '2022-02-11', '2030-02-11', false, 'bon', 'affecté', 'budget etat', 'Rangement sous bureau'],
            ['Etagere archive', 'Atlas', 'ETA-006', 'INV-MOB-006', '2018-11-21', '2028-11-21', true, 'mauvais', 'en reforme', 'don', 'Usure avancee'],
            ['Fauteuil visiteur', 'MobiPlus', 'FVT-007', 'INV-MOB-007', '2024-01-08', '2032-01-08', false, 'neuf', 'en stock', 'budget etat', 'Mobilier accueil'],
            ['Meuble TV', 'Conforama', 'MTV-008', 'INV-MOB-008', '2021-06-05', '2029-06-05', false, 'bon', 'en stock', 'autre', 'Salle multimedia'],
            ['Banquette accueil', 'Sokoa', 'BNQ-009', 'INV-MOB-009', '2017-05-17', '2027-05-17', true, 'mauvais', 'en reforme', 'budget etat', 'Revêtement fatigue'],
            ['Classeur vertical', 'Fellowes', 'CLS-010', 'INV-MOB-010', '2020-12-03', '2028-12-03', false, 'moyen', 'affecté', 'don', 'Utilise au service courrier'],
            ['Bureau de direction', 'Majencia', 'BDR-011', 'INV-MOB-011', '2022-08-24', '2034-08-24', false, 'neuf', 'affecté', 'budget etat', 'Mobilier direction generale'],
            ['Table basse', 'Mobexpert', 'TBS-012', 'INV-MOB-012', '2019-03-27', '2027-03-27', true, 'moyen', 'en stock', 'autre', 'Salon visiteurs'],
            ['Chaise pliante', 'Lafuma', 'CHP-013', 'INV-MOB-013', '2023-10-15', '2031-10-15', false, 'bon', 'en stock', 'budget etat', 'Evenements internes'],
            ['Rayonnage', 'Manutan', 'RAY-014', 'INV-MOB-014', '2018-01-19', '2026-01-19', true, 'hors service', 'en reforme', 'don', 'Structure deformee'],
            ['Comptoir accueil', 'OfficePro', 'CPA-015', 'INV-MOB-015', '2024-05-02', '2034-05-02', false, 'neuf', 'affecté', 'budget etat', 'Nouveau hall reception'],
        ];

        $payload = [];

        foreach ($rows as $index => $row) {
            $payload[] = [
                'designation' => $row[0],
                'marque' => $row[1],
                'reference' => $row[2],
                'num_inventaire' => $row[3],
                'date_acquisition' => $row[4],
                'date_fin_vie' => $row[5],
                'alerte_fin_vie_envoyee' => $row[6],
                'etat' => $row[7],
                'statut' => $row[8],
                'mode_acquisition' => $row[9],
                'observations' => $row[10],
                'direction_id' => 1,
                'created_by' => $createdBy,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('mobiliers')->insert($payload);
    }
}
