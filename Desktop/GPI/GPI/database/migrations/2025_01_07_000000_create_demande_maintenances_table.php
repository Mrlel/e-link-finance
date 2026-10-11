<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
{
    Schema::create('demande_maintenances', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade'); 
    $table->enum('statut_dmtc', [
            'en attente admin',
            'en attente chef',
            'en attente dsid',             
            'rejetée', 
            'approuvée',
            'traitée', 
            'annulée'            
    ])->default('en attente chef'); 
    $table->text('desc_prblem');
    $table->enum('priorite_dmtc', ['faible', 'moyen', 'urgent'])->nullable();
    $table->foreignId('direction_id')->constrained('directions')->onDelete('cascade');
    $table->foreignId('equipement_id')->nullable()->constrained('equipements')->onDelete('cascade');
    $table->timestamps();
});

}
    public function down(): void
    {
        Schema::dropIfExists('demande_maintenances');
    }
};
