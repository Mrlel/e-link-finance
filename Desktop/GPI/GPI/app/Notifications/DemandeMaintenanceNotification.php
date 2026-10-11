<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class DemandeMaintenanceNotification extends Notification
{
    use Queueable;

    protected $demandeMaintenance;
    protected $data;

    public function __construct($demandeMaintenance, $data = [])
    {
        $this->demandeMaintenance = $demandeMaintenance;
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $user = $this->demandeMaintenance->user;

        $baseData = [
            'demande_id' => $this->demandeMaintenance->id,
            'user_id' => $this->demandeMaintenance->user_id,
            'nom_demandeur' => $this->demandeMaintenance->user->nom ?? 'Utilisateur inconnu',
            'contact_demandeur' => $this->demandeMaintenance->user->contact ?? '',
            'email_demandeur' => $this->demandeMaintenance->user->email ?? '',
            'description_problème' => $this->demandeMaintenance->desc_prblem,
            'priorite' => $this->demandeMaintenance->priorite_dmtc,
            'direction_id' => $this->demandeMaintenance->direction_id,
            'link'         => route('demande_maintenances.index', $this->demandeMaintenance->id),
        ];

        // Données spécifiques selon le type de notification
        if (isset($this->data['type']) && $this->data['type'] === 'demande_transferee') {
            return array_merge($baseData, [
                'message' => 'Une demande de maintenance vous a été transférée',
                'ancienne_direction' => $this->data['ancienne_direction'] ?? 'Inconnue',
                'motif' => $this->data['motif'] ?? null,
                'type' => 'demande_transferee',
            ]);
        }

        // Notification par défaut pour nouvelle demande
        return array_merge($baseData, [
            'message' => 'Une nouvelle demande de maintenance a été soumise.',
            'type' => 'demande_maintenance',
        ]);
    }
}
