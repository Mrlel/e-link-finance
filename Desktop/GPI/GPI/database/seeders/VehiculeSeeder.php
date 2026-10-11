<?php

namespace Database\Seeders;

use App\Models\Direction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehiculeSeeder extends Seeder
{
    public function run(): void
    {
        $directionIds = Direction::query()->pluck('id')->all();
        $createdBy = User::query()->value('id');

        if ($directionIds === [] || $createdBy === null) {
            return;
        }

        $rows = [
            ['AB-101-AA', 'auto', 'Toyota', 'Corolla', 'Abidjan', 'CHASSIS-VEH-001', 'Blanc', 'BON', '2022-01-15', 'BUDGET ETAT', 'disponible'],
            ['AB-102-AA', 'auto', 'Hyundai', 'Tucson', 'Yamoussoukro', 'CHASSIS-VEH-002', 'Gris', 'NEUF', '2024-03-10', 'BUDGET ETAT', 'affecté'],
            ['AB-103-AA', 'moto', 'Yamaha', 'NMAX', 'Bouake', 'CHASSIS-VEH-003', 'Noir', 'BON', '2023-06-20', 'DON', 'disponible'],
            ['AB-104-AA', 'auto', 'Nissan', 'Navara', 'San Pedro', 'CHASSIS-VEH-004', 'Bleu', 'MOYEN', '2019-11-05', 'BUDGET ETAT', 'en maintenance'],
            ['AB-105-AA', 'auto', 'Kia', 'Sportage', 'Korhogo', 'CHASSIS-VEH-005', 'Rouge', 'BON', '2021-07-12', 'DON', 'disponible'],
            ['AB-106-AA', 'moto', 'Honda', 'CBR500R', 'Daloa', 'CHASSIS-VEH-006', 'Rouge', 'NEUF', '2024-08-01', 'BUDGET ETAT', 'affecté'],
            ['AB-107-AA', 'auto', 'Mitsubishi', 'Pajero', 'Man', 'CHASSIS-VEH-007', 'Vert', 'MAUVAIS', '2016-09-18', 'BUDGET ETAT', 'hors service'],
            ['AB-108-AA', 'auto', 'Ford', 'Ranger', 'Gagnoa', 'CHASSIS-VEH-008', 'Noir', 'BON', '2020-05-22', 'DON', 'disponible'],
            ['AB-109-AA', 'moto', 'Suzuki', 'V-Strom', 'Odienne', 'CHASSIS-VEH-009', 'Jaune', 'MOYEN', '2018-12-30', 'BUDGET ETAT', 'en maintenance'],
            ['AB-110-AA', 'auto', 'Peugeot', '3008', 'Bondoukou', 'CHASSIS-VEH-010', 'Blanc', 'BON', '2021-10-09', 'BUDGET ETAT', 'affecté'],
            ['AB-111-AA', 'auto', 'Renault', 'Duster', 'Abengourou', 'CHASSIS-VEH-011', 'Marron', 'MOYEN', '2017-04-14', 'DON', 'disponible'],
            ['AB-112-AA', 'moto', 'Bajaj', 'Pulsar', 'Abidjan', 'CHASSIS-VEH-012', 'Bleu', 'BON', '2022-02-28', 'BUDGET ETAT', 'disponible'],
            ['AB-113-AA', 'auto', 'Isuzu', 'D-Max', 'Soubre', 'CHASSIS-VEH-013', 'Gris', 'HORS SERVICE', '2015-08-07', 'BUDGET ETAT', 'hors service'],
            ['AB-114-AA', 'auto', 'Mazda', 'CX-5', 'Aboisso', 'CHASSIS-VEH-014', 'Blanc', 'NEUF', '2025-01-21', 'DON', 'disponible'],
            ['AB-115-AA', 'moto', 'TVS', 'Apache', 'Ferkessedougou', 'CHASSIS-VEH-015', 'Orange', 'BON', '2023-09-11', 'BUDGET ETAT', 'affecté'],
        ];

        $payload = [];

        foreach ($rows as $index => $row) {
            $payload[] = [
                'immatriculation' => $row[0],
                'categorie' => $row[1],
                'marque' => $row[2],
                'modele' => $row[3],
                'lieu_utilisation' => $row[4],
                'numero_chassis' => $row[5],
                'couleur' => $row[6],
                'etat' => $row[7],
                'date_mec' => $row[8],
                'mode_acquisition' => $row[9],
                'statut' => $row[10],
                'direction_id' => 1,
                'created_by' => $createdBy,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('vehicules')->insert($payload);
    }
}
