<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE equipements MODIFY COLUMN statut ENUM('en stock','en service','en maintenance','enlevement','reformer') NOT NULL DEFAULT 'en stock'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE equipements MODIFY COLUMN statut ENUM('en stock','en service','en maintenance') NOT NULL DEFAULT 'en stock'");
    }
};
