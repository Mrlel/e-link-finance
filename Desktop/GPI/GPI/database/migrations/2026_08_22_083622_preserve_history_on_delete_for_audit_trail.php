<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Le trait commun aux tables d'historique (affectations, sorties, journal d'audit) était
// ON DELETE CASCADE sur leurs clés étrangères : supprimer un utilisateur, un équipement,
// un véhicule, un mobilier, un article de patrimoine divers, une licence ou une direction
// effaçait silencieusement et définitivement tout l'historique qui le référençait - à
// l'opposé de ce qu'un audit exige (un enregistrement passé doit survivre à la suppression
// de l'entité qu'il concernait). On bascule ces FK en SET NULL : la ligne d'historique
// survit toujours, seul le lien vivant vers l'entité supprimée disparaît.
return new class extends Migration
{
    /**
     * Table => [ [colonne, table_référencée], ... ]
     */
    private function targets(): array
    {
        return [
            'assignments' => [
                ['user_id', 'users'],
                ['equipement_id', 'equipements'],
            ],
            'vehicule_assignments' => [
                ['vehicule_id', 'vehicules'],
                ['user_id', 'users'],
                ['direction_id', 'directions'],
            ],
            'mobilier_assignments' => [
                ['mobilier_id', 'mobiliers'],
                ['user_id', 'users'],
                ['direction_id', 'directions'],
            ],
            'patrimoine_divers_assignments' => [
                ['patrimoine_divers_id', 'patrimoine_divers'],
                ['user_id', 'users'],
                ['direction_id', 'directions'],
            ],
            'mobilier_sorties' => [
                ['mobilier_id', 'mobiliers'],
                ['demandeur_id', 'users'],
                ['direction_id', 'directions'],
            ],
            'sorties_equipement' => [
                ['equipement_id', 'equipements'],
                ['demandeur_id', 'users'],
                ['direction_id', 'directions'],
            ],
            'sorties_vehicule' => [
                ['vehicule_id', 'vehicules'],
                ['demandeur_id', 'users'],
                ['direction_id', 'directions'],
            ],
            'assigner_logiciels' => [
                ['equipement_id', 'equipements'],
                ['licence_id', 'licences'],
                ['direction_id', 'directions'],
            ],
            'journal_modifs' => [
                ['direction_id', 'directions'],
            ],
        ];
    }

    public function up(): void
    {
        foreach ($this->targets() as $table => $columns) {
            foreach ($columns as [$column, $refTable]) {
                $constraint = "{$table}_{$column}_foreign";
                DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$constraint}`");
                DB::statement("ALTER TABLE `{$table}` MODIFY `{$column}` BIGINT UNSIGNED NULL");
                DB::statement("ALTER TABLE `{$table}` ADD CONSTRAINT `{$constraint}` FOREIGN KEY (`{$column}`) REFERENCES `{$refTable}` (`id`) ON DELETE SET NULL");
            }
        }
    }

    public function down(): void
    {
        // Retour à CASCADE, dans le même ordre - restaure le comportement d'origine.
        // NB: si des lignes ont déjà NULL sur une colonne rendue NOT NULL à l'origine,
        // ce rollback échouera : c'est volontaire, il ne doit pas s'exécuter en silence
        // sur une base où l'historique a déjà été préservé par des suppressions ultérieures.
        foreach ($this->targets() as $table => $columns) {
            foreach ($columns as [$column, $refTable]) {
                $constraint = "{$table}_{$column}_foreign";
                DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$constraint}`");
                DB::statement("ALTER TABLE `{$table}` MODIFY `{$column}` BIGINT UNSIGNED NOT NULL");
                DB::statement("ALTER TABLE `{$table}` ADD CONSTRAINT `{$constraint}` FOREIGN KEY (`{$column}`) REFERENCES `{$refTable}` (`id`) ON DELETE CASCADE");
            }
        }
    }
};
