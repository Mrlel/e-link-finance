<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipementsTable extends Migration
{
  public function up()
{
    Schema::create('equipements', function (Blueprint $table) {
        $table->id(); 
        $table->string('des_equipement', 255)->nullable(false);
        $table->string('marque', 255)->nullable(); 
        $table->string('modele', 255)->nullable(); 
        $table->enum('categorie', ['Ordinateur portable','Ordinateur All-in-one','unite centrale','outillage technique','Imprimante','ecran','clavier','souris','Scanner', 'Serveur', 'Routeur', 'Switch', 'Onduleur', 'Projecteur', 'Téléphone IP','pare-feu', 'photocopieuse','stockage','systeme visio conference', 'Accessoire electrique', 'Accessoire reseau', 'Accessoire securite', 'Autre'])->nullable();
        $table->enum('nature', ['accesoires informatiques', 'reseaux', 'informatiques et bureautiques', 'multimedia', 'telephonie et connectivite', 'autre']);
        $table->string('num_inventaire', 255)->nullable();
        $table->string('adresse_mac', 255)->nullable();
        $table->string('numero_serie', 255)->nullable(false); 
        $table->date('date_acquis')->nullable(); 
        $table->integer('capacite')->nullable(); 
        $table->integer('ram')->nullable();
        $table->enum('source_acquisition', ['Etat','Bailleur','autre'])->nullable();
        $table->string('nom_fn', 255)->nullable();
        $table->string('processeur', 255)->nullable();
        $table->string('systeme', 255)->nullable();
        $table->foreignId('poste_id')->nullable()->constrained('postes')->onDelete('set null');
        $table->enum('etat', ['bon', 'moyen','hors service']);
        $table->enum('statut', ['en stock', 'en service', 'en maintenance'])->default('en stock');
        $table->string('qr_code', 255)->nullable();
        $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); 
        $table->foreignId('direction_id')->constrained('directions')->onDelete('cascade');
        $table->timestamps();
    });
}

    public function down()
    {
        Schema::dropIfExists('equipements');
    }
}
