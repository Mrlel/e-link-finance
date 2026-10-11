<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Durée de vie et statut étendu sur les équipements
        Schema::table('equipements', function (Blueprint $table) {
            $table->date('date_fin_vie')->nullable()->after('date_acquis');
            $table->boolean('alerte_fin_vie_envoyee')->default(false)->after('date_fin_vie');
            // Nouveau statut pour les équipements enlevés du stock
            // On étend l'enum existant
        });

        // Table de traçabilité des sorties (enlèvement + maintenance externe)
        Schema::create('sorties_equipement', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipement_id')->constrained('equipements')->onDelete('cascade');
            $table->foreignId('demandeur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('valideur_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('direction_id')->constrained('directions')->onDelete('cascade');

            // Type de sortie
            $table->enum('type_sortie', [
                'maintenance_externe',   // envoi en maintenance hors direction
                'reforme',               // réforme
                'enlevement',               // enlèvement
                'transfert',             // transfert vers autre direction
            ]);

            $table->string('motif', 500);
            $table->string('prestataire')->nullable();          
            $table->string('lieu_destination')->nullable();     
            $table->date('date_sortie');
            $table->date('date_retour_prevue')->nullable();     
            $table->date('date_retour_effective')->nullable();
            $table->foreignId('retour_valide_par')->nullable()->constrained('users')->onDelete('set null');

            $table->enum('statut', ['en_cours', 'retourne', 'definitif'])->default('en_cours');
            $table->text('observations')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sorties_equipement');
        Schema::table('equipements', function (Blueprint $table) {
            $table->dropColumn(['date_fin_vie', 'alerte_fin_vie_envoyee']);
        });
    }
};
