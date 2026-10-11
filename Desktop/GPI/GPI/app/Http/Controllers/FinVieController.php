<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Equipement;
use App\Models\Vehicule;
use App\Models\Mobilier;
use Carbon\Carbon;

class FinVieController extends Controller
{
    public function index(Request $request)
    {
        $directionId = Auth::user()->direction_id;

        // Seuil configurable via query string (défaut 90 jours)
        $jours = (int) $request->get('jours', 90);
        $seuil = now()->addDays($jours);

        // ── Équipements ──────────────────────────────────────────────────────
        $equipements = Equipement::whereNotNull('date_fin_vie')
            ->where('direction_id', $directionId)
            ->where('date_fin_vie', '<=', $seuil)
            ->whereNotIn('statut', ['enlèvement'])
            ->orderBy('date_fin_vie')
            ->get();

        // ── Mobilier ─────────────────────────────────────────────────────────
        $mobiliers = Mobilier::whereNotNull('date_fin_vie')
            ->where('direction_id', $directionId)
            ->where('date_fin_vie', '<=', $seuil)
            ->whereNotIn('statut', ['en réforme'])
 
            ->orderBy('date_fin_vie')
            ->get();

        // ── Totaux par urgence ────────────────────────────────────────────────
        $stats = [
            'expires'    => 0,  // date dépassée
            'critiques'  => 0,  // ≤ 30 jours
            'proches'    => 0,  // 31–90 jours
        ];

        $tous = collect()
            ->merge($equipements->map(fn($e) => ['fin' => $e->date_fin_vie]))
            ->merge($mobiliers->map(fn($m) => ['fin' => $m->date_fin_vie]));

        foreach ($tous as $item) {
            $diff = now()->diffInDays($item['fin'], false);
            if ($diff < 0)       $stats['expires']++;
            elseif ($diff <= 30) $stats['critiques']++;
            else                 $stats['proches']++;
        }

        return view('fin-vie.index', compact(
            'equipements', 'mobiliers', 'stats', 'jours'
        ));
    }
}
