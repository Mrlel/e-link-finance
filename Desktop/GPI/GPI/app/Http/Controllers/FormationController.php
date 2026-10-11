<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormationController extends Controller
{
    // Page d'accueil de la formation
    public function index()
    {
        return view('formation.index');
    }

    // Pages par module
    public function module($slug)
    {
        $modules = [
            'tableau-de-bord',
            'utilisateurs',
            'materiel-informatique',
            'parc-auto',
            'mobilier',
            'logiciels',
            'inventaire',
            'demandes',
            'activites',
        ];

        if (!in_array($slug, $modules)) {
            abort(404);
        }

        return view('formation.modules.' . $slug);
    }
}
