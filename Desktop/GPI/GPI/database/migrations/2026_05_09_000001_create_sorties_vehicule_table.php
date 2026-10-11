<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sorties_vehicule', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicule_id')->constrained('vehicules')->onDelete('cascade');
            $table->foreignId('demandeur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('valideur_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('direction_id')->constrained('directions')->onDelete('cascade');
            $table->enum('type_sortie', ['maintenance_externe', 'reforme', 'enlevement', 'transfert']);
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
        Schema::dropIfExists('sorties_vehicule');
    }
};
