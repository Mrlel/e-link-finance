<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->text('commentaire_retour')->nullable();
            $table->string('etat_retour')->nullable();
            $table->unsignedBigInteger('returned_by')->nullable();
            $table->foreign('returned_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropForeign(['returned_by']);
            $table->dropColumn(['commentaire_retour', 'etat_retour', 'returned_by']);
        });
    }
}; 