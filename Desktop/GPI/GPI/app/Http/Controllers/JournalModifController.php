<?php

namespace App\Http\Controllers;

use App\Models\Journal_modif;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class JournalModifController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale('fr');

        $query = Journal_modif::with([
                'equipement', 'vehicule', 'mobilier', 'patrimoineDivers',
                'licence', 'poste', 'targetUser', 'user',
            ])
            ->where('direction_id', auth()->user()->direction_id);

        // Filtre par date de début
        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }
        // Filtre par date de fin
        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }
        // Filtre par utilisateur
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        // Filtre par action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        // Recherche libre dans la description
        if ($request->filled('q')) {
            $query->where('description', 'like', '%' . $request->q . '%');
        }

        $modifications = $query->orderBy('created_at', 'desc')->get();
        $users = \App\Models\User::where('direction_id', auth()->user()->direction_id)->get();

        $modificationsParJour = $modifications->groupBy(fn ($m) => $m->created_at->format('Y-m-d'));

        return view('Materiels.Journal.index', compact('modifications', 'modificationsParJour', 'users'));
    }

    public static function logModification($action, $description, $equipement_id)
    {
        Journal_modif::create([
            'action' => $action,
            'description' => $description,
            'equipement_id' => $equipement_id ?? null,
            'user_id' => auth()->id(),
            'direction_id' => auth()->user()->direction_id
        ]);
    }
}