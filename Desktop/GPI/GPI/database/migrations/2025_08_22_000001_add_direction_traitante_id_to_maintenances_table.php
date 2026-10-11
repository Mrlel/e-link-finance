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
        Schema::table('maintenances', function (Blueprint $table) {
            $table->unsignedBigInteger('direction_traitante_id')->nullable();
            $table->foreign('direction_traitante_id')->references('id')->on('directions')->nullOnDelete();        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maintenances', function (Blueprint $table) {
            $table->dropForeign(['direction_traitante_id']);
            $table->dropColumn('direction_traitante_id');
        });
    }
};
