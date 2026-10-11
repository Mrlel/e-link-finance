<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Equipement;

class EquipementAssignedNotification extends Notification
{
    use Queueable;

    protected $equipement;
    protected $assignedBy;

    public function __construct($equipement, $assignedBy)
    {
        $this->equipement = $equipement;
        $this->assignedBy = $assignedBy;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Un nouvel équipement vous a été assigné',
            'equipement_id' => $this->equipement->id,
            'designation' => $this->equipement->des_equipement,
            'direction_id' => $this->equipement->direction_id,
            'categorie' => $this->equipement->categorie,
            'marque' => $this->equipement->marque,
            'modele' => $this->equipement->modele,
            'numero_serie' => $this->equipement->numero_serie,
            'assigned_by' => $this->assignedBy->nom
        ];
    }
} 