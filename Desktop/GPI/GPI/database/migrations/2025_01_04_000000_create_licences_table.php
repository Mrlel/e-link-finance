<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLicencesTable extends Migration
{
    public function up()
    {
        Schema::create('licences', function (Blueprint $table) {
            $table->id();
            $table->string('designation_licence');
            $table->string('type_licence')->nullable();
            $table->date('date_expiration')->nullable();
            $table->string('cle_licence')->nullable();
            $table->string('environnement')->nullable();
            $table->string('langage_version')->nullable();
            $table->string('sgbd_version')->nullable();
            $table->string('version')->nullable();
            $table->string('base_donnees')->nullable();
            $table->string('fichier_licence')->nullable();
            $table->string('libelle_licence')->nullable(); // Correction du nom de colonne
            $table->foreignId('equipement_id')->nullable()
                  ->constrained('equipements')
                  ->onDelete('cascade');
            $table->foreignId('direction_id')->constrained('directions')->onDelete('cascade');
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('licences');
    }
}
