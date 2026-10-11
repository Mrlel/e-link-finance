<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    public function changePassword(Request $request)
    {
         $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        // Mettre à jour le mot de passe de l'utilisateur
        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->password_changed_at = now();
        $user->save();
        
        if ($user->role === 'superadmin') {
                return redirect('/superadmin/dashboard')->with('success', 'Bienvenue Super Admin !');
        }
        if ($user->isAdminTier()) {
                return redirect('/adminDashboard')->with('success', 'Mot de passe changé avec succès.');
        }

        return redirect('/userDashboard')->with('success', 'Bienvenue Utilisateur !');
    }
}
