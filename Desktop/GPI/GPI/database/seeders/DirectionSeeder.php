<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Direction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DirectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        Direction::insert([
    [
        'nom_direction' => "Direction des Systemes d'Information et de la digitalisation",
        'slug' => Str::slug("Direction des Systemes d'Information et de la digitalisation"),
        'code_direction' => 'DSID',
        'email_contact' => 'info.dsid@plan.gouv.ci',
        'telephone' => '(+225) 2720200842',
        'adresse' => 'Plateau, Immeuble SCIAM',
        'responsable' => 'Directeur HIEN TOTO JEAN PAUL',
        'statut' => 'active',
        'type' => 'centrale',
        'logo'  => $faker->imageUrl(200, 200, 'people'),
               'created_at' => now(),   // ✅ obligatoire
        'updated_at' => now(),
    ],
    [
        'nom_direction' => 'CABINET',
        'slug' => Str::slug('CABINET'),
        'code_direction' => 'CABINET',
        'email_contact' => 'info@plan.gouv.ci',
        'telephone' => '(+225) 2720200896',
        'adresse' => 'BPV 165 ABIDJAN',
        'responsable' => 'Mme NIALE KABA',
        'statut' => 'active',
        'type' => 'centrale',
        'logo'  => $faker->imageUrl(200, 200, 'people'),
               'created_at' => now(),   // ✅ obligatoire
        'updated_at' => now(),
    ],
    [
        'nom_direction' => 'Direction des Resources Humaines',
        'slug' => Str::slug('Direction des Resources Humaines'),
        'code_direction' => 'DRH',
        'email_contact' => 'info.Drh@plan.gouv.ci',
        'telephone' => '(+225) 2720200800',
        'adresse' => 'BPV 127 ABIDJAN',
        'responsable' => 'Mme Kouassi',
        'statut' => 'active',
        'type' => 'centrale',
        'logo'  => $faker->imageUrl(200, 200, 'people'),
               'created_at' => now(),   // ✅ obligatoire
        'updated_at' => now(),
    ],
]);

    }
}
