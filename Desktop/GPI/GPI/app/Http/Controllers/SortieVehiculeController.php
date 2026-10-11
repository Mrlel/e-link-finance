<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Vehicule;
use App\Models\SortieVehicule;
use App\Models\VehiculeAssignment;
use App\Models\User;

class SortieVehiculeController extends Controller
{
    // ─── Index ────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = SortieVehicule::with(['vehicule', 'demandeur', 'retourValidePar'])
            ->where('direction_id', Auth::user()->direction_id);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('vehicule', fn($q) =>
                $q->where('immatriculation', 'like', "%$s%")
                  ->orWhere('marque', 'like', "%$s%")
            );
        }
        if ($request->filled('type_sortie')) {
            $query->where('type_sortie', $request->type_sortie);
        }
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $sorties = $query->orderByDesc('date_sortie')->get();

        return view('Patrimoine.vehicules.sorties.index', compact('sorties'));
    }

    // ─── Create ───────────────────────────────────────────────────────────────

    public function create($vehiculeId)
    {
        $vehicule = Vehicule::where('direction_id', Auth::user()->direction_id)
            ->findOrFail($vehiculeId);

        if ($vehicule->sortieActive) {
            return redirect()->back()->with('error', 'Ce véhicule a déjà une sortie en cours.');
        }

        if ($vehicule->statut === 'enlevé') {
            return redirect()->back()->with('error', 'Ce véhicule a été enlevé du parc.');
        }

        return view('Patrimoine.vehicules.sorties.create', compact('vehicule'));
    }

    // ─── Store ────────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $request->validate([
            'vehicule_id'       => 'required|exists:vehicules,id',
            'type_sortie'       => 'required|in:maintenance_externe,reforme,enlevement,transfert',
            'motif'             => 'required|string|max:500',
            'prestataire'       => 'nullable|string|max:255',
            'lieu_destination'  => 'nullable|string|max:255',
            'date_sortie'       => 'required|date',
            'date_retour_prevue'=> 'nullable|date|after_or_equal:date_sortie',
            'observations'      => 'nullable|string|max:1000',
        ]);

        $vehicule = Vehicule::where('direction_id', Auth::user()->direction_id)
            ->findOrFail($request->vehicule_id);

        if ($vehicule->sortieActive) {
            return redirect()->back()->with('error', 'Ce véhicule a déjà une sortie en cours.');
        }

        if ($vehicule->statut === 'enlevé') {
            return redirect()->back()->with('error', 'Ce véhicule a été enlevé du parc.');
        }

        try {
            DB::beginTransaction();

            // Clore l'affectation active si elle existe
            $affectationActive = VehiculeAssignment::where('vehicule_id', $vehicule->id)
                ->whereNull('returned_at')
                ->first();

            if ($affectationActive) {
                $affectationActive->update([
                    'returned_at'        => now(),
                    'returned_by'        => Auth::id(),
                    'commentaire_retour' => 'Clôturé automatiquement lors de la sortie.',
                ]);
            }

            $statutSortie = in_array($request->type_sortie, ['reforme', 'enlevement'])
                ? 'definitif'
                : 'en_cours';

            $sortie = SortieVehicule::create([
                'vehicule_id'       => $vehicule->id,
                'demandeur_id'      => Auth::id(),
                'direction_id'      => Auth::user()->direction_id,
                'type_sortie'       => $request->type_sortie,
                'motif'             => $request->motif,
                'prestataire'       => $request->prestataire,
                'lieu_destination'  => $request->lieu_destination,
                'date_sortie'       => $request->date_sortie,
                'date_retour_prevue'=> $request->date_retour_prevue,
                'statut'            => $statutSortie,
                'observations'      => $request->observations,
            ]);

            $nouveauStatut = match($request->type_sortie) {
                'maintenance_externe', 'transfert' => 'en maintenance',
                'enlevement'                       => 'enlevé',
                'reforme'                          => 'hors service',
            };
            $vehicule->update(['statut' => $nouveauStatut]);

            $this->logSortie(
                'sortie_vehicule',
                "Sortie ({$sortie->type_label}) du véhicule {$vehicule->immatriculation} — {$request->motif}"
            );

            DB::commit();
            return redirect()->route('vehicules.show', $vehicule->id)
                ->with('status', 'Sortie enregistrée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur sortie véhicule', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    // ─── Retour ───────────────────────────────────────────────────────────────

    public function retour(Request $request, $sortieId)
    {
        $request->validate([
            'date_retour_effective' => 'required|date',
            'observations'          => 'nullable|string|max:1000',
        ]);

        $sortie = SortieVehicule::with('vehicule')
            ->where('direction_id', Auth::user()->direction_id)
            ->where('statut', 'en_cours')
            ->findOrFail($sortieId);

        try {
            DB::beginTransaction();

            $sortie->update([
                'date_retour_effective' => $request->date_retour_effective,
                'retour_valide_par'     => Auth::id(),
                'statut'                => 'retourne',
                'observations'          => $request->observations ?? $sortie->observations,
            ]);

            $sortie->vehicule->update(['statut' => 'disponible']);

            $this->logSortie(
                'retour_sortie_vehicule',
                "Retour du véhicule {$sortie->vehicule->immatriculation} après {$sortie->type_label}"
            );

            DB::commit();
            return redirect()->back()->with('success', 'Retour du véhicule enregistré avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur retour sortie véhicule', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Une erreur est survenue.');
        }
    }

    // ─── Show ─────────────────────────────────────────────────────────────────

    public function show($id)
    {
        $sortie = SortieVehicule::with(['vehicule', 'demandeur', 'valideur', 'retourValidePar'])
            ->where('direction_id', Auth::user()->direction_id)
            ->findOrFail($id);

        return view('Patrimoine.vehicules.sorties.show', compact('sortie'));
    }

    // ─── Helper log ───────────────────────────────────────────────────────────

    private function logSortie(string $action, string $description): void
    {
        \App\Models\Journal_modif::create([
            'action'        => $action,
            'description'   => $description,
            'equipement_id' => null,
            'user_id'       => Auth::id(),
            'direction_id'  => Auth::user()->direction_id,
        ]);
    }
}
