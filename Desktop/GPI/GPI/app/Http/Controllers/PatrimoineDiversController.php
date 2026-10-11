<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PatrimoineDivers;
use App\Models\PatrimoineDiversAssignment;
use App\Models\User;
use App\Exports\PatrimoineDiversExport;
use App\Traits\LogsModifications;

class PatrimoineDiversController extends Controller
{
    use LogsModifications;

    public function index(Request $request)
    {
        $query = PatrimoineDivers::where('direction_id', Auth::user()->direction_id);

        if ($request->filled('search')) {
            $query->where('libelle', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }
        if ($request->filled('couleur')) {
            $query->where('couleur', $request->couleur);
        }
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('etat')) {
            $query->where('etat', $request->etat);
        }
        
        $items = $query->orderBy('created_at', 'desc')->get();
        $users = User::where('direction_id', Auth::user()->direction_id)->get();

        return view('Patrimoine.divers.index', compact('items', 'users'));
    }

    // ─── Export ──────────────────────────────────────────────────────────────

    public function exportExcel()
    {
        return Excel::download(new PatrimoineDiversExport(), 'patrimoine_divers_' . now()->format('Ymd') . '.xlsx');
    }

    public function exportPdf()
    {
        $items = PatrimoineDivers::with('utilisateursActuels')
            ->where('direction_id', Auth::user()->direction_id)
            ->get();

        $pdf = Pdf::loadView('Patrimoine.divers.pdf', compact('items'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('patrimoine_divers_' . now()->format('Ymd') . '.pdf');
    }

    public function create()
    {
        return view('Patrimoine.divers.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'libelle'          => 'required|string|max:255',
            'nombre'           => 'required|integer|min:0',
            'categorie'        => 'nullable|in:fournitures,consommables,autre',
            'couleur'          => 'nullable|in:' . implode(',', PatrimoineDivers::COULEURS),
            'date_acquisition' => 'nullable|date',
            'etat'             => 'required|in:neuf,bon,use',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $item = PatrimoineDivers::create([
            'libelle'          => $request->libelle,
            'nombre'           => $request->nombre,
            'categorie'        => $request->categorie,
            'couleur'          => $request->couleur,
            'date_acquisition' => $request->date_acquisition,
            'etat'             => $request->etat,
            'statut'           => $request->nombre > 0 ? 'en stock' : 'épuisé',
            'user_id'          => Auth::id(),
            'direction_id'     => Auth::user()->direction_id,
        ]);

        $this->logPatrimoine('ajout', "Ajout de patrimoine divers : {$item->libelle}", $item->id);

        return redirect()->route('patrimoine-divers.index')->with('status', 'Article ajouté avec succès.');
    }

    public function edit($id)
    {
        $item = PatrimoineDivers::where('direction_id', Auth::user()->direction_id)->findOrFail($id);
        return view('Patrimoine.divers.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'libelle'          => 'required|string|max:255',
            'nombre'           => 'required|integer|min:0',
            'categorie'        => 'nullable|in:fournitures,consommables,autre',
            'couleur'          => 'nullable|in:' . implode(',', PatrimoineDivers::COULEURS),
            'date_acquisition' => 'nullable|date',
            'etat'             => 'required|in:neuf,bon,use',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $item = PatrimoineDivers::where('direction_id', Auth::user()->direction_id)->findOrFail($id);

        $item->update([
            'libelle'          => $request->libelle,
            'nombre'           => $request->nombre,
            'categorie'        => $request->categorie,
            'couleur'          => $request->couleur,
            'date_acquisition' => $request->date_acquisition,
            'etat'             => $request->etat,
        ]);

        $this->logPatrimoine('modification', "Modification de patrimoine divers : {$item->libelle}", $item->id);

        return redirect()->route('patrimoine-divers.index')->with('status', 'Article modifié avec succès.');
    }


    public function destroy($id)
    {
        $item = PatrimoineDivers::where('direction_id', Auth::user()->direction_id)->findOrFail($id);
        $this->logPatrimoine('suppression', "Suppression de patrimoine divers : {$item->libelle}", $item->id);
        $item->delete();

        return redirect()->route('patrimoine-divers.index')->with('message', 'Article supprimé avec succès.');
    }


    public function show($id)
    {
        $item = PatrimoineDivers::with('assignments.user', 'assignments.assignedBy')
            ->where('direction_id', Auth::user()->direction_id)
            ->findOrFail($id);

        return view('Patrimoine.divers.show', compact('item'));
    }


    public function pageAssigner($id)
    {
        $item  = PatrimoineDivers::where('direction_id', Auth::user()->direction_id)->findOrFail($id);
        $users = User::where('direction_id', Auth::user()->direction_id)->get();
        return view('Patrimoine.divers.assigner', compact('item', 'users'));
    }

    public function assigner(Request $request, $id)
    {
        $item = PatrimoineDivers::where('direction_id', Auth::user()->direction_id)->findOrFail($id);

        $request->validate([
            'user_id'  => 'required|exists:users,id',
            'quantite' => "required|integer|min:1|max:{$item->nombre}",
        ]);

        try {
            DB::beginTransaction();

            PatrimoineDiversAssignment::create([
                'patrimoine_divers_id' => $item->id,
                'user_id'              => $request->user_id,
                'assigned_by'          => Auth::id(),
                'quantite'             => $request->quantite,
                'assigned_at'          => now(),
                'direction_id'         => Auth::user()->direction_id,
            ]);

            $nouveauNombre = $item->nombre - $request->quantite;
            $item->update([
                'nombre' => $nouveauNombre,
                'statut' => $nouveauNombre <= 0 ? 'épuisé' : 'partiellement assigné',
            ]);

            $this->logPatrimoine(
                'attribution',
                "Attribution de {$request->quantite} x {$item->libelle} à l'utilisateur ID {$request->user_id}",
                $item->id
            );

            DB::commit();
            return redirect()->back()->with('status', 'Article assigné avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur assignation patrimoine divers', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'assignation.');
        }
    }

    public function reapprovisionner(Request $request, $id)
    {
        $request->validate([
            'quantite_ajout' => 'required|integer|min:1',
            'commentaire'    => 'nullable|string|max:255',
        ]);

        $item = PatrimoineDivers::where('direction_id', Auth::user()->direction_id)->findOrFail($id);

        $nouveauNombre = $item->nombre + $request->quantite_ajout;
        $item->update([
            'nombre' => $nouveauNombre,
            'statut' => 'en stock',
        ]);

        $this->logPatrimoine(
            'réapprovisionnement',
            "Réapprovisionnement de {$request->quantite_ajout} x {$item->libelle}" . ($request->commentaire ? " — {$request->commentaire}" : ''),
            $item->id
        );

        return redirect()->back()->with('status', "Stock mis à jour : +{$request->quantite_ajout} unité(s) ajoutée(s).");
    }

 
    public function retourner(Request $request, $assignmentId)
    {
        $request->validate([
            'commentaire_retour' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $assignment = PatrimoineDiversAssignment::with('patrimoineDivers')
                ->where('direction_id', Auth::user()->direction_id)
                ->findOrFail($assignmentId);

            $assignment->update([
                'returned_at'        => now(),
                'returned_by'        => Auth::id(),
                'commentaire_retour' => $request->commentaire_retour,
            ]);

            $item = $assignment->patrimoineDivers;
            $nouveauNombre = $item->nombre + $assignment->quantite;
            $item->update([
                'nombre' => $nouveauNombre,
                'statut' => 'en stock',
            ]);

            $this->logPatrimoine(
                'retour',
                "Retour de {$assignment->quantite} x {$item->libelle}",
                $item->id
            );

            DB::commit();
            return redirect()->back()->with('success', 'Article retourné avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur retour patrimoine divers', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Une erreur est survenue lors du retour.');
        }
    }


    public function historique()
    {
        $assignments = PatrimoineDiversAssignment::with(['patrimoineDivers', 'user', 'assignedBy'])
            ->where('direction_id', Auth::user()->direction_id)
            ->orderByDesc('assigned_at')
            ->get();

        return view('Patrimoine.divers.historique', compact('assignments'));
    }

    // ─── Helper log ────────────────────────────────────────────────────────

    private function logPatrimoine(string $action, string $description, $itemId = null): void
    {
        $this->logAction($action, $description, patrimoineDiversId: $itemId);
    }
}
