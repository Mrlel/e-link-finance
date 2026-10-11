<?php

namespace App\Http\Controllers;

use App\Models\Mobilier;
use App\Models\PatrimoineDivers;
use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Vue d'audit en lecture seule regroupant les patrimoines hors matériel informatique
// (véhicules, fournitures/consommables, mobilier) - pas de CRUD ici, uniquement
// consultation/filtrage/export ; la gestion reste sur les pages dédiées à chaque type.
class PatrimoineInventaireController extends Controller
{
    public function index(Request $request)
    {
        $directionId = Auth::user()->direction_id;

        $vehicules = Vehicule::with('affectationActive.user')
            ->where('direction_id', $directionId)
            ->when($request->filled('v_search'), function ($q) use ($request) {
                $s = $request->v_search;
                $q->where(function ($q2) use ($s) {
                    $q2->where('immatriculation', 'like', "%$s%")
                        ->orWhere('marque', 'like', "%$s%")
                        ->orWhere('modele', 'like', "%$s%");
                });
            })
            ->when($request->filled('v_categorie'), fn ($q) => $q->where('categorie', $request->v_categorie))
            ->when($request->filled('v_etat'), fn ($q) => $q->where('etat', $request->v_etat))
            ->when($request->filled('v_statut'), fn ($q) => $q->where('statut', $request->v_statut))
            ->orderBy('immatriculation')
            ->get();

        $diversItems = PatrimoineDivers::where('direction_id', $directionId)
            ->when($request->filled('d_search'), fn ($q) => $q->where('libelle', 'like', '%' . $request->d_search . '%'))
            ->when($request->filled('d_categorie'), fn ($q) => $q->where('categorie', $request->d_categorie))
            ->when($request->filled('d_statut'), fn ($q) => $q->where('statut', $request->d_statut))
            ->when($request->filled('d_etat'), fn ($q) => $q->where('etat', $request->d_etat))
            ->orderBy('libelle')
            ->get();

        $mobiliers = Mobilier::with('affectationActive.user')
            ->where('direction_id', $directionId)
            ->when($request->filled('m_search'), function ($q) use ($request) {
                $s = $request->m_search;
                $q->where(function ($q2) use ($s) {
                    $q2->where('designation', 'like', "%$s%")
                        ->orWhere('num_inventaire', 'like', "%$s%");
                });
            })
            ->when($request->filled('m_etat'), fn ($q) => $q->where('etat', $request->m_etat))
            ->when($request->filled('m_statut'), fn ($q) => $q->where('statut', $request->m_statut))
            ->orderBy('designation')
            ->get();

        $activeTab = in_array($request->query('tab'), ['vehicules', 'divers', 'mobilier'], true)
            ? $request->query('tab')
            : 'vehicules';

        return view('Patrimoine.inventaire-partage', compact('vehicules', 'diversItems', 'mobiliers', 'activeTab'));
    }
}
