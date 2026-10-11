<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DemandeMaintenance;
use App\Models\User;
use App\Models\AssignmentController;
use App\Models\AssignerController;
use App\Models\Direction;
use Illuminate\Support\Str;



class LoginController extends Controller
{
    public function edit(Request $request){
        $user = Auth::user();
        return view('Users.editProfile', compact('user'));
    }
    protected function authenticated(Request $request, $user)
    {
        if (!$user->password_changed) {
            return redirect()->route('change.password');
        }

        return redirect()->intended($this->redirectPath());
    }

    public function loginUser(Request $request)
{
    $credentials = $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    if (!Auth::attempt($credentials)) {
        return back()->withErrors([
            'email' => 'Identifiants incorrects'
        ]);
    }

    $request->session()->regenerate();

    $user = Auth::user();

    // Super Admin
    if ($user->role === 'superadmin') {
        return redirect('/superadmin/dashboard')
            ->with('success', 'Bienvenue Super Admin !');
    }

    // Vérification direction
    if (!$user->direction) {
        Auth::logout();

        return back()->withErrors([
            'message' => 'Votre compte n’est rattaché à aucune direction.'
        ]);
    }

    if ($user->direction->statut !== 'active') {
        Auth::logout();

        return back()->withErrors([
            'message' => 'Votre direction est désactivée.'
        ]);
    }

    if (!$user->password_changed_at) {
        return redirect()->route('password.change');
    }

    if ($user->isAdminTier()) {
        return redirect('/adminDashboard');
    }

    // Utilisateur simple
    return redirect()
        ->route('userDashboard')
        ->with('success', 'Connexion réussie !');
}

public function updatePassword(Request $request)
{
    $request->validate([
        'password' => 'required|min:8|confirmed',
    ]);

    $user = Auth::user();
    $user->password = bcrypt($request->password);
    $user->password_changed_at = now(); 
    $user->save();

    return redirect()->route('userDashboard')->with('success', 'Mot de passe changé avec succès.');
}



    public function logout_user(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('status', 'Déconnexion réussit.');
    }


}
