<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\DemandeMateriel;

class DemandeMaterielApprouvee extends Notification implements ShouldQueue
{
    use Queueable;

    protected $demandeMateriel;

    public function __construct(DemandeMateriel $demandeMateriel)
    {
        $this->demandeMateriel = $demandeMateriel;
    }

    /**
     * Détermine les canaux par lesquels la notification doit être envoyée.
     */
    public function via($notifiable)
    {
        return ['database']; 
    }

    /**
     * Données stockées dans la colonne `data` (JSON).
     */
    public function toDatabase($notifiable)
{
    return [
        'message'      => "La demande de matériel #{$this->demandeMateriel->id} a été approuvée par le Directeur.",
        'priorite'     => 'Haute',
        'type_materiel'=> $this->demandeMateriel->typ_mat ?? 'Matériel',
        'demande_id'   => $this->demandeMateriel->id,
        'direction_id' => $this->demandeMateriel->direction_id,
        'direction'    => $this->demandeMateriel->direction->nom ?? 'Direction inconnue',
        'approuve_par' => auth()->user()->nom ?? 'Admin',
        'date'         => now()->toDateTimeString(),
        'link'         => route('demande-materiel.index', $this->demandeMateriel->id),
    ];
}

}
