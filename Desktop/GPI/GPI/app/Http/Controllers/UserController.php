<?php

namespace App\Http\Controllers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Equipement;
use App\Models\User;
use App\Models\Licence;
use App\Models\Fonction;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Exports\UserImportTemplateExport;
use App\Imports\UserImport;
use App\Traits\LogsModifications;


class UserController extends Controller
{
    use LogsModifications;
  
public function showUserlist(Request $request)
{
    $userStats = $this->getUserStats();

    $statistiques = Equipement::where('direction_id', Auth::user()->direction_id)->get();
    $licences = Licence::where('direction_id', Auth::user()->direction_id)->get();

    $fonctions = Fonction::where('direction_id', Auth::user()->direction_id)->get();
    $informaticiens = User::whereHas('fonction', function ($q) {
        $q->where('fonction', 'informaticien');
    })->get();

    $usersPage = $this->getUsersByRole()
        ->where('role', '!=', 'superadmin')
        ->when($request->filled('q'), function ($query) use ($request) {
            $q = $request->q;
            $query->where(function ($query) use ($q) {
                $query->where('nom', 'like', "%{$q}%")
                    ->orWhere('prenom', 'like', "%{$q}%")
                    ->orWhere('matricule', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        })
        ->when($request->filled('role'), function ($query) use ($request) {
            $query->where('role', $request->role);
        })
        ->when($request->filled('fonction_id'), function ($query) use ($request) {
            $query->where('fonction_id', $request->fonction_id);
        })
        ->orderBy('nom')
        ->paginate(20)
        ->withQueryString();

    $roles = [
        'user' => 'Agent',
        'chef_de_service' => 'Chef de service',
        'sous_directeur' => 'Sous-directeur',
        'gestionnaire_parc' => 'Gestionnaire de parc',
        'admin' => 'Admin',
        'technicien' => 'Technicien',
        'directeur' => 'Directeur',
        'ministre' => 'Ministre',
        'point_focal' => 'Point focal',
        'service_gestionnaire' => 'Service gestionnaire',
    ];

    return view('Admin.tableau_utilisateurs', compact(
        'statistiques',
        'licences',
        'fonctions',
        'informaticiens',
        'usersPage',
        'roles'
    ) + $userStats);
}

    
    public function showUser_equipes()
    {
        $userStats = $this->getUserStats();
        $statistiques = Equipement::all();
        $licences = Licence::all();
        $fonctions = Fonction::where('direction_id', Auth::user()->direction_id)->get();
        $informaticiens = User::whereHas('fonction', function ($q) {
            $q->where('fonction', 'informaticien');
        })->get();

        return view('Admin.user_equipe', array_merge($userStats, compact('statistiques', 'licences', 'fonctions', 'informaticiens')));
    }
    
    public function showUser_non_equipes()
    {
        $userStats = $this->getUserStats();
        $statistiques = Equipement::all();
        $licences = Licence::all();
        $fonctions = Fonction::where('direction_id', Auth::user()->direction_id)->get();
        $informaticiens = User::whereHas('fonction', function ($q) {
            $q->where('fonction', 'informaticien');
        })->get();

        return view('Admin.user_non_equipe', array_merge($userStats, compact('statistiques', 'licences', 'fonctions', 'informaticiens')));
    }

  public function ExportUsers_listPdf(Request $request) {
    $users = $this->getUsersByRole()->get();

    $pdf = \PDF::loadView('Admin.export_users_list', compact('users'));
    $pdf->setPaper('A4', 'landscape'); 
    
    return $pdf->download('Admin.export_users_list.pdf');
}

    public function ExportUsers_listExcel(Request $request)
    {
        $users = $this->getUsersByRole()->get();
        return Excel::download(new UsersExport($users), 'users_list.xlsx');
    }
    
    public function downloadImportTemplate()
    {
        return Excel::download(new UserImportTemplateExport(), 'modele_import_utilisateurs.xlsx');
    }

    public function importUsers(Request $request)
    {
        $request->validate([
            'fichier' => 'required|mimes:xlsx,xls'
        ]);

        $user = Auth::user();
        $import = new UserImport($user->direction_id);

        try {
            Excel::import($import, $request->file('fichier'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'import : ' . $e->getMessage());
        }

        // SkipsOnFailure/SkipsOnError font que les lignes invalides sont ignorées individuellement
        // plutôt que d'annuler tout l'import : on doit donc rapporter explicitement ce qui a été
        // sauté, sinon un import partiel se présenterait à tort comme un succès complet.
        $skippedLines = collect($import->failures())->map(function ($failure) {
            return 'Ligne ' . $failure->row() . ' : ' . implode(' ', $failure->errors());
        })->concat(
            collect($import->errors())->map(function ($error) {
                return $error->getMessage();
            })
        );

        $roleFallbacks = collect($import->roleFallbacks());

        $this->logAction(
            'import',
            "Importation d'utilisateurs" . ($skippedLines->isNotEmpty() ? " ({$skippedLines->count()} ligne(s) ignorée(s))" : '')
        );

        $notices = [];
        if ($skippedLines->isNotEmpty()) {
            $notices[] = "{$skippedLines->count()} ligne(s) ignorée(s) : " . $skippedLines->implode(' | ');
        }
        if ($roleFallbacks->isNotEmpty()) {
            $notices[] = "{$roleFallbacks->count()} rôle(s) non reconnu(s) (« user » appliqué par défaut) : " . $roleFallbacks->implode(' | ');
        }

        if (!empty($notices)) {
            return redirect()->back()->with('warning', 'Import terminé. ' . implode(' — ', $notices));
        }

        return redirect()->back()->with('success', 'Utilisateurs importés avec succès.');
    }

    public function create()
    {
        $fonctions = Fonction::where('direction_id', Auth::user()->direction_id)->get();
        return view('Users.create', compact('fonctions'));
    }

    public function saveAdd_user(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'matricule' => 'required|string|unique:users,matricule',
            'email' => 'required|string|email|unique:users,email',
            'contact' => 'required|string',
            'emploie' => 'required|string',
            'fonction_id' => 'required|string',
            'grade' => 'required|string',
            'date_prise_service_un' => 'nullable|string',
            'date_prise_service' => 'nullable|string',
            'role' => 'required|in:user,chef_de_service,admin,sous_directeur,gestionnaire_parc,technicien,directeur,ministre,point_focal,service_gestionnaire',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        try {
            $admin = Auth::user();
    
            // Sécurité : vérifier que l'admin a bien une direction
            if (is_null($admin->direction_id)) {
                return redirect()->back()
                    ->with('error', 'Votre compte n\'est pas associé à une direction. Impossible de créer un utilisateur.')
                    ->withInput();
            }
    
            // Création de l'utilisateur dans la même direction que l'admin
            $user = User::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'matricule' => $request->matricule,
                'email' => $request->email,
                'contact' => $request->contact,
                'emploie' => $request->emploie,
                'fonction_id' => $request->fonction_id,
                'grade' => $request->grade,
                'date_prise_service_un' => $request->date_prise_service_un,
                'date_prise_service' => $request->date_prise_service,
                'role' => $request->role,
                'direction_id' => $admin->direction_id, 
                'password' => Hash::make('12345678'),
            ]);

            $this->logAction(
                'ajouter',
                "Ajout d'un utilisateur",
                targetUserId: $user->id
            );
            return redirect()->back()->with('success', 'Utilisateur créé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage())
                ->withInput();
        }
    }
    

    public function userDelete($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect('/userlist')->with('error', 'Utilisateur introuvable.');
        }
        if ($user->role === 'admin') {
            return redirect('/userlist')->with('error', 'Impossible de supprimer un admin.');
        }
        if (!$this->canManageUser($user)) {
            return redirect('/userlist')->with('error', "Vous ne pouvez pas gérer un utilisateur d'une autre direction.");
        }

        // Log AVANT suppression : une fois l'utilisateur supprimé, la vérification
        // d'existence de logAction() mettrait target_user_id à null.
        $this->logAction(
            'supprimer',
            "Suppression d'un utilisateur : {$user->nom} {$user->prenom}",
            targetUserId: $user->id
        );
        $user->delete();
        return redirect('/userlist')->with('message', 'L\'utilisateur a bien été supprimé !');
    }

    // Un admin ou un directeur ne gère que les utilisateurs de sa propre direction ; le superadmin gère tout le monde.
    private function canManageUser(User $target): bool
    {
        $current = Auth::user();
        return $current->role === 'superadmin' || $target->direction_id === $current->direction_id;
    }

    private function getUsersByRole()
    {
        $currentUser = auth()->user();
    
        if ($currentUser->role === 'superadmin') {
            return User::query();
        } elseif (in_array($currentUser->role, ['admin', 'directeur'], true)) {
            return User::where('direction_id', $currentUser->direction_id);
        } else {
            return User::where('id', $currentUser->id);
        }
    }
    public function search(Request $request)
{
    $q = $request->q;

    return $this->getUsersByRole()
        ->where(function ($query) use ($q) {
            $query->where('nom', 'LIKE', "%$q%")
                ->orWhere('prenom', 'LIKE', "%$q%")
                ->orWhere('matricule', 'LIKE', "%$q%");
        })
        ->limit(20)
        ->get();
}

private function getUserStats()
{
    $usersQuery = $this->getUsersByRole()
        ->where('role', '!=', 'superadmin'); 

    return [
        'users' => (clone $usersQuery)->get(),
        'login_users' => (clone $usersQuery)->whereNotNull('password_changed_at')->get(),
        'users_equipes' => (clone $usersQuery)->whereHas('assignments', function ($query) {
            $query->whereNull('returned_at');
        })->get(),
        'users_non_equipes' => (clone $usersQuery)->whereDoesntHave('assignments', function ($query) {
            $query->whereNull('returned_at');
        })->get(),
    ];
}
    
    public function showLoggedUsers()
    {
        $userStats = $this->getUserStats();
        $statistiques = Equipement::all();
        $licences = Licence::all();
    
        return view('Admin.user_connecter', array_merge($userStats, compact('statistiques', 'licences')));
    }
    
    

    public function showUpdateUserForm($id)
    {
        $users = User::find($id);
        if (!$users) {
            return redirect('/userlist')->with('error', 'Utilisateur introuvable.');
        }
        if (!$this->canManageUser($users)) {
            return redirect('/userlist')->with('error', "Vous ne pouvez pas gérer un utilisateur d'une autre direction.");
        }
        $fonctions = Fonction::where('direction_id', Auth::user()->direction_id)->get();
        return view('Users.update_userList', compact('users','fonctions'));
    }

    public function save_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'matricule' => 'required|string|unique:users,matricule,' . $request->id,
            'email' => 'required|string|email|unique:users,email,' . $request->id,
            'contact' => 'required|string',
            'emploie' => 'required|string',
            'fonction_id' => 'required|string',
            'grade' => 'required|string',
            'date_prise_service_un' => 'nullable|string',
            'date_prise_service' => 'nullable|string',

            'role' => 'nullable|in:user,chef_de_service,admin,sous_directeur,gestionnaire_parc,technicien,directeur,ministre,point_focal,service_gestionnaire',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $users = User::find($request->id);
        if (!$users) {
            return redirect()->back()->with('error', 'Utilisateur non trouvé');
        }
        if (!$this->canManageUser($users)) {
            return redirect('/userlist')->with('error', "Vous ne pouvez pas gérer un utilisateur d'une autre direction.");
        }

        $users->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'matricule' => $request->matricule,
            'email' => $request->email,
            'contact' => $request->contact,
            'emploie' => $request->emploie,
            'fonction_id' => $request->fonction_id,
            'grade' => $request->grade,
            'date_prise_service_un' => $request->date_prise_service_un,
            'date_prise_service' => $request->date_prise_service,
            'role' => $request->role ?? 'user',
        ]);

        $this->logAction(
            'modification',
            "Modification d'un utilisateur",
            targetUserId: $request->id ?? null
        );
        return redirect('/userlist')->with('status', 'Utilisateur modifié avec succès !');
    }

    public function edit()
    {
        $user = Auth::user();
        return view('Users.editProfile', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'matricule' => 'nullable|string|unique:users,matricule,' . Auth::id(),
            'email' => 'nullable|email|unique:users,email,' . Auth::id(),
            'password' => 'nullable|min:8|confirmed',
            'contact' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();
        $user->matricule = $request->matricule;
        $user->email = $request->email;
        $user->contact = $request->contact;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        $this->logAction(
            'modification',
            "Modification de votre profil",
            targetUserId: Auth::id()
        );

        return redirect()->back()->with('success', 'Profil mis à jour avec succès !');
    }

}

