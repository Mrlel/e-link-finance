<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    
    return new class extends Migration {
        public function up(): void
        {
            Schema::create('demande_materiels', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade'); // L'utilisateur qui fait la demande
                $table->string('priorite_dem')->default('moyenne');
                $table->text('desc_demande')->nullable(); 
                $table->enum('typ_mat', ['Ordinateur portable','Ordinateur All-in-one','unite centrale','outillage technique','Imprimante','ecran','clavier','souris','Scanner', 'Serveur', 'Routeur', 'Switch', 'Onduleur', 'Projecteur', 'Téléphone IP','pare-feu', 'photocopieuse','stockage','systeme visio conference', 'Accessoire electrique', 'Accessoire reseau', 'Accessoire securite', 'Autre'])->nullable(); 
                $table->enum('statut_dem', [
                    'en attente chef',
                    'en attente GestParc',   
                    'en attente admin',           
                    'rejetée', 
                    'approuvée',
                    'traitée', 
                    'annulée'            
                ])->default('en attente chef'); 
                $table->boolean('demande_autorisation')->default(false);
                $table->text('commentaire')->nullable();
                $table->foreignId('direction_id')->constrained('directions')->onDelete('cascade');
                $table->timestamps();
            });
        }
    
        public function down(): void
        {
            Schema::dropIfExists('demande_materiels');
        }
    };
