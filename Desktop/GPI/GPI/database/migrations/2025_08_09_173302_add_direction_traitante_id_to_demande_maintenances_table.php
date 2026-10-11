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
        Schema::table('demande_maintenances', function (Blueprint $table) {
            $table->unsignedBigInteger('direction_traitante_id')->nullable()->after('direction_id');
            $table->foreign('direction_traitante_id')->references('id')->on('directions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('demande_maintenances', function ($table) {
        $table->dropForeign(['direction_traitante_id']);
        $table->dropColumn('direction_traitante_id');
    });
}
};
