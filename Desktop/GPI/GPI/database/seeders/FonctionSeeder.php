<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Direction;
use App\Models\Fonction;
use Illuminate\Database\Seeder;

class FonctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Fonction::insert([
            [
                'fonction' => 'informaticien',
                'direction_id' => Direction::where('code_direction', 'DSID')->first()->id,
                'created_at' => now(),   
                'updated_at' => now(),
            ]
        ]);
    }
}
