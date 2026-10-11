<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('demande_maintenance_maintenance', function (Blueprint $table) {
            $table->id();

            $table->foreignId('demande_maintenance_id')
                ->constrained('demande_maintenances')
                ->onDelete('cascade');

            $table->foreignId('maintenance_id')
                ->constrained('maintenances')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demande_maintenance_maintenance');
    }
};
