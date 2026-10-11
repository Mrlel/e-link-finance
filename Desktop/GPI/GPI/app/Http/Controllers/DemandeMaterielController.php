<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DemandeMateriel;
use App\Models\Equipement;
use App\Models\Assignment;
use App\Mail\DemandeMaterielMail;
use App\Notifications\DemandeMaterielNotification;
use App\Notifications\DemandeMaterielApprouvee;
use App\Notifications\DemandeRejeteeNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Traits\LogsModifications;


class DemandeMaterielController extends Controller
{
    use LogsModifications;
    
    public function show_demande_materiel_form(){
        return view('Users.demandeMateriel');
    }

public function saveDemandeMateriel(Request $request) {
    try {
        $user = auth()->user();
        
        // Validation et vérification de la direction
        if (!$user->direction_id) {
            return redirect()->back()->with('error', 'Votre compte n\'est associé à aucune direction.');
        }

        $request->validate([
            'typ_mat' => 'required',
            'priorite_dem' => 'required',
            'desc_demande' => 'required',
        ]); 

        // Vérification de la disponibilité du matériel (laissez votre logique actuelle)
        $categorieExiste = Equipement::where('statut', 'en stock')
                                     ->where('categorie', $request->typ_mat)
                                     ->exists();

        if (!$categorieExiste) {
            return redirect()->back()->with('error', 'L\'équipement demandé est indisponible veuillez demander un autre.');
        }

        // Création de la demande
        $demandeMateriel = DemandeMateriel::create([
            'user_id' => $user->id,
            'direction_id' => $user->direction_id,
            'typ_mat' => $request->typ_mat,
            'priorite_dem' => $request->priorite_dem,
            'desc_demande' => $request->desc_demande,
            'statut_dem' => 'en attente chef',
        ]);

        $this->logAction(
            'création',
            "Création d'une demande de matériel ({$request->typ_mat}) par {$user->nom} {$user->prenom}",
            targetUserId: $user->id
        );

        // --- Logique de Notification Améliorée ---
        $chefs = User::where('direction_id', $user->direction_id)
            ->where('role', 'chef_de_service') 
            ->get(); 
        
        foreach ($chefs as $chef) {
            $chef->notify(new DemandeMaterielNotification($demandeMateriel));
        }

        if ($chefs->isEmpty()) {
            // Optionnel : Logguer s'il n'y a pas de chef pour la direction
            \Log::warning("Aucun chef de service trouvé pour la direction_id : " . $user->direction_id);
            // Vous pourriez aussi notifier un administrateur ici si c'est critique
        }

        return redirect()->back()->with('success', 'Demande envoyée avec succès et notifiée au chef de service.');
    } catch (\Exception $e) {
        // Il est important de logger l'erreur complète pour le débogage
        \Log::error('Erreur lors de l\'enregistrement de la demande de materiel : ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
        return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'enregistrement de la demande.');
    }
}

   public function index()
{
    $user = auth()->user();
    $direction_id = $user->direction_id ?? null;
    
    $demandeApprouvées = DemandeMateriel::where('direction_id', $direction_id)->where('statut_dem', 'traitée')->count();
    $demandeEnAttente = DemandeMateriel::where('direction_id', $direction_id)->whereIn('statut_dem', ['en attente admin', 'en attente GestParc', 'en attente chef'])->count();
    $demandeRefusées = DemandeMateriel::where('direction_id', $direction_id)->where('statut_dem', 'refusée')->count();;
    

    if ($user->role == 'admin') {
       
        $demandeMateriels = DemandeMateriel::where('direction_id', $direction_id)->whereIn('statut_dem', ['en attente admin', 'approuvée', 'rejetée','traitée'])->get();
        
    }
    elseif($user->role == 'gestionnaire_parc') {
        $demandeMateriels = DemandeMateriel::where('direction_id', $direction_id)->whereIn('statut_dem', ['en attente GestParc', 'approuvée', 'rejetée','en attente admin','traitée'])->get();

    } elseif (in_array($user->role, ['chef_de_service', 'sous_directeur', 'directeur', 'ministre', 'point_focal', 'service_gestionnaire'])) {
        $demandeMateriels = DemandeMateriel::where('direction_id', $direction_id)->get();
    }
    else {
        // Employé standard voit seulement SES demandes
        $demandeMateriels = DemandeMateriel::where('direction_id', $direction_id)->where('user_id', $user->id)->get();
    }

    // Données communes
    $equipements = Equipement::where('direction_id', $direction_id)->get();
    $users = User::where('direction_id', $direction_id)->get();

    return view('admin.demande-materiel.index', compact(
        'demandeMateriels',
        'demandeApprouvées',
        'demandeEnAttente',
        'demandeRefusées',
        'equipements',
        'users',
        'user'
    ));
}

    public function showDetail($id)
    {
        $demandeMateriel = DemandeMateriel::where('direction_id', auth()->user()->direction_id)->findOrFail($id);
        $user = User::findOrFail($demandeMateriel->user_id);
        $assignments = Assignment::where('direction_id', $user->direction_id)->get();
        $equipements = Equipement::where('direction_id', $user->direction_id)->where('statut', 'en stock')->get();
        return view('admin.demande-materiel.show', compact('demandeMateriel', 'user','equipements'));
    }

    public function cancelDemandeMateriel($id)
    {
        $demande = DemandeMateriel::where('direction_id', auth()->user()->direction_id)->findOrFail($id);

        $this->logAction(
            'annulation',
            "Annulation d'une demande de matériel ({$demande->typ_mat})",
            targetUserId: $demande->user_id
        );
        $demande->delete();

        return redirect()->back()->with('success', 'Demande de matériel annulée avec succès.');
    }

    public function updateStatus(Request $request, $id)
    {
        $demande = DemandeMateriel::where('direction_id', auth()->user()->direction_id)->findOrFail($id);
        $demande->statut_dem = $request->input('statut_dem');
        $demande->save();

        $this->logAction(
            'modification',
            "Modification du statut de la demande de matériel vers « {$request->input('statut_dem')} »",
            targetUserId: $demande->user_id
        );

        return redirect()->route('demande-materiel.index')->with('success', 'Statut mis à jour avec succès.');
    }

    /**
     * Met à jour l'autorisation de la demande
     */
    
    public function approuverParChef($id) {
        $demandeMateriel = DemandeMateriel::findOrFail($id);
        
        // Vérifier que l'utilisateur est bien chef de service de la direction concernée
        if ((auth()->user()->role !== 'chef_de_service' && auth()->user()->role !== 'sous_directeur') ||
            auth()->user()->direction_id !== $demandeMateriel->direction_id) {
            abort(404);
        }
      
        $demandeMateriel->update([
            'statut_dem' => 'en attente GestParc',
            'approuve_par_chef' => true,
            'date_approbation_chef' => now(),
        ]);

        $this->logAction(
            'approuver',
            "Approbation (chef de service) de la demande de matériel ({$demandeMateriel->typ_mat})",
            targetUserId: $demandeMateriel->user_id
        );

        // Envoyer la notification au gestionnaire du parc de la direction
        $GP = User::where('direction_id', $demandeMateriel->direction_id)
        ->where('role', 'gestionnaire_parc')
        ->first();

        if ($GP) {
            $GP->notify(new DemandeMaterielNotification($demandeMateriel));
        }
        
        return back()->with('success', 'Demande approuvée et envoyée au Gestionnaire du parc.');
    }

    public function approuverParGestParc($id) {
        $demandeMateriel = DemandeMateriel::findOrFail($id);
        
        // Vérifier que l'utilisateur est bien chef de service de la direction concernée
        if (auth()->user()->role !== 'gestionnaire_parc' && auth()->user()->role !== 'sous_directeur' || 
            auth()->user()->direction_id !== $demandeMateriel->direction_id) {
                return redirect()->back()->with('error', 'Vous n\'etes pas autorisé à envoyer cette demande au Directeur');
        }
      
        $demandeMateriel->update([
            'statut_dem' => 'en attente admin',
            'approuve_par_gestparc' => true,
            'date_approbation_gestparc' => now(),
        ]);

        $this->logAction(
            'approuver',
            "Approbation (gestionnaire de parc) de la demande de matériel ({$demandeMateriel->typ_mat})",
            targetUserId: $demandeMateriel->user_id
        );

        // Envoyer la notification au Directeur de la direction

        // Notifier tous les administrateurs (globaux) ainsi que les admins de la direction
        $admins = User::where('role', 'admin')->get();

        if ($admins->isEmpty()) {
            // Log pour aider le débogage si aucun admin n'est configuré
            Log::warning('Aucun administrateur trouvé lors de l\'approbation par gestionnaire de parc pour demande_id: ' . $demandeMateriel->id);
        } else {
            foreach ($admins as $admin) {
                $admin->notify(new DemandeMaterielNotification($demandeMateriel));
            }
        }

        return back()->with('success', 'Demande approuvée et envoyée aux administrateurs.');
    }

    public function approuverParAdmin($id) {
        $demandeMateriel = DemandeMateriel::findOrFail($id);

        // Vérifier que l'utilisateur est bien admin de la direction concernée
        if (auth()->user()->role !== 'admin' || 
            auth()->user()->direction_id !== $demandeMateriel->direction_id) {
            abort(404);
        }

        $demandeMateriel->update([
            'statut_dem' => 'approuvée',
            'approuve_par_admin' => true,
            'date_approbation_admin' => now(),
        ]);

        $this->logAction(
            'approuver',
            "Approbation finale (admin) de la demande de matériel ({$demandeMateriel->typ_mat})",
            targetUserId: $demandeMateriel->user_id
        );

        // Notifier le demandeur et le gestionnaire du parc (si présent)
        $demandeur = $demandeMateriel->user;
        $gestParc = User::where('direction_id', $demandeMateriel->direction_id)
            ->whereIn('role', ['gestionnaire_parc', 'chef_de_service'])
            ->first();

        if ($demandeur) {
            $demandeur->notify(new DemandeMaterielApprouvee($demandeMateriel));
        }

        if ($gestParc) {
            $gestParc->notify(new DemandeMaterielApprouvee($demandeMateriel));
        }

        return back()->with('success', 'Demande approuvée et notifiée aux destinataires.');
    }



    public function rejeterParAdmin(Request $request, $id)
{
    // Validation : commentaire requis si rejet
    $request->validate([
        'commentaire' => 'nullable|string|max:1000',
    ]);

    // Récupérer la demande
    $demandeMateriel = DemandeMateriel::findOrFail($id);
    
    // Vérification du rôle et de la direction
    if (
        auth()->user()->role !== 'admin' ||
        auth()->user()->direction_id !== $demandeMateriel->direction_id
    ) {
        abort(403, 'Accès non autorisé.');
    }

    // Mise à jour de la demande
    $demandeMateriel->update([
        'statut_dem' => 'rejetée',
        'commentaire' => $request->input('commentaire'),
    ]);

    $this->logAction(
        'rejet',
        "Rejet (admin) de la demande de matériel ({$demandeMateriel->typ_mat})" . ($request->input('commentaire') ? " - motif : {$request->input('commentaire')}" : ''),
        targetUserId: $demandeMateriel->user_id
    );

      $demandeur = $demandeMateriel->user;
        $gestParc = User::where('direction_id', $demandeMateriel->direction_id)
            ->whereIn('role', ['gestionnaire_parc', 'chef_de_service'])
            ->first();

         if ($demandeur) {
            $demandeur->notify(new \App\Notifications\DemandeRejeteeNotification($demandeMateriel));
        }

        if ($gestParc) {
            $gestParc->notify(new \App\Notifications\DemandeRejeteeNotification($demandeMateriel));
        }

    return back()->with('success', 'La demande a été rejetée et le demandeur a été notifié.');
}

}
