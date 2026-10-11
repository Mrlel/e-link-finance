<?php
namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\DemandeMaintenance;
use App\Models\Equipement;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Traits\LogsModifications;

class MaintenanceController extends Controller
{
    use LogsModifications;

    public function store(Request $request)
{
    \Log::info('Début de la création de maintenance', $request->all());
    $user = auth()->user();

    try {
        $validated = $request->validate([
            'description' => 'required|string',
            'date_realisation' => 'required|string',
            'statut_maintenance' => 'required|string|in:non resolue,resolue',
            'type_maintenance' => 'required|string|in:preventive,corrective,curative,palliative',
            'demande_maintenance_id' => 'required|exists:demande_maintenances,id' 
        ]);

        $demande = DemandeMaintenance::findOrFail($validated['demande_maintenance_id']);

        // Vérifie s’il y a déjà une maintenance liée à cette demande
        $existing = Maintenance::where('demande_maintenance_id', $demande->id)->first();
        if ($existing) {
            return redirect()->back()->with('error', 'Une maintenance existe déjà pour cette demande.');
        }

        $equipement = Equipement::All();
        if (!$equipement) {
            return redirect()->back()->with('error', 'Aucun équipement trouvé pour ce numéro de série.');
        }

        $maintenance = Maintenance::create([
            'description' => $validated['description'],
            'date_demande' => $demande->created_at,
            'type_maintenance' => $validated['type_maintenance'],
            'statut_maintenance' => $validated['statut_maintenance'],
            'date_realisation' => $validated['date_realisation'],
            'demande_maintenance_id' => $demande->id,
            'direction_traitante_id' => $user->direction->id,
            'direction_id' => $demande->user->direction_id,
        ]);

        \Log::info('Maintenance créée avec succès', ['id' => $maintenance->id]);

        $this->logModification(
            'création',
            "Création d'une maintenance ({$validated['type_maintenance']}) pour la demande #{$demande->id}",
            $demande->equipement_id
        );

        return redirect()->route('demande_maintenances.index')
            ->with('success', 'Maintenance créée avec succès !');

    } catch (\Exception $e) {
        \Log::error('Erreur lors de la création de la maintenance', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->back()
            ->withInput()
            ->with('error', 'Erreur : ' . $e->getMessage());
    }
}


public function listMaintenance()
{
    $user = auth()->user();

    $maintenances = Maintenance::with(['user', 'equipement', 'direction', 'demandeMaintenance'])
        ->where('direction_id', $user->direction_id)
        ->orWhereHas('demandeMaintenance', function($q) use ($user) {
            $q->where('direction_traitante_id', $user->direction_id);
        })
        ->get();

    return view('Admin.maintenance.tableau-maintenance', compact('maintenances'));
}

    public function show($id)
    {
        $user = auth()->user();

        $maintenance = Maintenance::with(['demandeMaintenance.user', 'demandeMaintenance.equipement', 'direction'])
            ->where(function ($q) use ($user) {
                $q->where('direction_id', $user->direction_id)
                  ->orWhere('direction_traitante_id', $user->direction_id);
            })
            ->findOrFail($id);

        return view('Admin.maintenance.show', compact('maintenance'));
    }
}