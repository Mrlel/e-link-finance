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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->date('date_demande')->nullable();
            $table->date('date_realisation')->nullable();
            $table->enum('statut_maintenance', ['non resolue', 'resolue'])->nullable();
            $table->enum('type_maintenance', ['preventive', 'corrective','curative','palliative'])->nullable();
            $table->foreignId('demande_maintenance_id') 
                  ->unique()
                  ->constrained('demande_maintenances')
                  ->onDelete('cascade');
            $table->foreignId('direction_id')->nullable()->constrained('directions')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
