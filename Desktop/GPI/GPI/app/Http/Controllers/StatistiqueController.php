<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class StatistiqueController extends Controller{

    public function getEquipementStats()
    {
        $stats = DB::table('equipements')
            ->select('etat', DB::raw('count(*) as total'))
            ->groupBy('etat')
            ->get();

        return response()->json($stats);
    }

    public function getMaintenanceStats()
    {
        $stats = DB::table('maintenances')
            ->select('statut_maintenance', DB::raw('count(*) as total'))
            ->groupBy('statut_maintenance')
            ->get();

        return response()->json($stats);
    }

    public function getUserAssignments($userId)
    {
        $assignments = DB::table('assignments')
            ->join('equipements', 'assignments.equipement_id', '=', 'equipements.id')
            ->where('assignments.user_id', $userId)
            ->select('equipements.des_equipement', 'assignments.assigned_at', 'assignments.returned_at')
            ->get();

        return response()->json($assignments);
    }   
}