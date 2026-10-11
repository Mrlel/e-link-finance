<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\DemandeMateriel;

class DemandeRejeteeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $demande;

    /**
     * Create a new notification instance.
     */
    public function __construct(DemandeMateriel $demande)
    {
        $this->demande = $demande;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Provide database payload explicitly.
     */
    public function toDatabase($notifiable)
    {
        return [
            'demande_id' => $this->demande->id,
            'direction_id' => $this->demande->direction_id,
            'type' => 'demande_rejetee',
            'message' => 'La demande de matériel a été rejetée',
            'commentaire' => $this->demande->commentaire,
            'typ_mat' => $this->demande->typ_mat,
            'type_materiel' => $this->demande->typ_mat,
        ];
    }
}
