<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function showChangeForm()
    {
        return view('Users.auth.change-password');
    }

    public function updatePassword(Request $request)
    {
        // Validation des champs
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        try {
            // Récupérer l'utilisateur connecté
            $user = Auth::user();
            $user->must_change_password = false; // Désactive l'obligation de changer le mot de passe
            $user->save();

            return redirect()->route('userDashboard')->with('success', 'Mot de passe changé avec succès !');
                } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Une erreur est survenue : ' . $e->getMessage()]);
        }
    }
}
