<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\DemandeMateriel;

class DemandeMaterielNotification extends Notification implements ShouldQueue // Ajoutez l'interface pour une meilleure performance
{
    use Queueable;

    protected $demandeMateriel;

    public function __construct(DemandeMateriel $demandeMateriel)
    {
        // Enregistrez la demande de matériel
        $this->demandeMateriel = $demandeMateriel;
    }

    /**
     * Détermine les canaux par lesquels la notification doit être envoyée.
     */
    public function via($notifiable)
    {
        // Le canal de notification interne
        return ['database']; 
    }

    /**
     * Obtient la représentation de la notification pour le stockage dans la base de données.
     * Cette méthode doit retourner un tableau.
     */
    public function toDatabase($notifiable) // toArray est un alias de toDatabase
    {
        // Vous pouvez optimiser l'accès aux relations
        $demandeurName = $this->demandeMateriel->user->nom ?? 'Utilisateur Inconnu';
        
        // Retournez simplement le tableau des données que vous souhaitez stocker dans la colonne 'data' (JSON)
        return [
            'type' => 'nouvelle_demande_materiel',
            'demande_id' => $this->demandeMateriel->id,
            'demandeur_id' => $this->demandeMateriel->user_id,
            'direction_id_cible' => $this->demandeMateriel->direction_id, // Information importante pour les filtres
            // Alias pour compatibilité avec les filtres existants (NotificationController cherche 'data->direction_id')
            'direction_id' => $this->demandeMateriel->direction_id,

            // Clé principale utilisée par la vue (compatibilité)
            'message' => "Nouvelle Demande de Matériel de {$demandeurName}.",
            // Garde l'ancien champ si d'autres parties du code l'utilisent
            'message_court' => "Nouvelle Demande de Matériel de {$demandeurName}.",

            'type_materiel' => $this->demandeMateriel->typ_mat,
            'priorite' => $this->demandeMateriel->priorite_dem,

            // Ajoutez un lien (ajustez la route selon votre configuration)
            'link' => route('demande-materiel.show', $this->demandeMateriel->id),
        ];
    }
}