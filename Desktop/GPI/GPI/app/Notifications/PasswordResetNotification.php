<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordResetNotification extends Notification
{
    use Queueable;

    public function __construct(protected string $newPassword) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Réinitialisation de votre mot de passe - GPI')
            ->greeting('Bonjour ' . trim($notifiable->prenom . ' ' . $notifiable->nom) . ',')
            ->line('Le mot de passe de votre compte GPI vient d\'être réinitialisé par un administrateur.')
            ->line('Votre nouveau mot de passe temporaire est :')
            ->line('**' . $this->newPassword . '**')
            ->line('Pour des raisons de sécurité, il vous sera demandé de le modifier dès votre prochaine connexion.')
            ->line('Si vous n\'êtes pas à l\'origine de cette demande, contactez immédiatement votre administrateur.');
    }
}
