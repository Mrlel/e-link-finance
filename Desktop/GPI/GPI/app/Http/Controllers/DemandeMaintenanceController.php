<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\DemandeMaintenance;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Equipement;
use App\Models\Direction;
use App\Models\Licence;
use Illuminate\Pagination\Paginator;
use App\Notifications\DemandeMaintenanceNotification;
use App\Notifications\TransfertDemandeNotification;
use Illuminate\Support\Facades\Log;
use App\Traits\LogsModifications;


class DemandeMaintenanceController extends Controller
{
    use LogsModifications;

    /**
     * Une demande de maintenance n'est visible/modifiable que par la direction d'origine
     * ou la direction actuellement chargée de son traitement (cf. transfererDemande / index()).
     */
    private function findDemandeForActor($id)
    {
        $directionId = auth()->user()->direction_id;

        return DemandeMaintenance::where('id', $id)
            ->where(function ($q) use ($directionId) {
                $q->where('direction_id', $directionId)
                  ->orWhere('direction_traitante_id', $directionId);
            })
            ->firstOrFail();
    }

    public function update_en_maintenance(Request $request, $id)
    {
        $demande = $this->findDemandeForActor($id);
        $equipement = $demande->equipement;
        if ($equipement) {
            $equipement->statut = 'en maintenance';
            $equipement->save();
        }
        return redirect()->route('demande_maintenances.index')->with('success', 'Équipement mis en maintenance avec succès.');
    }

    public function update_en_service(Request $request, $id)
    {
        $demande = $this->findDemandeForActor($id);
        $equipement = $demande->equipement;
        if ($equipement) {
            $equipement->statut = 'en service';
            $equipement->save();
        }
        return redirect()->route('demande_maintenances.index')->with('success', 'Équipement remis en service avec succès.');
    }
     public function update_traitee(Request $request, $id)
    {
        $demande = $this->findDemandeForActor($id);
        $demande->statut_dmtc = 'traitée';
        $demande->save();

        return redirect()->route('demande_maintenances.index')->with('success', 'Statut mis à jour avec succès.');
    }

    public function showChoice()
    {
        return view('Users.demandeMaintenance');
    }

    public function showFormDemande()
    {
        return view('Users.demandeMaintenance');
    }

    public function saveDemande(Request $request)
    {
        $user = auth()->user();
        // Validation des champs
        $validator = Validator::make($request->all(), [
            'desc_prblem' => 'required|string',
            'priorite_dmtc' => 'required|string',
            'statut_dmtc' => 'nullable|string',
            
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $demandes = new DemandeMaintenance();
        $demandes->user_id = Auth::id(); 
        $demandes->desc_prblem = $request->desc_prblem;
        $demandes->priorite_dmtc = $request->priorite_dmtc;
        $demandes->equipement_id = $request->equipement_id;
        $demandes->direction_id = auth()->user()->direction_id ?? null;
        $demandes->statut_dmtc = $request->statut_dmtc ?? 'en attente chef';
        $demandes->save();

        $chefs = User::where('direction_id', $user->direction_id)
            ->whereIn('role', ['chef_de_service', 'technicien'])
            ->get();
        
        foreach ($chefs as $chef) {
            $chef->notify(new DemandeMaintenanceNotification($demandes));
        }

        if ($chefs->isEmpty()) {
            // Optionnel : Logguer s'il n'y a pas de chef pour la direction
            \Log::warning("Aucun chef de service trouvé pour la direction_id : " . $user->direction_id);
            // Vous pourriez aussi notifier un administrateur ici si c'est critique
        }


        $this->logModification(
            'création',
            "Création d'une demande de maintenance",
            $demandes->equipement_id
        );

        return redirect()->back()->with('status', 'Demande envoyée avec succès !');
    }

        public function transfererDemande(Request $request, $id)
            {
                $request->validate([
                    'direction_id' => 'required|exists:directions,id',
                    'motif' => 'nullable|string|max:255',
                ]);

                $demande = $this->findDemandeForActor($id);
                $nouvelleDirection = Direction::find($request->direction_id);
                if (!$nouvelleDirection) {
                    return redirect()->back()->with('error', 'La direction sélectionnée n\'existe pas.');
                }

                // Sauvegarder l'ancienne direction traitante pour l'historique
                $ancienneDirectionTraitanteId = $demande->direction_traitante_id;

                // Mettre à jour la direction traitante de la demande vers la direction choisie
                $demande->update([
                    'direction_traitante_id' => $nouvelleDirection->id,
                    'statut_dmtc' => $nouvelleDirection->code_direction === 'DSID' ? 'en attente dsid' : 'en attente chef',
                ]);

                // Enregistrer l'historique du transfert
                $this->logModification(
                    'transfert',
                    "Demande transférée pour traitement de la direction ID: $ancienneDirectionTraitanteId vers la direction ID: {$nouvelleDirection->id}" . ($request->motif ? " (motif : {$request->motif})" : ''),
                    $demande->equipement_id
                );

                // Envoyer une notification au chef de service de la nouvelle direction
                $chefDeService = User::where('direction_id', $nouvelleDirection->id)->whereIn('role', ['chef_de_service', 'technicien'])->first();
                if ($chefDeService) {
                    $chefDeService->notify(new TransfertDemandeNotification($demande, [
                        'ancienne_direction' => Direction::find($ancienneDirectionTraitanteId)->nom ?? 'Inconnue',
                        'motif' => $request->motif,
                    ]));
                }

                return redirect()->route('demande_maintenances.index')->with('success', 'La demande a été transférée avec succès vers la nouvelle direction pour traitement.');
            }
            public function index()
            {
                $user = auth()->user();
                $direction_id = $user->direction_id ?? null;
                
                if (in_array($user->role, ['technicien', 'admin', 'sous_directeur', 'gestionnaire_parc', 'chef_de_service', 'directeur', 'ministre', 'point_focal', 'service_gestionnaire'])) {
                    $demandes = DemandeMaintenance::where('direction_id', $direction_id)
                        ->orWhere('direction_traitante_id', $direction_id)
                        ->get();
                }
                
                else {
                    $demandes = DemandeMaintenance::where('direction_id', $direction_id)
                        ->where('user_id', $user->id)
                        ->get();
                }
            
                // Calcul des statistiques
                $demandesApprouvées = $demandes->where('statut_dmtc', 'approuvée','traitée')->count();
                $demandesEnAttente = $demandes->where('statut_dmtc', 'en attente chef','en attente dsid','en attente technicien')
                ->count();
                $demandesRejetee = $demandes->where('statut_dmtc', 'rejetée')->count();
            
                // Données communes
                $equipements = Equipement::where('direction_id', $direction_id)->get();
                $users = User::where('direction_id', $direction_id)->get();
            
                return view('demande_maintenances.index', compact(
                    'demandes', 
                    'demandesApprouvées', 
                    'demandesEnAttente', 
                    'demandesRejetee',
                    'equipements',
                    'users'
                ));
            }


            public function showUpdate_FormDemande($id){
                $demandes = DemandeMaintenance::find($id);
                return view('Users.update_serviceMaintenance',compact('demandes'));
            }
            
            public function detail($id)
            {
                $demandeMaintenance = $this->findDemandeForActor($id);

                return view('admin.demande-maintenance.detail', compact('demandeMaintenance'));
            }

    public function show($id)
    {
        $demande = $this->findDemandeForActor($id);
        $equipements = Equipement::where('direction_id', auth()->user()->direction_id)->get();

        return view('demande_maintenances.show', compact('demande', 'equipements'));
    }

    public function updateStatus(Request $request, $id)
    {
        $demande = $this->findDemandeForActor($id);
        $demande->statut_dmtc = $request->input('statut_dmtc');
        $demande->save();

        $this->logModification(
            'modification',
            "Modification du statut de la demande de maintenance",
            $demande->equipement_id
        );

        return redirect()->route('demande_maintenances.index')->with('success', 'Statut mis à jour avec succès.');
    }

    public function cancelDemandeMaintenance($id)
    {
        $demande = $this->findDemandeForActor($id);

        $this->logModification(
            'annulation',
            "Annulation de la demande de maintenance",
            $demande->equipement_id
        );
        $demande->delete();

        return redirect()->back()->with('success', 'Demande de maintenance annulée avec succès.');
    }


    public function approuverParAdmin($id) {
        // Vérifier que l'utilisateur est bien admin de la direction concernée
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $demande = $this->findDemandeForActor($id);
    
        $demande->update([
            'statut_dmtc' => 'approuvee',
            'approuve_par_admin' => true,
            'date_approbation_admin' => now(),
        ]);
    
        $this->logModification(
            'approuver',
            "Approbation de la demande de maintenance par l'administrateur",
            $demande->equipement_id
        );
        /*
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $admin->notify(new DemandeApprouveeNotification($demande));
        }
    */    
        return back()->with('success', 'Demande approuvée');
    }

    public function rejeter($id) {
        // Vérifier que l'utilisateur est bien admin de la direction concernée
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $demande = $this->findDemandeForActor($id);
    
        $demande->update([
            'statut_dmtc' => 'rejetée',
            'rejet_par_admin' => true,
            'date_rejet_admin' => now(),
        ]);
    
        $this->logModification(
            'rejet',
            "Rejet de la demande de maintenance par l'administrateur",
            $demande->equipement_id
        );
        /*
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $admin->notify(new DemandeApprouveeNotification($demande));
        }
    */    
        return back()->with('success', 'Demande rejetée et envoyée au chef de service.');
    }
}
