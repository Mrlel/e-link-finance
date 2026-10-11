<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mobiliers', function (Blueprint $table) {
            $table->id();
            $table->string('designation');
            $table->string('marque')->nullable();
            $table->string('reference')->nullable();
            $table->string('num_inventaire')->nullable();
            $table->date('date_acquisition')->nullable();
            $table->date('date_fin_vie')->nullable();
            $table->boolean('alerte_fin_vie_envoyee')->default(false);
            $table->enum('etat', ['neuf', 'bon', 'moyen', 'mauvais', 'hors service']);
            $table->enum('statut', ['en stock', 'affecté', 'en réforme'])->default('en stock');
            $table->enum('mode_acquisition', ['budget etat', 'don', 'autre'])->default('budget etat');
            $table->text('observations')->nullable();
            $table->foreignId('direction_id')->constrained('directions')->onDelete('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('mobilier_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mobilier_id')->constrained('mobiliers')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('returned_at')->nullable();
            $table->foreignId('returned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('motif_affectation')->nullable();
            $table->string('commentaire_retour')->nullable();
            $table->foreignId('direction_id')->nullable()->constrained('directions')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('mobilier_sorties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mobilier_id')->constrained('mobiliers')->onDelete('cascade');
            $table->foreignId('demandeur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('direction_id')->constrained('directions')->onDelete('cascade');
            $table->enum('type_sortie', ['reforme','enlevement', 'transfert', 'perte']);
            $table->string('motif');
            $table->date('date_sortie');
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mobilier_sorties');
        Schema::dropIfExists('mobilier_assignments');
        Schema::dropIfExists('mobiliers');
    }
};
