<?php

namespace Database\Seeders;

use App\Models\Direction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PatrimoineDiversSeeder extends Seeder
{
    public function run(): void
    {
        $directionIds = Direction::query()->pluck('id')->all();
        $userId = User::query()->value('id');

        if ($directionIds === [] || $userId === null) {
            return;
        }

        $rows = [
            ['Ramettes A4', 120, 'fournitures', '2026-01-08', 'neuf', 'en stock'],
            ['Stylos bleus', 300, 'fournitures', '2026-01-12', 'neuf', 'partiellement assigné'],
            ['Toners imprimante HP', 18, 'consommables', '2025-12-20', 'bon', 'en stock'],
            ['Claviers USB', 25, 'autre', '2025-11-03', 'bon', 'partiellement assigné'],
            ['Souris optiques', 40, 'autre', '2025-11-03', 'bon', 'en stock'],
            ['Boites d archives', 60, 'fournitures', '2025-10-15', 'bon', 'partiellement assigné'],
            ['Cables HDMI', 14, 'autre', '2026-02-01', 'neuf', 'en stock'],
            ['Batteries onduleur', 8, 'consommables', '2024-09-09', 'use', 'partiellement assigné'],
            ['Cartouches Canon', 10, 'consommables', '2025-08-18', 'bon', 'en stock'],
            ['Pochettes kraft', 85, 'fournitures', '2026-03-05', 'neuf', 'en stock'],
            ['Rallonges electriques', 12, 'autre', '2025-07-07', 'bon', 'partiellement assigné'],
            ['Clefs USB 32Go', 22, 'autre', '2026-01-29', 'neuf', 'en stock'],
            ['Carnets de liaison', 75, 'fournitures', '2025-09-14', 'bon', 'partiellement assigné'],
            ['Ampoules LED', 50, 'consommables', '2025-06-25', 'bon', 'en stock'],
            ['Etiquettes inventaire', 0, 'fournitures', '2025-05-30', 'use', 'épuisé'],
        ];

        $payload = [];

        foreach ($rows as $index => $row) {
            $payload[] = [
                'libelle' => $row[0],
                'nombre' => $row[1],
                'categorie' => $row[2],
                'date_acquisition' => $row[3],
                'etat' => $row[4],
                'statut' => $row[5],
                'user_id' => $userId,
                'direction_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('patrimoine_divers')->insert($payload);
    }
}
