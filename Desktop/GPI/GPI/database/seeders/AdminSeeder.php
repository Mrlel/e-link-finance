<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Direction;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
    [
            'nom' => 'Mrlela',
            'prenom' => 'Gnawa Dominique',
            'email' => 'dominiklela456@gmail.com',
            'matricule' => 'LELG010620',
            'contact' => '0102323326',
            'emploie' => 'Administrateur système',
            'fonction_id' => 1,
            'grade' => 'A4',
            'role' => 'admin',
            'password' => Hash::make('12345678'),
            'direction_id' => Direction::where('code_direction', 'DSID')->first()->id,
            'created_at' => now(),   
            'updated_at' => now(),
        ],
            [
                'nom' => 'Hien',
                'prenom' => 'Toto jean paul',
                'email' => 'hien@plan.gouv.ci.com',
                'matricule' => 'HIEJ010620',
                'contact' => '05000000000',
                'emploie' => 'Informaticien',
                'fonction_id' => 1,
                'grade' => 'A3',
                'role' => 'superadmin',
                'password' => Hash::make('MEPD@2025'),
                'direction_id' => Direction::where('code_direction', 'DSID')->first()->id,
                'created_at' => now(),   // ✅ obligatoire
                'updated_at' => now(),
            ],
]);

    }
}
    

