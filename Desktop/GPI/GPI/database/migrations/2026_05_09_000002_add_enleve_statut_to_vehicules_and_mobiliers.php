<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ajouter 'enlevé' à l'enum statut des véhicules
        DB::statement("ALTER TABLE vehicules MODIFY COLUMN statut ENUM('disponible','affecté','en maintenance','hors service','enlevé') NOT NULL DEFAULT 'disponible'");

        // Ajouter 'enlevé' à l'enum statut des mobiliers
        DB::statement("ALTER TABLE mobiliers MODIFY COLUMN statut ENUM('en stock','affecté','en réforme','enlevé') NOT NULL DEFAULT 'en stock'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE vehicules MODIFY COLUMN statut ENUM('disponible','affecté','en maintenance','hors service') NOT NULL DEFAULT 'disponible'");
        DB::statement("ALTER TABLE mobiliers MODIFY COLUMN statut ENUM('en stock','affecté','en réforme') NOT NULL DEFAULT 'en stock'");
    }
};
