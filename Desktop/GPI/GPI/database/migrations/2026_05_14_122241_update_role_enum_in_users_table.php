<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE users 
            MODIFY COLUMN role ENUM(
                'user',
                'chef_de_service',
                'sous_directeur',
                'gestionnaire_parc',
                'admin',
                'technicien',
                'superadmin',
                'role_x',
                'directeur',
                'ministre',
                'point_focal',
                'service_gestionnaire'
            ) NOT NULL DEFAULT 'user'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE users 
            MODIFY COLUMN role ENUM(
                'user',
                'chef_de_service',
                'sous_directeur',
                'gestionnaire_parc',
                'admin',
                'technicien',
                'superadmin'
            ) NOT NULL DEFAULT 'user'
        ");
    }
};