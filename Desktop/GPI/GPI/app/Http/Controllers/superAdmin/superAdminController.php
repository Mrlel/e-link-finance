<?php

namespace App\Http\Controllers\superAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Direction;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Traits\LogsModifications;
use App\Notifications\PasswordResetNotification;

class superAdminController extends Controller
{
    use LogsModifications;

    // Rôles assignables via ce contrôleur. 'superadmin' est volontairement exclu :
    // un compte superadmin ne doit pas pouvoir être créé/obtenu via ce formulaire.
    private const ASSIGNABLE_ROLES = [
        'user', 'chef_de_service', 'admin', 'sous_directeur', 'gestionnaire_parc',
        'technicien', 'directeur', 'ministre', 'point_focal', 'service_gestionnaire',
    ];

    public function listAdmins(Request $request)
    {
        Carbon::setLocale('fr');
        $dateToday = Carbon::now();
        $directions = Direction::all();

        // Eager loading pour éviter les N+1 sur $admin->direction->nom_direction
        $admins = User::with('direction')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = $request->q;
                $query->where(function ($query) use ($term) {
                    $query->where('nom', 'like', "%{$term}%")
                        ->orWhere('prenom', 'like', "%{$term}%")
                        ->orWhere('matricule', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%");
                });
            })
            ->when($request->filled('role'), function ($query) use ($request) {
                $query->where('role', $request->role);
            })
            ->when($request->filled('direction_id'), function ($query) use ($request) {
                $query->where('direction_id', $request->direction_id);
            })
            ->orderBy('nom')
            ->paginate(20)
            ->withQueryString();

        $roles = [
            'superadmin'           => 'Super Admin',
            'admin'                => 'Administrateur',
            'directeur'            => 'Directeur',
            'sous_directeur'       => 'Sous-directeur',
            'gestionnaire_parc'    => 'Gestionnaire de parc',
            'chef_de_service'      => 'Chef de service',
            'technicien'           => 'Technicien',
            'point_focal'          => 'Point focal',
            'service_gestionnaire' => 'Service gestionnaire',
            'user'                 => 'Agent',
        ];

        $roleBadges = [
            'superadmin'        => ['label' => 'Super Admin', 'class' => 'bg-danger'],
            'admin'             => ['label' => 'Administrateur', 'class' => 'bg-primary'],
            'directeur'         => ['label' => 'Directeur', 'class' => 'bg-info'],
            'gestionnaire_parc' => ['label' => 'Gest. Parc', 'class' => 'bg-warning text-dark'],
            'chef_de_service'   => ['label' => 'Chef Service', 'class' => 'bg-secondary'],
            'technicien'        => ['label' => 'Technicien', 'class' => 'bg-secondary'],
            'user'              => ['label' => 'Agent', 'class' => 'bg-light text-dark border'],
        ];

        return view('SuperAdmin.Administrateurs.list-admin', compact('directions', 'admins', 'dateToday', 'roles', 'roleBadges'));
    }

    public function createAdmin()
    {
        Carbon::setLocale('fr');
        $dateToday  = Carbon::now();
        $directions = Direction::all();
        return view('SuperAdmin.Administrateurs.create', compact('dateToday', 'directions'));
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'nom'          => 'required|string|max:255',
            'prenom'       => 'nullable|string|max:255',
            'matricule'    => 'required|string|unique:users,matricule',
            'email'        => 'required|string|email|unique:users,email',
            'contact'      => 'nullable|string',
            'emploie'      => 'nullable|string',
            'grade'        => 'nullable|string',
            'role'         => 'nullable|in:' . implode(',', self::ASSIGNABLE_ROLES),
            'direction_id' => 'required|exists:directions,id',
        ]);

        User::create([
            'nom'          => $request->nom,
            'prenom'       => $request->prenom,
            'matricule'    => $request->matricule,
            'email'        => $request->email,
            'contact'      => $request->contact,
            'emploie'      => $request->emploie,
            'grade'        => $request->grade,
            'role'         => $request->role ?? 'user',
            'password'     => Hash::make('12345678'),
            'direction_id' => $request->direction_id,
        ]);

        return redirect()->route('directions.list-admin')->with('success', 'Utilisateur créé avec succès. Mot de passe par défaut : 12345678');
    }

    public function editAdmin($id)
    {
        $admin      = User::findOrFail($id);
        $directions = Direction::all();
        $dateToday  = Carbon::now();
        return view('SuperAdmin.Administrateurs.edit', compact('admin', 'directions', 'dateToday'));
    }

    public function updateAdmin(Request $request, $id)
    {
        $request->validate([
            'nom'          => 'required|string|max:255',
            'prenom'       => 'nullable|string|max:255',
            'matricule'    => 'required|string|unique:users,matricule,' . $id,
            'email'        => 'required|string|email|unique:users,email,' . $id,
            'contact'      => 'nullable|string',
            'emploie'      => 'nullable|string',
            'grade'        => 'nullable|string',
            'role'         => 'nullable|in:' . implode(',', self::ASSIGNABLE_ROLES),
            'direction_id' => 'required|exists:directions,id',
        ]);

        $admin = User::findOrFail($id);

        if ($admin->role === 'superadmin') {
            return back()->with('error', 'Impossible de modifier un super administrateur.');
        }

        $admin->update([
            'nom'          => $request->nom,
            'prenom'       => $request->prenom,
            'matricule'    => $request->matricule,
            'email'        => $request->email,
            'contact'      => $request->contact,
            'emploie'      => $request->emploie,
            'grade'        => $request->grade,
            'role'         => $request->role,
            'direction_id' => $request->direction_id,
        ]);

        return redirect()->route('directions.list-admin')->with('success', 'Utilisateur modifié avec succès.');
    }

    public function destroyAdmin($id)
    {
        $admin = User::findOrFail($id);
        if ($admin->role === 'superadmin') {
            return redirect()->back()->with('error', 'Impossible de supprimer un super administrateur.');
        }
        $admin->delete();
        return redirect()->back()->with('success', 'Utilisateur supprimé avec succès.');
    }

    public function showAdmin($id)
    {
        $admin = User::findOrFail($id);
        return view('SuperAdmin.Administrateurs.show', compact('admin'));
    }

    /**
     * Un compte ne peut réinitialiser/modifier le mot de passe que :
     * - s'il est superadmin (portée globale), ou
     * - s'il gère la même direction que la cible (portée locale), et n'est pas lui-même un simple 'user'.
     * Dans tous les cas, la cible ne peut pas être un superadmin.
     */
    private function authorizePasswordAction(User $actor, User $target): void
    {
        if ($target->role === 'superadmin') {
            abort(403, 'Impossible de modifier ce mot de passe.');
        }

        if ($actor->role === 'superadmin') {
            return;
        }

        if ($actor->role === 'user' || $actor->direction_id !== $target->direction_id) {
            abort(403, 'Accès non autorisé.');
        }
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = User::findOrFail($id);
        $this->authorizePasswordAction($request->user(), $user);

        $user->password = Hash::make($request->new_password);
        $user->password_changed_at = null;
        $user->save();

        return back()->with('success', 'Mot de passe mis à jour avec succès.');
    }

    public function updatePasswordAdmin($id)
    {
        $user = User::findOrFail($id);
        return view('Admin.update-password-admin', compact('user'));
    }

    public function resetPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $this->authorizePasswordAction($request->user(), $user);

        $newPassword = Str::password(12);

        $user->password            = Hash::make($newPassword);
        $user->password_changed_at = null;
        $user->save();

        $this->logAction(
            'reset-password',
            "Réinitialisation du mot de passe de l'utilisateur {$user->nom} {$user->prenom}",
            targetUserId: $user->id
        );

        try {
            $user->notify(new PasswordResetNotification($newPassword));

            return redirect()->back()->with(
                'success',
                "Mot de passe réinitialisé avec succès. Le nouveau mot de passe a été envoyé par e-mail à {$user->email}."
            );
        } catch (\Throwable $e) {
            report($e);

            return redirect()->back()->with(
                'warning',
                "Mot de passe réinitialisé, mais l'e-mail n'a pas pu être envoyé à {$user->email}. Mot de passe temporaire à communiquer manuellement : {$newPassword}"
            );
        }
    }
}
