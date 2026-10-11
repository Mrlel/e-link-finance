<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Equipement;
use App\Models\Vehicule;
use App\Models\Mobilier;

class PatrimoineEnleveController extends Controller
{
    public function index(Request $request)
    {
        $dirId = Auth::user()->direction_id;

        $eqQuery = Equipement::with(['sorties' => fn($q) => $q->where('type_sortie', 'enlevement')->latest()])
            ->where('direction_id', $dirId)
            ->where('statut', 'enlèvement');

        $vhQuery = Vehicule::with(['sorties' => fn($q) => $q->where('type_sortie', 'enlevement')->latest()])
            ->where('direction_id', $dirId)
            ->where('statut', 'enlevé');

        $mbQuery = Mobilier::with(['sorties' => fn($q) => $q->where('type_sortie', 'enlevement')->latest()])
            ->where('direction_id', $dirId)
            ->where('statut', 'enlevé');

        // Filtre par période
        if ($request->filled('date_debut')) {
            $eqQuery->whereHas('sorties', fn($q) => $q->where('date_sortie', '>=', $request->date_debut));
            $vhQuery->whereHas('sorties', fn($q) => $q->where('date_sortie', '>=', $request->date_debut));
            $mbQuery->whereHas('sorties', fn($q) => $q->where('date_sortie', '>=', $request->date_debut));
        }
        if ($request->filled('date_fin')) {
            $eqQuery->whereHas('sorties', fn($q) => $q->where('date_sortie', '<=', $request->date_fin));
            $vhQuery->whereHas('sorties', fn($q) => $q->where('date_sortie', '<=', $request->date_fin));
            $mbQuery->whereHas('sorties', fn($q) => $q->where('date_sortie', '<=', $request->date_fin));
        }

        $equipements = $eqQuery->get();
        $vehicules   = $vhQuery->get();
        $mobiliers   = $mbQuery->get();

        $total = $equipements->count() + $vehicules->count() + $mobiliers->count();

        return view('Patrimoine.enleves.index', compact('equipements', 'vehicules', 'mobiliers', 'total'));
    }
}
