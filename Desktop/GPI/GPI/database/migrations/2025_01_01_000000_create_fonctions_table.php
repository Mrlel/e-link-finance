<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fonctions', function (Blueprint $table) {
            $table->id();
            $table->string('fonction', 100);
            $table->foreignId('direction_id')->constrained('directions')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fonctions');
    }
};
