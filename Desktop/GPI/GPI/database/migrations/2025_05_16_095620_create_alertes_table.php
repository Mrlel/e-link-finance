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
        Schema::create('alertes', function (Blueprint $table) {
            $table->id();
            $table->enum('type_alerte', ['Garantie expirée', 'Fin de vie', 'Maintenance'])->nullable(false);
            $table->date('date_prevue')->nullable(false);
            $table->boolean('vu_alerte')->default(false);
            $table->foreignId('equipement_id')->nullable()->constrained('equipements')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alertes');
    }
};
