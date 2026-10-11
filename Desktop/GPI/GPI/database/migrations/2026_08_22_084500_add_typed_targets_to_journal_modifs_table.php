<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// journal_modifs n'avait qu'une seule colonne de rattachement (equipement_id), héritée de
// l'époque où seuls les équipements informatiques existaient. Les contrôleurs Véhicule,
// Mobilier et Patrimoine Divers écrivaient donc equipement_id => null pour CHAQUE entrée,
// et certains appels (utilisateurs, licences, postes) faisaient passer un id d'un autre
// type dans ce même champ. On ajoute une colonne dédiée par type de cible, toutes en
// ON DELETE SET NULL (cohérent avec la migration précédente).
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('journal_modifs', function (Blueprint $table) {
            $table->foreignId('vehicule_id')->nullable()->after('equipement_id')
                ->constrained('vehicules')->onDelete('set null');
            $table->foreignId('mobilier_id')->nullable()->after('vehicule_id')
                ->constrained('mobiliers')->onDelete('set null');
            $table->foreignId('patrimoine_divers_id')->nullable()->after('mobilier_id')
                ->constrained('patrimoine_divers')->onDelete('set null');
            $table->foreignId('licence_id')->nullable()->after('patrimoine_divers_id')
                ->constrained('licences')->onDelete('set null');
            $table->foreignId('poste_id')->nullable()->after('licence_id')
                ->constrained('postes')->onDelete('set null');
            // Distinct de user_id (l'acteur qui a fait l'action) : la personne concernée
            // par l'action, quand l'action porte sur un utilisateur (création, suppression...).
            $table->foreignId('target_user_id')->nullable()->after('poste_id')
                ->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('journal_modifs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('vehicule_id');
            $table->dropConstrainedForeignId('mobilier_id');
            $table->dropConstrainedForeignId('patrimoine_divers_id');
            $table->dropConstrainedForeignId('licence_id');
            $table->dropConstrainedForeignId('poste_id');
            $table->dropConstrainedForeignId('target_user_id');
        });
    }
};
