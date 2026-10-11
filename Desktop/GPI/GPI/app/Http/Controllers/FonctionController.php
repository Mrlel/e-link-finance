<?php

namespace App\Http\Controllers;

use App\Models\Fonction;
use App\Models\Direction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class FonctionController extends Controller
{
    // LISTE
    public function index()
    {
        $fonctions = Fonction::where('direction_id', Auth::user()->direction_id)->get();;
        return view('fonctions.index', compact('fonctions'));
    }

    // SAVE
    public function store(Request $request)
    {
        $request->validate([
            'fonction' => 'required|string|max:100',
        ]);

        $fonction = Fonction::create([
            'fonction' => $request->fonction,
            'direction_id' => Auth::user()->direction_id
        ]);

        return redirect()->back()->with('success', 'Fonction ajoutée avec succès !');
    }

    // EDIT
    public function edit(Fonction $fonction)
    {
        return view('fonctions.edit', compact('fonction'));
    }

    // UPDATE
    public function update(Request $request, Fonction $fonction)
    {
        $request->validate([
            'fonction' => 'required|string|max:100',
        ]);

        $fonction->update($request->only('fonction'));

        return redirect()->route('fonctions.index')->with('success', 'Fonction modifiée !');
    }

    // DELETE
    public function destroy(Fonction $fonction)
    {
        // ❗ Empêcher suppression si des users utilisent cette fonction
        if ($fonction->users()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer : des utilisateurs utilisent cette fonction.');
        }

        $fonction->delete();

        return redirect()->route('fonctions.index')->with('success', 'Fonction supprimée !');
    }
}
