<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Vehicule;
use App\Models\VehiculeAssignment;
use App\Models\User;
use App\Exports\VehiculesExport;
use App\Traits\LogsModifications;

class VehiculeController extends Controller
{
    use LogsModifications;

    // ─── Constantes ──────────────────────────────────────────────────────────

    const ETATS            = ['NEUF', 'BON', 'MOYEN', 'MAUVAIS', 'HORS SERVICE'];
    const CATEGORIES       = ['auto', 'moto'];
    const MODES_ACQUISITION = ['BUDGET ETAT', 'DON'];
    const STATUTS          = ['disponible', 'affecté', 'en maintenance', 'hors service'];

    // ─── Index ────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = Vehicule::with('affectationActive.user')
            ->where('direction_id', Auth::user()->direction_id);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('immatriculation', 'like', "%$s%")
                  ->orWhere('marque', 'like', "%$s%")
                  ->orWhere('modele', 'like', "%$s%");
            });
        }
        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }
        if ($request->filled('etat')) {
            $query->where('etat', $request->etat);
        }
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('mode_acquisition')) {
            $query->where('mode_acquisition', $request->mode_acquisition);
        }

        // Exclure les enlevés par défaut
        if (!$request->boolean('afficher_enleves')) {
            $query->where('statut', '!=', 'enlevé');
        }

        $vehicules = $query->orderBy('created_at', 'desc')->get();
        $users     = User::where('direction_id', Auth::user()->direction_id)->get();

        return view('Patrimoine.vehicules.index', compact('vehicules', 'users'));
    }

    // ─── Export ──────────────────────────────────────────────────────────────

    public function exportExcel()
    {
        return Excel::download(new VehiculesExport(), 'vehicules_' . now()->format('Ymd') . '.xlsx');
    }

    public function exportPdf()
    {
        $vehicules = Vehicule::with('affectationActive.user')
            ->where('direction_id', Auth::user()->direction_id)
            ->get();

        $pdf = Pdf::loadView('Patrimoine.vehicules.pdf', compact('vehicules'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('vehicules_' . now()->format('Ymd') . '.pdf');
    }

    // ─── Create / Store ───────────────────────────────────────────────────────

    public function create()
    {
        return view('Patrimoine.vehicules.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'immatriculation' => 'required|string|max:50|unique:vehicules,immatriculation',
            'categorie'       => 'required|in:auto,moto',
            'marque'          => 'nullable|string|max:100',
            'modele'          => 'nullable|string|max:100',
            'lieu_utilisation'=> 'nullable|string|max:255',
            'numero_chassis'  => 'nullable|string|max:100|unique:vehicules,numero_chassis',
            'couleur'         => 'nullable|string|max:50',
            'etat'            => 'required|in:NEUF,BON,MOYEN,MAUVAIS,HORS SERVICE',
            'date_mec'        => 'nullable|date',
            'mode_acquisition'=> 'required|in:BUDGET ETAT,DON',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $vehicule = Vehicule::create([
            'immatriculation' => strtoupper($request->immatriculation),
            'categorie'       => $request->categorie,
            'marque'          => $request->marque,
            'modele'          => $request->modele,
            'lieu_utilisation'=> $request->lieu_utilisation,
            'numero_chassis'  => $request->numero_chassis,
            'couleur'         => $request->couleur,
            'etat'            => $request->etat,
            'date_mec'        => $request->date_mec,
            'mode_acquisition'=> $request->mode_acquisition,
            'statut'          => 'disponible',
            'direction_id'    => Auth::user()->direction_id,
            'created_by'      => Auth::id(),
        ]);

        $this->logVehicule('ajout', "Ajout du véhicule {$request->immatriculation}", $vehicule->id);

        return redirect()->route('vehicules.index')->with('status', 'Véhicule ajouté avec succès.');
    }

    // ─── Show ─────────────────────────────────────────────────────────────────

    public function show($id)
    {
        $vehicule = Vehicule::with(
            'assignments.user', 'assignments.assignedBy', 'assignments.returnedBy',
            'sorties.demandeur', 'sortieActive'
        )
            ->where('direction_id', Auth::user()->direction_id)
            ->findOrFail($id);

        return view('Patrimoine.vehicules.show', compact('vehicule'));
    }

    // ─── Edit / Update ────────────────────────────────────────────────────────

    public function edit($id)
    {
        $vehicule = Vehicule::where('direction_id', Auth::user()->direction_id)->findOrFail($id);
        return view('Patrimoine.vehicules.edit', compact('vehicule'));
    }

    public function update(Request $request, $id)
    {
        $vehicule = Vehicule::where('direction_id', Auth::user()->direction_id)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'immatriculation' => ['required', 'string', 'max:50', Rule::unique('vehicules', 'immatriculation')->ignore($id)],
            'categorie'       => 'required|in:auto,moto',
            'marque'          => 'nullable|string|max:100',
            'modele'          => 'nullable|string|max:100',
            'lieu_utilisation'=> 'nullable|string|max:255',
            'numero_chassis'  => ['nullable', 'string', 'max:100', Rule::unique('vehicules', 'numero_chassis')->ignore($id)],
            'couleur'         => 'nullable|string|max:50',
            'etat'            => 'required|in:NEUF,BON,MOYEN,MAUVAIS,HORS SERVICE',
            'date_mec'        => 'nullable|date',
            'mode_acquisition'=> 'required|in:BUDGET ETAT,DON',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $vehicule->update([
            'immatriculation' => strtoupper($request->immatriculation),
            'categorie'       => $request->categorie,
            'marque'          => $request->marque,
            'modele'          => $request->modele,
            'lieu_utilisation'=> $request->lieu_utilisation,
            'numero_chassis'  => $request->numero_chassis,
            'couleur'         => $request->couleur,
            'etat'            => $request->etat,
            'date_mec'        => $request->date_mec,
            'mode_acquisition'=> $request->mode_acquisition,
        ]);

        $this->logVehicule('modification', "Modification du véhicule {$vehicule->immatriculation}", $vehicule->id);

        return redirect()->route('vehicules.index')->with('status', 'Véhicule modifié avec succès.');
    }

    // ─── Destroy ──────────────────────────────────────────────────────────────

    public function destroy($id)
    {
        $vehicule = Vehicule::where('direction_id', Auth::user()->direction_id)->findOrFail($id);

        if ($vehicule->statut === 'affecté') {
            return redirect()->back()->with('error', 'Impossible de supprimer un véhicule actuellement affecté.');
        }

        $this->logVehicule('suppression', "Suppression du véhicule {$vehicule->immatriculation}", $vehicule->id);
        $vehicule->delete();

        return redirect()->route('vehicules.index')->with('message', 'Véhicule supprimé avec succès.');
    }

    // ─── Affectation ──────────────────────────────────────────────────────────

    public function affecter(Request $request, $id)
    {
        $vehicule = Vehicule::where('direction_id', Auth::user()->direction_id)->findOrFail($id);

        if ($vehicule->statut === 'affecté') {
            return redirect()->back()->with('error', 'Ce véhicule est déjà affecté.');
        }

        if ($vehicule->statut === 'enlevé') {
            return redirect()->back()->with('error', 'Ce véhicule a été enlevé du parc et ne peut plus être affecté.');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        try {
            DB::beginTransaction();

            VehiculeAssignment::create([
                'vehicule_id' => $vehicule->id,
                'user_id'     => $request->user_id,
                'assigned_by' => Auth::id(),
                'assigned_at' => now(),
                'direction_id'=> Auth::user()->direction_id,
            ]);

            $vehicule->update(['statut' => 'affecté']);

            $user = User::find($request->user_id);
            $this->logVehicule('affectation', "Affectation du véhicule {$vehicule->immatriculation} à {$user->nom} {$user->prenom}", $vehicule->id);

            DB::commit();
            return redirect()->back()->with('status', 'Véhicule affecté avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur affectation véhicule', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'affectation.');
        }
    }

    // ─── Retrait ──────────────────────────────────────────────────────────────

    public function retirer(Request $request, $assignmentId)
    {
        $request->validate([
            'commentaire_retour' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $assignment = VehiculeAssignment::with('vehicule')
                ->where('direction_id', Auth::user()->direction_id)
                ->findOrFail($assignmentId);

            $assignment->update([
                'returned_at'        => now(),
                'returned_by'        => Auth::id(),
                'commentaire_retour' => $request->commentaire_retour,
            ]);

            $assignment->vehicule->update(['statut' => 'disponible']);

            $this->logVehicule('retrait', "Retrait du véhicule {$assignment->vehicule->immatriculation}", $assignment->vehicule->id);

            DB::commit();
            return redirect()->back()->with('success', 'Véhicule retiré avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur retrait véhicule', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Une erreur est survenue lors du retrait.');
        }
    }

    // ─── Historique ───────────────────────────────────────────────────────────

    public function historique(Request $request)
    {
        $query = VehiculeAssignment::with(['vehicule', 'user', 'assignedBy', 'returnedBy'])
            ->where('direction_id', Auth::user()->direction_id);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('vehicule', fn($q) => $q->where('immatriculation', 'like', "%$s%"))
                  ->orWhereHas('user', fn($q) => $q->where('nom', 'like', "%$s%")->orWhere('prenom', 'like', "%$s%"));
        }

        $assignments = $query->orderByDesc('assigned_at')->get();

        return view('Patrimoine.vehicules.historique', compact('assignments'));
    }

    // ─── Helper log ───────────────────────────────────────────────────────────

    private function logVehicule(string $action, string $description, $vehiculeId = null): void
    {
        $this->logAction($action, $description, vehiculeId: $vehiculeId);
    }
}
