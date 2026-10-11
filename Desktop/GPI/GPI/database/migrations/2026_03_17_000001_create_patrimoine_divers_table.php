<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patrimoine_divers', function (Blueprint $table) {
            $table->id();
            $table->string('libelle', 255);
            $table->integer('nombre')->default(0);
            $table->enum('categorie', ['fournitures', 'consommables', 'autre'])->nullable();
            $table->date('date_acquisition')->nullable();
            $table->enum('etat', ['neuf', 'bon', 'use'])->default('neuf');
            $table->enum('statut', ['en stock', 'partiellement assigné', 'épuisé'])->default('en stock');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('direction_id')->constrained('directions')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('patrimoine_divers_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patrimoine_divers_id')->constrained('patrimoine_divers')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('quantite')->default(1);
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('returned_at')->nullable();
            $table->foreignId('returned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('commentaire_retour', 255)->nullable();
            $table->foreignId('direction_id')->nullable()->constrained('directions')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patrimoine_divers_assignments');
        Schema::dropIfExists('patrimoine_divers');
    }
};
