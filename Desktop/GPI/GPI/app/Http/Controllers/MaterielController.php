<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Equipement;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Licence;
use App\Models\Assignment;
use App\Models\eqp_Type;
use App\Models\Type_logiciel;
use App\Models\SourceAcquisition;
use App\Models\eqp_Categorie;
use App\Models\Poste;
use App\Traits\LogsModifications;

class MaterielController extends Controller
{
    use LogsModifications;

private function getEquipementsParStatut($Statut)
{
    $directionId = Auth::user()->direction_id;

    return Equipement::with('sortieActive')
                     ->where('direction_id', $directionId)
                     ->where('statut', $Statut)
                     ->get();
}

private function getEquipementsActifs(Request $request = null)
{
    $directionId = Auth::user()->direction_id;
    $query = Equipement::with('sortieActive')->where('direction_id', $directionId);

    // Exclure les enlevés par défaut
    if (!($request && $request->boolean('afficher_enleves'))) {
        $query->where('statut', '!=', 'enlèvement');
    }
    return $query;
}

private function getMaterielsGeneraux()
{
    $directionId = Auth::user()->direction_id;

    return [
        'users' => User::where('direction_id', $directionId)->get(),
        'licences' => Licence::where('direction_id', $directionId)->get(),
        'assignments' => Assignment::all(), // ici assignment doit être aussi filtré si nécessaire !
        'EquipementsAttribues' => Equipement::where('direction_id', $directionId)->has('assignments')->get(),
        'materielsNonAttribues' => Equipement::where('direction_id', $directionId)->doesntHave('assignments')->get(),
    ];
}

public function list_stock(Request $request)
{
    $materiels = $this->getMaterielsGeneraux();

    $materiels['materielsEnStock'] = $this->getEquipementsParStatut('en stock')
        ->sortByDesc('created_at');

    $materiels['materielsEnStockPage'] = Equipement::with('sortieActive')
        ->where('direction_id', Auth::user()->direction_id)
        ->where('statut', 'en stock')
        ->when($request->filled('q'), function ($query) use ($request) {
            $term = $request->q;
            $query->where(function ($query) use ($term) {
                $query->where('des_equipement', 'like', "%{$term}%")
                    ->orWhere('marque', 'like', "%{$term}%")
                    ->orWhere('numero_serie', 'like', "%{$term}%")
                    ->orWhere('categorie', 'like', "%{$term}%");
            });
        })
        ->when($request->filled('categorie'), function ($query) use ($request) {
            $query->where('categorie', $request->categorie);
        })
        ->when($request->filled('etat'), function ($query) use ($request) {
            $query->where('etat', $request->etat);
        })
        ->orderByDesc('created_at')
        ->paginate(20)
        ->withQueryString();

    $materiels['categories'] = Equipement::CATEGORIES;
    $materiels['etats'] = Equipement::ETATS;

    $materiels['materielsHorsService'] = $this->getEquipementsParStatut('hors service')
        ->sortByDesc('created_at');
    $materiels['materielsEnMaintenance'] = $this->getEquipementsParStatut('en maintenance')
        ->sortByDesc('created_at');
    $materiels['materielsEnService'] = $this->getEquipementsParStatut('en service')
        ->sortByDesc('created_at');

    $materiels['nombreDeployes'] = $materiels['materielsEnService']->count();
    $materiels['sources'] = SourceAcquisition::all();

    $postes = Poste::with('equipements')
        ->where('direction_id', Auth::user()->direction_id)
        ->orderBy('created_at', 'desc')
        ->get();

    return view('Patrimoine.equipements.stock_materiel', $materiels, compact('postes'));
}

public function materielsEnService()
{
    $materiels = $this->getMaterielsGeneraux();
    $materiels['materielsEnStock'] = $this->getEquipementsParStatut('en stock')
        ->sortByDesc('created_at');
    $materiels['materielsHorsService'] = $this->getEquipementsParStatut('hors service')
        ->sortByDesc('created_at');
    $materiels['materielsEnMaintenance'] = $this->getEquipementsParStatut('en maintenance')
        ->sortByDesc('created_at');
    $materiels['materielsEnService'] = $this->getEquipementsParStatut('en service')
        ->sortByDesc('created_at');
    $materiels['nombreDeployes'] = $materiels['materielsEnService']->count();
    $postes = Poste::with('equipements')->where('direction_id', Auth::user()->direction_id)->get();

    return view('Patrimoine.equipements.materiels-en-service', $materiels, compact('postes'));
}

public function materielsEnMaintenance()
{
    $materiels = $this->getMaterielsGeneraux();
     $materiels['materielsEnStock'] = $this->getEquipementsParStatut('en stock')
        ->sortByDesc('created_at');
    $materiels['materielsHorsService'] = $this->getEquipementsParStatut('hors service')
        ->sortByDesc('created_at');
    $materiels['materielsEnMaintenance'] = $this->getEquipementsParStatut('en maintenance')
        ->sortByDesc('created_at');
    $materiels['materielsEnService'] = $this->getEquipementsParStatut('en service')
        ->sortByDesc('created_at');
    $materiels['nombreDeployes'] = $materiels['materielsEnService']->count();
    $postes = Poste::with('equipements')->where('direction_id', Auth::user()->direction_id)->get();

    return view('Patrimoine.equipements.materiels-en-maintenance', $materiels, compact('postes'));
}
/*
public function recherche(Request $request)
{
    $directionId = Auth::user()->direction_id;
    $query = $request->input('q');

    $equipements = Equipement::where('direction_id', $directionId)
        ->where(function($q) use ($query) {
            $q->where('des_equipement', 'like', "%$query%")
              ->orWhere('marque', 'like', "%$query%")
              ->orWhere('modele', 'like', "%$query%")
              ->orWhere('categorie', 'like', "%$query%")
              ->orWhere('num_inventaire', 'like', "%$query%");
        })
        ->get();

    // Passe les résultats à la vue (adapte selon ta structure)
    return view('Materiels.stock_materiel', [
        'equipements' => $equipements,
        'q' => $query,
        // Ajoute ici les autres variables nécessaires à la vue
    ]);
}*/
}