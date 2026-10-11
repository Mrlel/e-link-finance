<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicules', function (Blueprint $table) {
            $table->id();
            $table->string('immatriculation')->unique();
            $table->enum('categorie', ['auto', 'moto']);
            $table->string('marque')->nullable();
            $table->string('modele')->nullable();
            $table->string('lieu_utilisation')->nullable();
            $table->string('numero_chassis')->unique()->nullable();
            $table->string('couleur')->nullable();
            $table->enum('etat', ['NEUF', 'BON', 'MOYEN', 'MAUVAIS', 'HORS SERVICE']);
            $table->date('date_mec')->nullable();
            $table->enum('mode_acquisition', ['BUDGET ETAT', 'DON']);
            $table->enum('statut', ['disponible', 'affecté', 'en maintenance', 'hors service'])->default('disponible');
            $table->foreignId('direction_id')->constrained('directions')->onDelete('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('vehicule_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicule_id')->constrained('vehicules')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('returned_at')->nullable();
            $table->foreignId('returned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('commentaire_retour')->nullable();
            $table->foreignId('direction_id')->nullable()->constrained('directions')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicule_assignments');
        Schema::dropIfExists('vehicules');
    }
};
