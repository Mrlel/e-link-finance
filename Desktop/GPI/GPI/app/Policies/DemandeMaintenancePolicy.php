<?php

namespace App\Policies;

use App\Models\User;
use App\Models\DemandeMaintenance;

class DemandeMaintenancePolicy
{
    /**
     * Création d’une demande → uniquement par un employé simple.
     */
    public function create(User $user): bool
    {
        return $user->role === 'user';
    }

    /**
     * Technicien de la direction d’origine peut agir
     * tant que la demande n’est pas transférée.
     */
    public function actionsOrigine(User $user, DemandeMaintenance $demande): bool
    {
        return $user->role === 'technicien'
            && $user->direction_id === $demande->direction_id
            && $demande->statut_dmtc === 'en attente chef';
    }

    /**
     * Transférer la demande vers une autre direction.
     */
    public function transfer(User $user, DemandeMaintenance $demande): bool
    {
        return $this->actionsOrigine($user, $demande);
    }

    /**
     * Changer le statut de l’équipement (maintenance / service)
     * avant transfert.
     */
    public function changeStatus(User $user, DemandeMaintenance $demande): bool
    {
        return $this->actionsOrigine($user, $demande);
    }

    /**
     * Clôturer (traitée) → uniquement si technicien origine et pas encore transférée.
     */
    public function close(User $user, DemandeMaintenance $demande): bool
    {
        return $this->actionsOrigine($user, $demande);
    }

    /**
     * Admin de la direction traitante valide ou rejette.
     * → Statut attendu : "en attente dsid".
     */
    public function validate(User $user, DemandeMaintenance $demande): bool
    {
        return $user->role === 'admin'
            && $user->direction_id === $demande->direction_traitante_id
            && $demande->statut_dmtc === 'en attente dsid';
    }

    /**
     * Technicien de la direction traitante peut agir
     * UNIQUEMENT si la demande est approuvée par l’admin.
     */
    public function actionsTraitante(User $user, DemandeMaintenance $demande): bool
    {
        return $user->role === 'technicien'
            && $user->direction_id === $demande->direction_traitante_id
            && $demande->statut_dmtc === 'approuvée';
    }
}
