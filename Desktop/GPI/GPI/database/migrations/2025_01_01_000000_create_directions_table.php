<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('directions', function (Blueprint $table) {
            $table->id();
            $table->string('nom_direction')->unique();
            $table->string('slug')->unique();
            $table->string('code_direction')->nullable()->unique();
            $table->string('email_contact')->nullable();
            $table->string('telephone')->nullable();
            $table->string('logo')->nullable();
            $table->string('adresse')->nullable();
            $table->string('responsable')->nullable();
            $table->enum('statut', ['active', 'inactive'])->default('active');
            $table->string('type')->nullable(); // ex: centrale, déconcentrée, partenaire
            $table->string('web_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('directions');
    }
};
