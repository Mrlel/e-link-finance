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
        Schema::create('source_acquisitions', function (Blueprint $table) {
            $table->id();
            $table->string('nom_source_acquisition');
            $table->string('email_source_acquisition')->unique();
            $table->string('contact_source_acquisition')->nullable();
            $table->string('type_source_acquisition')->nullable();
            $table->string('description_source_acquisition')->nullable();
            $table->foreignId('direction_id')->nullable()->constrained('directions')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('source_acquisitions');
    }
};
