<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Licence;

class LogicielAssignedNotification extends Notification
{
    use Queueable;

    protected $licence;
    protected $returnedBy;

    public function __construct($licence, $retunedBy)
    {
        $this->licence = $licence;
        $this->returnedBy = $retunedBy;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Une nouvelle licence logicielle vous a été assignée',
            'licence_id' => $this->licence->id,
            'nom_logiciel' => $this->licence->nom_logiciel,
            'version' => $this->licence->version,
            'cle_licence' => $this->licence->cle_licence,
            'returned_by' => $this->returnedBy->nom
        ];
    }
} 