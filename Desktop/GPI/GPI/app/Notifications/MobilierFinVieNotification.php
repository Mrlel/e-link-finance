<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Mobilier;

class MobilierFinVieNotification extends Notification
{
    use Queueable;

    public function __construct(protected Mobilier $mobilier) {}

    public function via($notifiable): array { return ['database']; }

    public function toDatabase($notifiable): array
    {
        $jours = now()->diffInDays($this->mobilier->date_fin_vie, false);
        return [
            'type'        => 'fin_vie_mobilier',
            'message'     => "Le mobilier \"{$this->mobilier->designation}\" approche de sa fin de durée de vie ({$jours} jour(s) restant(s)).",
            'mobilier_id' => $this->mobilier->id,
            'designation' => $this->mobilier->designation,
            'date_fin_vie'=> $this->mobilier->date_fin_vie->format('d/m/Y'),
            'direction_id'=> $this->mobilier->direction_id,
            'link'        => route('mobiliers.show', $this->mobilier->id),
        ];
    }
}
