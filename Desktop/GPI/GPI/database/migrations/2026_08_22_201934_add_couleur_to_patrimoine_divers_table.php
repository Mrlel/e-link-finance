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
        Schema::table('patrimoine_divers', function (Blueprint $table) {
            $table->enum('couleur', [
                'Noir', 'Cyan', 'Magenta', 'Jaune', 'Blanc', 'Rouge', 'Bleu', 'Vert', 'Gris', 'Autre',
            ])->nullable()->after('categorie');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patrimoine_divers', function (Blueprint $table) {
            $table->dropColumn('couleur');
        });
    }
};
