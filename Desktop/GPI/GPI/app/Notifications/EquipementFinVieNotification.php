<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Equipement;

class EquipementFinVieNotification extends Notification
{
    use Queueable;

    public function __construct(protected Equipement $equipement) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $joursRestants = now()->diffInDays($this->equipement->date_fin_vie, false);

        return [
            'type'          => 'fin_vie_equipement',
            'message'       => "L'équipement \"{$this->equipement->des_equipement}\" approche de sa fin de durée de vie ({$joursRestants} jour(s) restant(s)).",
            'equipement_id' => $this->equipement->id,
            'designation'   => $this->equipement->des_equipement,
            'num_serie'     => $this->equipement->numero_serie,
            'date_fin_vie'  => $this->equipement->date_fin_vie->format('d/m/Y'),
            'direction_id'  => $this->equipement->direction_id,
            'link'          => route('equipement.details', $this->equipement->id),
        ];
    }
}
