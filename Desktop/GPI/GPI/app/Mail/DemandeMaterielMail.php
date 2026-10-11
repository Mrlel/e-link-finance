<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DemandeMaterielMail extends Mailable
{
    use Queueable, SerializesModels;

    public $demandeMateriel; // Variable publique pour passer les données à la vue

    public function __construct($demandeMateriel)
    {
        $this->demandeMateriel = $demandeMateriel;
    }

    public function build()
    {
        return $this->subject('Nouvelle demande de matériel') // Sujet de l'e-mail
                    ->view('emails.demande-materiel') // Vue de l'e-mail
                    ->with([
                        'demandeMateriel' => $this->demandeMateriel, // Passer les données à la vue
                    ]);
    }
}