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
        Schema::create('journal_modifs', function (Blueprint $table) {
            $table->id();
            $table->string('action', 255)->nullable(false);
            $table->string('description', 255)->nullable(false);
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('equipement_id')->nullable()->constrained('equipements')->onDelete('set null');
            $table->foreignId('direction_id')->constrained('directions')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_modifs');
    }
};
