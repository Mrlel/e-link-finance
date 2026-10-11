<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Assignment; // S'assurer que le modèle est utilisé

class EquipementReturnedNotification extends Notification
{
    use Queueable;

    protected $assignment;

    /**
     * @param Assignment $assignment Le modèle d'attribution retourné.
     */
    public function __construct(Assignment $assignment)
    {
        $this->assignment = $assignment;
        // Assurez-vous que l'équipement est chargé si vous utilisez toDatabase/toArray plus tard
        $this->assignment->load('equipement'); 
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Correction: Remplacement de $this->equipement par $this->assignment->equipement.
     */
    public function toArray($notifiable)
    {
        // Utilisation de toArray pour la convention moderne et de l'objet equipement via la relation
        $equipement = $this->assignment->equipement;
        
        $message = "L'équipement **{$equipement->des_equipement}** a été retourné et retiré de votre inventaire.";
        
        return [
            'type' => 'equipement_returned',
            'message' => $message,
            'equipement_id' => $equipement->id,
            'equipement_nom' => $equipement->des_equipement, // Remplacement de 'nom' par 'des_equipement' (basé sur le log)
            'date_retour' => $this->assignment->returned_at,
            
            // CORRECTION DE L'ERREUR : Accès à direction_id via la relation equipement
            'direction_id' => $equipement->direction_id, 
            
            // L'utilisateur qui a validé le retour est 'returnedBy'
            'returned_by' => optional($this->assignment->returnedBy)->nom ?? 'Système/Auto-déclaré',
            'commentaire' => $this->assignment->commentaire_retour,
            'etat_retour' => $this->assignment->etat_retour,
        ];
    }
}