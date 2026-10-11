<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DemandeMaintenance;
use App\Models\User;
use App\Models\Equipement;
use App\Models\Assignment;
use App\Models\AssignerLogiciel;
use App\Models\DemandeMateriel;

class MesDemandesController extends Controller
{
    public function showMesDemandesEnCours()
    {
            $user = Auth::user();
            
            // Récupérer les équipements et logiciels assignés
            $userEquipements = Assignment::where('user_id', $user->id)->whereNull('returned_at')->get();
            // Récupérer les demandes de l'utilisateur
            $demande_materiels = DemandeMateriel::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
            
            $demande_maintenances = DemandeMaintenance::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('MesDemandes.index', compact(
                'user',
                'userEquipements',
                'demande_materiels',
                'demande_maintenances',
            ));
    }
}
