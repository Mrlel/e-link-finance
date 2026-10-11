<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Mobilier;
use App\Models\MobilierAssignment;
use App\Models\MobilierSortie;
use App\Models\User;
use App\Exports\MobiliersExport;
use App\Traits\LogsModifications;

class MobilierController extends Controller
{
    use LogsModifications;

    // ─── Index ────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = Mobilier::with('affectationActive.user')
            ->where('direction_id', Auth::user()->direction_id);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q
                ->where('designation', 'like', "%$s%")
                ->orWhere('marque', 'like', "%$s%")
                ->orWhere('reference', 'like', "%$s%")
                ->orWhere('num_inventaire', 'like', "%$s%")
            );
        }
        foreach (['etat', 'statut', 'mode_acquisition'] as $f) {
            if ($request->filled($f)) $query->where($f, $request->$f);
        }

        // Exclure les enlevés par défaut
        if (!$request->boolean('afficher_enleves')) {
            $query->where('statut', '!=', 'enlevé');
        }

        $mobiliers = $query->orderByDesc('created_at')->get();
        $users     = User::where('direction_id', Auth::user()->direction_id)->get();

        // Statistiques rapides
        $stats = [
            'total'       => $mobiliers->count(),
            'en_stock'    => $mobiliers->where('statut', 'en stock')->count(),
            'affectes'    => $mobiliers->where('statut', 'affecté')->count(),
            'en_reforme'  => $mobiliers->where('statut', 'en réforme')->count(),
            'fin_vie'     => $mobiliers->filter(fn($m) => $m->date_fin_vie && $m->date_fin_vie->lte(now()->addDays(30)))->count(),
        ];

        return view('Patrimoine.mobiliers.index', compact('mobiliers', 'users', 'stats'));
    }

    // ─── Create / Store ───────────────────────────────────────────────────────

    public function create()
    {
        return view('Patrimoine.mobiliers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'designation'     => 'required|string|max:255',
            'marque'          => 'nullable|string|max:100',
            'reference'       => 'nullable|string|max:100',
            'num_inventaire'  => 'nullable|string|max:100',
            'date_acquisition'=> 'nullable|date',
            'date_fin_vie'    => 'nullable|date',
            'etat'            => 'required|in:neuf,bon,moyen,mauvais,hors service',
            'mode_acquisition'=> 'required|in:budget etat,don,autre',
            'observations'    => 'nullable|string|max:1000',
        ]);

        $data['statut']     = 'en stock';
        $data['direction_id'] = Auth::user()->direction_id;
        $data['created_by']   = Auth::id();

        $mobilier = Mobilier::create($data);
        $this->log('ajout', "Ajout du mobilier : {$data['designation']}", $mobilier->id);

        return redirect()->route('mobiliers.index')->with('status', 'Mobilier ajouté avec succès.');
    }

    // ─── Show ─────────────────────────────────────────────────────────────────

    public function show($id)
    {
        $mobilier = Mobilier::with([
            'assignments.user', 'assignments.assignedBy', 'assignments.returnedBy',
            'sorties.demandeur',
        ])->where('direction_id', Auth::user()->direction_id)->findOrFail($id);

        return view('Patrimoine.mobiliers.show', compact('mobilier'));
    }

    // ─── Edit / Update ────────────────────────────────────────────────────────

    public function edit($id)
    {
        $mobilier = Mobilier::where('direction_id', Auth::user()->direction_id)->findOrFail($id);
        return view('Patrimoine.mobiliers.edit', compact('mobilier'));
    }

    public function update(Request $request, $id)
    {
        $mobilier = Mobilier::where('direction_id', Auth::user()->direction_id)->findOrFail($id);

        $data = $request->validate([
            'designation'     => 'required|string|max:255',
            'marque'          => 'nullable|string|max:100',
            'reference'       => 'nullable|string|max:100',
            'num_inventaire'  => 'nullable|string|max:100',
            'date_acquisition'=> 'nullable|date',
            'date_fin_vie'    => 'nullable|date',
            'etat'            => 'required|in:neuf,bon,moyen,mauvais,hors service',
            'mode_acquisition'=> 'required|in:budget etat,don,autre',
            'observations'    => 'nullable|string|max:1000',
        ]);

        $mobilier->update($data);
        $this->log('modification', "Modification du mobilier : {$mobilier->designation}", $mobilier->id);

        return redirect()->route('mobiliers.show', $id)->with('status', 'Mobilier modifié avec succès.');
    }

    // ─── Destroy ──────────────────────────────────────────────────────────────

    public function destroy($id)
    {
        $mobilier = Mobilier::where('direction_id', Auth::user()->direction_id)->findOrFail($id);

        if ($mobilier->statut === 'affecté') {
            return redirect()->back()->with('error', 'Impossible de supprimer un mobilier actuellement affecté.');
        }

        $this->log('suppression', "Suppression du mobilier : {$mobilier->designation}", $mobilier->id);
        $mobilier->delete();

        return redirect()->route('mobiliers.index')->with('message', 'Mobilier supprimé.');
    }

    // ─── Affectation ──────────────────────────────────────────────────────────

    public function affecter(Request $request, $id)
    {
        $mobilier = Mobilier::where('direction_id', Auth::user()->direction_id)->findOrFail($id);

        if ($mobilier->statut === 'affecté') {
            return redirect()->back()->with('error', 'Ce mobilier est déjà affecté.');
        }

        if ($mobilier->statut === 'enlevé') {
            return redirect()->back()->with('error', 'Ce mobilier a été enlevé du parc et ne peut plus être affecté.');
        }

        $request->validate([
            'user_id'          => 'required|exists:users,id',
            'motif_affectation'=> 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            MobilierAssignment::create([
                'mobilier_id'      => $mobilier->id,
                'user_id'          => $request->user_id,
                'assigned_by'      => Auth::id(),
                'assigned_at'      => now(),
                'motif_affectation'=> $request->motif_affectation,
                'direction_id'     => Auth::user()->direction_id,
            ]);

            $mobilier->update(['statut' => 'affecté']);

            $user = User::find($request->user_id);
            $this->log('affectation', "Affectation de « {$mobilier->designation} » à {$user->nom} {$user->prenom}", $mobilier->id);

            DB::commit();
            return redirect()->back()->with('status', 'Mobilier affecté avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur affectation mobilier', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Une erreur est survenue.');
        }
    }

    // ─── Retour ───────────────────────────────────────────────────────────────

    public function retirer(Request $request, $assignmentId)
    {
        $request->validate(['commentaire_retour' => 'nullable|string|max:255']);

        try {
            DB::beginTransaction();

            $assignment = MobilierAssignment::with('mobilier')
                ->where('direction_id', Auth::user()->direction_id)
                ->findOrFail($assignmentId);

            $assignment->update([
                'returned_at'       => now(),
                'returned_by'       => Auth::id(),
                'commentaire_retour'=> $request->commentaire_retour,
            ]);

            $assignment->mobilier->update(['statut' => 'en stock']);
            $this->log('retour', "Retour du mobilier : {$assignment->mobilier->designation}", $assignment->mobilier->id);

            DB::commit();
            return redirect()->back()->with('success', 'Mobilier retiré avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Une erreur est survenue.');
        }
    }

    // ─── Sortie (réforme / transfert / perte) ─────────────────────────────────

    public function sortie(Request $request, $id)
    {
        $mobilier = Mobilier::where('direction_id', Auth::user()->direction_id)->findOrFail($id);

        $request->validate([
            'type_sortie' => 'required|in:reforme,enlevement,transfert,perte',
            'motif'       => 'required|string|max:500',
            'date_sortie' => 'required|date',
            'observations'=> 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Clore l'affectation active si elle existe
            if ($mobilier->affectationActive) {
                $mobilier->affectationActive->update([
                    'returned_at' => now(),
                    'returned_by' => Auth::id(),
                    'commentaire_retour' => 'Clôturé automatiquement lors de la sortie.',
                ]);
            }

            MobilierSortie::create([
                'mobilier_id'  => $mobilier->id,
                'demandeur_id' => Auth::id(),
                'direction_id' => Auth::user()->direction_id,
                'type_sortie'  => $request->type_sortie,
                'motif'        => $request->motif,
                'date_sortie'  => $request->date_sortie,
                'observations' => $request->observations,
            ]);

            $nouveauStatut = match($request->type_sortie) {
                'enlevement' => 'enlevé',
                default      => 'en réforme',
            };
            $mobilier->update(['statut' => $nouveauStatut]);

            $this->log('sortie', "Sortie ({$request->type_sortie}) du mobilier : {$mobilier->designation}", $mobilier->id);

            DB::commit();
            return redirect()->route('mobiliers.show', $id)->with('status', 'Sortie enregistrée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    // ─── Inventaire ───────────────────────────────────────────────────────────

    public function inventaire(Request $request)
    {
        $query = Mobilier::with('affectationActive.user')
            ->where('direction_id', Auth::user()->direction_id);

        foreach (['etat', 'statut'] as $f) {
            if ($request->filled($f)) $query->where($f, $request->$f);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q
                ->where('designation', 'like', "%$s%")
                ->orWhere('num_inventaire', 'like', "%$s%")
            );
        }

        $mobiliers = $query->orderBy('designation')->get();

        return view('Patrimoine.mobiliers.inventaire', compact('mobiliers'));
    }

    // ─── Historique ───────────────────────────────────────────────────────────

    public function historique(Request $request)
    {
        $query = MobilierAssignment::with(['mobilier', 'user', 'assignedBy', 'returnedBy'])
            ->where('direction_id', Auth::user()->direction_id);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('mobilier', fn($q) => $q->where('designation', 'like', "%$s%"))
                  ->orWhereHas('user', fn($q) => $q->where('nom', 'like', "%$s%")->orWhere('prenom', 'like', "%$s%"));
        }

        $assignments = $query->orderByDesc('assigned_at')->get();
        return view('Patrimoine.mobiliers.historique', compact('assignments'));
    }

    // ─── Export Excel ─────────────────────────────────────────────────────────

    public function exportExcel()
    {
        return Excel::download(new MobiliersExport(), 'mobiliers_' . now()->format('Ymd') . '.xlsx');
    }

    // ─── Export PDF ───────────────────────────────────────────────────────────

    public function exportPdf()
    {
        $mobiliers = Mobilier::with('affectationActive.user')
            ->where('direction_id', Auth::user()->direction_id)
            ->get();

        $pdf = Pdf::loadView('Patrimoine.mobiliers.pdf', compact('mobiliers'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('mobiliers_' . now()->format('Ymd') . '.pdf');
    }

    // ─── Helper log ───────────────────────────────────────────────────────────

    private function log(string $action, string $description, $mobilierId = null): void
    {
        $this->logAction($action, $description, mobilierId: $mobilierId);
    }
}
