<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Equipement;
use App\Models\SortieEquipement;
use App\Models\User;

class SortieEquipementController extends Controller
{
    // ─── Liste des sorties de la direction ───────────────────────────────────

    public function index(Request $request)
    {
        $query = SortieEquipement::with(['equipement', 'demandeur', 'valideur'])
            ->where('direction_id', Auth::user()->direction_id);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('equipement', fn($q) =>
                $q->where('des_equipement', 'like', "%$s%")
                  ->orWhere('numero_serie', 'like', "%$s%")
            );
        }
        if ($request->filled('type_sortie')) {
            $query->where('type_sortie', $request->type_sortie);
        }
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $sorties = $query->orderByDesc('date_sortie')->get();

        return view('Patrimoine.equipements.sorties.index', compact('sorties'));
    }

    // ─── Formulaire de création ───────────────────────────────────────────────

    public function create($equipementId)
    {
        $equipement = Equipement::where('direction_id', Auth::user()->direction_id)
            ->findOrFail($equipementId);

        // Vérifier qu'il n'y a pas déjà une sortie en cours
        if ($equipement->sortieActive) {
            return redirect()->back()->with('error', 'Cet équipement a déjà une sortie en cours.');
        }

        return view('Patrimoine.equipements.sorties.create', compact('equipement'));
    }

    // ─── Enregistrement ───────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $request->validate([
            'equipement_id'     => 'required|exists:equipements,id',
            'type_sortie'       => 'required|in:maintenance_externe,reforme,enlevement,transfert',
            'motif'             => 'required|string|max:500',
            'prestataire'       => 'nullable|string|max:255',
            'lieu_destination'  => 'nullable|string|max:255',
            'date_sortie'       => 'required|date',
            'date_retour_prevue'=> 'nullable|date|after_or_equal:date_sortie',
            'observations'      => 'nullable|string|max:1000',
        ]);

        $equipement = Equipement::where('direction_id', Auth::user()->direction_id)
            ->findOrFail($request->equipement_id);

        if ($equipement->sortieActive) {
            return redirect()->back()->with('error', 'Cet équipement a déjà une sortie en cours.');
        }

        try {
            DB::beginTransaction();

            $sortie = SortieEquipement::create([
                'equipement_id'     => $equipement->id,
                'demandeur_id'      => Auth::id(),
                'direction_id'      => Auth::user()->direction_id,
                'type_sortie'       => $request->type_sortie,
                'motif'             => $request->motif,
                'prestataire'       => $request->prestataire,
                'lieu_destination'  => $request->lieu_destination,
                'date_sortie'       => $request->date_sortie,
                'date_retour_prevue'=> $request->date_retour_prevue,
                'statut'            => in_array($request->type_sortie, ['reforme', 'enlevement']) ? 'definitif' : 'en_cours',
                'observations'      => $request->observations,
            ]);

            // Mise à jour du statut de l'équipement
            $nouveauStatut = match($request->type_sortie) {
                'maintenance_externe' => 'en maintenance',
                'enlevement'          => 'enlèvement',
                default               => 'en stock',
            };
            $equipement->update(['statut' => $nouveauStatut]);

            $this->logSortie(
                'sortie',
                "Sortie ({$sortie->type_label}) de l'équipement {$equipement->des_equipement} — {$request->motif}",
                $equipement->id
            );

            DB::commit();
            return redirect()->route('equipement.details', $equipement->id)
                ->with('status', 'Sortie enregistrée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur sortie équipement', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    // ─── Retour d'un équipement sorti ─────────────────────────────────────────

    public function retour(Request $request, $sortieId)
    {
        $request->validate([
            'date_retour_effective' => 'required|date',
            'observations'          => 'nullable|string|max:1000',
        ]);

        $sortie = SortieEquipement::with('equipement')
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

            // Remettre l'équipement en stock
            $sortie->equipement->update(['statut' => 'en stock']);

            $this->logSortie(
                'retour_sortie',
                "Retour de l'équipement {$sortie->equipement->des_equipement} après {$sortie->type_label}",
                $sortie->equipement_id
            );

            DB::commit();
            return redirect()->back()->with('success', 'Retour de l\'équipement enregistré avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur retour sortie équipement', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Une erreur est survenue.');
        }
    }

    // ─── Détail d'une sortie ──────────────────────────────────────────────────

    public function show($id)
    {
        $sortie = SortieEquipement::with(['equipement', 'demandeur', 'valideur', 'retourValidePar'])
            ->where('direction_id', Auth::user()->direction_id)
            ->findOrFail($id);

        return view('Patrimoine.equipements.sorties.show', compact('sortie'));
    }

    // ─── Helper log ───────────────────────────────────────────────────────────

    private function logSortie(string $action, string $description, $equipementId = null): void
    {
        \App\Models\Journal_modif::create([
            'action'        => $action,
            'description'   => $description,
            'equipement_id' => $equipementId,
            'user_id'       => Auth::id(),
            'direction_id'  => Auth::user()->direction_id,
        ]);
    }
}
