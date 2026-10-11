<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contrat_garanties', function (Blueprint $table) {
            $table->id();
            $table->string('titre', 255)->nullable(false);
            $table->string('description', 255)->nullable(false);
            $table->string('reference_contrat', 255)->nullable(false);
            $table->date('date_debut')->nullable(false);
            $table->date('date_fin')->nullable(false);
            $table->foreignId('equipement_id')->nullable()->constrained('equipements')->onDelete('set null');
            $table->foreignId('source_acquisition_id')->nullable()->constrained('source_acquisitions')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contrat_garanties');
    }
};
