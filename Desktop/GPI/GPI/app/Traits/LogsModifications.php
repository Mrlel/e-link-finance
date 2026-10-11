<?php

namespace App\Traits;

use App\Models\Journal_modif;
use App\Models\Equipement;
use App\Models\Vehicule;
use App\Models\Mobilier;
use App\Models\PatrimoineDivers;
use App\Models\Licence;
use App\Models\Poste;
use App\Models\User;

trait LogsModifications
{
    // Compat : forme historique, équipement uniquement. Les nouveaux appels multi-cibles
    // doivent passer par logAction() directement.
    protected function logModification(string $action, string $description, $equipement_id = null, $direction_id = null): void
    {
        $this->logAction($action, $description, equipementId: $equipement_id, directionId: $direction_id);
    }

    protected function logAction(
        string $action,
        string $description,
        $equipementId = null,
        $vehiculeId = null,
        $mobilierId = null,
        $patrimoineDiversId = null,
        $licenceId = null,
        $posteId = null,
        $targetUserId = null,
        $directionId = null
    ): void {
        Journal_modif::create([
            'action' => $action,
            'description' => $description,
            'equipement_id' => $this->existsOrNull($equipementId, Equipement::class),
            'vehicule_id' => $this->existsOrNull($vehiculeId, Vehicule::class),
            'mobilier_id' => $this->existsOrNull($mobilierId, Mobilier::class),
            'patrimoine_divers_id' => $this->existsOrNull($patrimoineDiversId, PatrimoineDivers::class),
            'licence_id' => $this->existsOrNull($licenceId, Licence::class),
            'poste_id' => $this->existsOrNull($posteId, Poste::class),
            'target_user_id' => $this->existsOrNull($targetUserId, User::class),
            'user_id' => auth()->id(),
            'direction_id' => $directionId ?? auth()->user()->direction_id,
        ]);
    }

    // Chaque id est vérifié contre SA propre table - jamais contre équipements par défaut,
    // pour éviter qu'un id d'un autre type (utilisateur, licence...) ne se retrouve
    // rattaché par coïncidence au mauvais enregistrement.
    private function existsOrNull($id, string $modelClass)
    {
        if (is_null($id)) {
            return null;
        }

        return $modelClass::where('id', $id)->exists() ? $id : null;
    }
}
