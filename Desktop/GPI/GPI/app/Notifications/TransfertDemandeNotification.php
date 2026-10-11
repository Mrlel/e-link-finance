<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class TransfertDemandeNotification extends Notification
{
    use Queueable;

    protected $demande;
    protected $data;

    public function __construct($demande, $data = [])
    {
        $this->demande= $demande;
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $user = $this->demande->user;

        $baseData = [
            'demande_id' => $this->demande->id,
            'user_id' => $this->demande->user_id,
            'nom_demandeur' => $this->demande->user->nom ?? 'Utilisateur inconnu',
            'contact_demandeur' => $this->demande->user->contact ?? '',
            'email_demandeur' => $this->demande->user->email ?? '',
            'description_problème' => $this->demande->desc_prblem,
            'priorite' => $this->demande->priorite_dmtc,
            'direction_id' => $this->demande->direction_id,
        ];

        // Données spécifiques selon le type de notification
        return array_merge($baseData, [
            'message' => 'Une demande de maintenance vous a été transférée',
            'ancienne_direction' => $this->data['ancienne_direction'] ?? 'Inconnue',
            'motif' => $this->data['motif'] ?? null,
            'type' => 'demande_transferee',
        ]);
    }
}
