<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Licence;
use App\Models\User;
use App\Models\AssignerLogiciel;
use App\Models\Equipement;
use Carbon\Carbon;  
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Notifications\LogicielAssignedNotification;
use Illuminate\Support\Facades\Log;
use App\Traits\LogsModifications;

class AssignerLogicielController extends Controller
{
    use LogsModifications;
    public function index()
    {
        $attributions = AssignerLogiciel::where('direction_id', auth()->user()->direction_id)
            ->with(['equipement', 'licence', 'assignedBy']) // ✅ Corrigé
            ->get();
        $equipements = Equipement::where('direction_id', auth()->user()->direction_id)->get();       
        $licences = Licence::where('direction_id', auth()->user()->direction_id)->get();
        $users = User::where('direction_id', auth()->user()->direction_id)->get();
        
        Log::info('Attributions:', $attributions->toArray());
        
        return view('Admin.list_historique_logiciel_assigner', compact('attributions', 'equipements', 'users', 'licences'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'equipement_id' => 'required|exists:equipements,id',
            'licence_id' => 'required|exists:licences,id',
        ]);

        try {
            // Vérifier si l'équipement a déjà ce logiciel assigné
            $existingAssignment = AssignerLogiciel::where('equipement_id', $request->equipement_id)
                ->where('licence_id', $request->licence_id)
                ->whereNull('returned_at')
                ->first();

            if ($existingAssignment) {
                return redirect()->back()
                    ->with('error', 'Ce logiciel est déjà assigné à cet équipement.')
                    ->withInput();
            }

            $licence = Licence::find($request->licence_id);
            if (!$licence) {
                return response()->json(['error' => 'Licence non trouvée'], 404);
            }

            $equipement = Equipement::find($request->equipement_id);
            if (!$equipement) {
                return response()->json(['error' => 'Équipement non trouvé'], 404);
            }

            $attribution = AssignerLogiciel::create([
                'equipement_id' => $request->equipement_id,
                'licence_id' => $request->licence_id,
                'logiciel_assigned_by' => auth()->id(),
                'direction_id' => auth()->user()->direction_id,
                'assigned_at' => now(),
            ]);
            $this->logAction(
                'attribution',
                "Attribution du logiciel {$licence->nom_licence} à l'équipement {$equipement->des_equipement}",
                equipementId: $equipement->id,
                licenceId: $licence->id,
                directionId: $equipement->direction_id
            );
            Log::info('Nouvelle attribution créée:', $attribution->toArray());

            return redirect()->back()
                ->with('success', 'Logiciel assigné avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'attribution:', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de l\'attribution du logiciel.')
                ->withInput();
        }
    }

    public function retirer($id)
    {
        try {
            $assignerLogiciel = AssignerLogiciel::findOrFail($id);
            
            // Mettre à jour la date de retour au lieu de supprimer
            $assignerLogiciel->update([
                'returned_at' => now(),
            ]);
            $this->logAction(
                'retirer',
                "Retirer du logiciel {$assignerLogiciel->licence->nom_licence} de l'équipement {$assignerLogiciel->equipement->des_equipement}",
                equipementId: $assignerLogiciel->equipement->id,
                licenceId: $assignerLogiciel->licence->id,
                directionId: $assignerLogiciel->equipement->direction_id
            );
            Log::info('Logiciel retiré:', $assignerLogiciel->toArray());

            return redirect()->route('list_historique_logiciel_assigner')
                ->with('success', 'Logiciel retiré avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors du retrait:', ['error' => $e->getMessage()]);
            
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors du retrait du logiciel.');
        }
    }

    public function historiqueRetraitsLicence()
    {
        // ✅ Corrigé : Récupérer les logiciels retournés
        $historiqueRetraits = AssignerLogiciel::where('direction_id', auth()->user()->direction_id)
            ->whereNotNull('returned_at')
            ->with(['equipement', 'licence', 'assignedBy'])
            ->orderBy('returned_at', 'desc')
            ->get();
            
        return view('Admin.histoLicence', compact('historiqueRetraits'));
    }

    public function show($id)
    {
        $assignerLogiciel = AssignerLogiciel::with(['equipement', 'licence', 'assignedBy'])
            ->findOrFail($id);
        return view('assigner_logiciels.show', compact('assignerLogiciel'));
    }

    public function edit($id)
    {
        $assignerLogiciel = AssignerLogiciel::findOrFail($id);
        $equipements = Equipement::where('direction_id', auth()->user()->direction_id)->get();
        $licences = Licence::where('direction_id', auth()->user()->direction_id)->get();
        
        // ✅ Retourner une vue d'édition spécifique
        return view('Admin.edit_logiciel_assignation', compact('assignerLogiciel', 'equipements', 'licences'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'equipement_id' => 'required|exists:equipements,id',
            'licence_id' => 'required|exists:licences,id',
        ]);

        try {
            $assignerLogiciel = AssignerLogiciel::findOrFail($id);
            
            $assignerLogiciel->update([
                'equipement_id' => $request->equipement_id,
                'licence_id' => $request->licence_id,
            ]);

            return redirect()->route('list_historique_logiciel_assigner')
                ->with('success', 'Attribution mise à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour:', ['error' => $e->getMessage()]);
            
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la mise à jour.')
                ->withInput();
        }
    }
}