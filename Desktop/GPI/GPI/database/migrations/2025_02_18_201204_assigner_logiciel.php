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
        Schema::create('assigner_logiciels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('equipement_id'); // Changé de user_id à equipement_id
            $table->unsignedBigInteger('licence_id');
            $table->unsignedBigInteger('logiciel_assigned_by')->nullable(); // Renommé pour plus de clarté
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('returned_at')->nullable();
            $table->timestamps();

            $table->foreign('equipement_id')->references('id')->on('equipements')->onDelete('cascade'); // Changé
            $table->foreign('licence_id')->references('id')->on('licences')->onDelete('cascade');
            $table->foreign('logiciel_assigned_by')->references('id')->on('users')->onDelete('set null');
            $table->foreignId('direction_id')->constrained('directions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assigner_logiciels');
    }
};
