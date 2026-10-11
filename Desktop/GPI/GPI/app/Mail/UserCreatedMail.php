<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;

    // Le constructeur reçoit les variables et les stocke dans la classe
    public function __construct($user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject("Votre compte a été créé")
                    ->view('emails.user_created')
                    ->with([
                        'user' => $this->user,  // Passer les variables à la vue
                        'password' => $this->password,
                    ]);
    }
}
