<?php

namespace App\Http\Controllers\Direction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Direction;
use App\Models\Equipement;
use App\Models\User;
use App\Models\Licence;
use App\Models\DemandeMaintenance;
use App\Models\Assignment;
use App\Models\Vehicule;
use App\Models\Mobilier;
use App\Models\PatrimoineDivers;
use Illuminate\Support\Str;
use Carbon\Carbon;
use PDF;

class DirectionController extends Controller
{
    /**
     * Calcule les statistiques d'une direction.
     * Utilise clone() pour éviter la mutation du query builder.
     */
    private function buildStats(Direction $direction, Carbon $now, Carbon $limitDate): array
    {
        $equipementsBase = Equipement::where('direction_id', $direction->id);
        $usersBase       = User::where('direction_id', $direction->id);
        $licencesBase    = Licence::where('direction_id', $direction->id);

        $totalUsers = (clone $usersBase)->count();
        $equipes    = (clone $usersBase)->whereHas('assignments', function ($q) {
            $q->whereNull('returned_at');
        })->count();

        return [
            'direction' => $direction->nom_direction,
            'equipements' => [
                'total'          => (clone $equipementsBase)->count(),
                'en_stock'       => (clone $equipementsBase)->where('statut', 'en stock')->count(),
                'en_service'     => (clone $equipementsBase)->where('statut', 'en service')->count(),
                'en_maintenance' => (clone $equipementsBase)->where('statut', 'en maintenance')->count(),
                'hors_service'   => (clone $equipementsBase)->where('etat', 'hors service')->count(),
            ],
            'utilisateurs' => [
                'total'       => $totalUsers,
                'equipes'     => $equipes,
                'non_equipes' => $totalUsers - $equipes,
                'connectes'   => (clone $usersBase)->whereNotNull('password_changed_at')->count(),
            ],
            'logiciels' => [
                'total'          => (clone $licencesBase)->count(),
                'actifs'         => (clone $licencesBase)->where('date_expiration', '>', $now)->count(),
                'bientot_expires'=> (clone $licencesBase)->whereBetween('date_expiration', [$now, $limitDate])->count(),
                'expires'        => (clone $licencesBase)->where('date_expiration', '<', $now)->count(),
            ],
        ];
    }

    public function superadminDashboard()
    {
        if (auth()->user()->role !== 'superadmin') {
            abort(403, 'Accès non autorisé');
        }

        Carbon::setLocale('fr');
        $dateToday = Carbon::now();
        $now       = Carbon::now();
        $limitDate = $now->copy()->addDays(10);

        $directions          = Direction::all();
        $directionsActives   = Direction::where('statut', 'active')->count();
        $directionsInactives = Direction::where('statut', 'inactive')->count();
        $adminCount          = User::where('role', 'admin')->count();

        $stats = [];
        foreach ($directions as $direction) {
            $stats[$direction->id] = $this->buildStats($direction, $now, $limitDate);
        }

        return view('SuperAdmin.dashboard', compact(
            'directions', 'directionsActives', 'directionsInactives',
            'dateToday', 'adminCount', 'stats'
        ));
    }

    public function rapport(Request $request)
    {
        if (auth()->user()->role !== 'superadmin') {
            abort(403, 'Accès non autorisé');
        }

        Carbon::setLocale('fr');
        $dateToday = Carbon::now();
        $now       = Carbon::now();
        $limitDate = $now->copy()->addDays(10);

        $directions      = Direction::all();
        $directionId     = $request->input('direction_id');
        $filteredDirections = $directionId
            ? $directions->where('id', $directionId)
            : $directions;

        $stats = [];
        foreach ($filteredDirections as $direction) {
            $stats[$direction->id] = $this->buildStats($direction, $now, $limitDate);
        }

        return view('SuperAdmin.rapport', compact('stats', 'dateToday', 'directions'));
    }

    public function rapportPDF(Request $request, $directionId = null)
    {
        Carbon::setLocale('fr');
        $dateToday = Carbon::now();
        $now       = Carbon::now();
        $limitDate = $now->copy()->addDays(10);

        $query = Direction::query();
        if ($directionId) {
            $query->where('id', $directionId);
        } elseif ($request->has('direction_id')) {
            $query->where('id', $request->input('direction_id'));
        }

        $filteredDirections = $query->get();

        if ($filteredDirections->isEmpty()) {
            return redirect()->back()->with('error', 'Aucune direction trouvée.');
        }

        $stats = [];
        foreach ($filteredDirections as $direction) {
            $stats[$direction->id] = $this->buildStats($direction, $now, $limitDate);
        }

        $logoGpi  = public_path('logo-gpi.png');
        $logoDsid = public_path('dsid.jpg');

        $direction = $filteredDirections->first();
        $directionLogoPath = $direction && $direction->logo
            ? storage_path('app/public/' . $direction->logo)
            : public_path('default-direction-logo.png');

        $pdf = PDF::loadView('SuperAdmin.rapportPDF', compact(
            'stats', 'dateToday', 'logoGpi', 'logoDsid', 'direction', 'directionLogoPath'
        ));

        return $pdf->download('rapport_direction.pdf');
    }

    public function listDirections(Request $request)
    {
        Carbon::setLocale('fr');
        $dateToday           = Carbon::now();
        $directions          = Direction::withCount('users')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = $request->q;
                $query->where(function ($query) use ($term) {
                    $query->where('nom_direction', 'like', "%{$term}%")
                        ->orWhere('code_direction', 'like', "%{$term}%");
                });
            })
            ->when($request->filled('statut'), function ($query) use ($request) {
                $query->where('statut', $request->statut);
            })
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->orderBy('nom_direction')
            ->paginate(12)
            ->withQueryString();
        $directionsActives   = Direction::where('statut', 'active')->count();
        $directionsInactives = Direction::where('statut', 'inactive')->count();
        $directionsAdministrés = Direction::whereHas('admin')->count();
        $admins              = User::where('role', 'admin')->get();

        return view('SuperAdmin.Directions.list-direction', compact(
            'directions', 'directionsActives', 'directionsInactives',
            'dateToday', 'directionsAdministrés', 'admins'
        ));
    }

    public function create()
    {
        Carbon::setLocale('fr');
        $dateToday = Carbon::now();
        return view('SuperAdmin.Directions.create', compact('dateToday'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom_direction'  => 'required|unique:directions',
            'code_direction' => 'required|unique:directions',
            'type'           => 'nullable|in:centrale,déconcentrée,partenaire',
            'logo'           => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'web_url'        => 'nullable|url',
        ]);

        Direction::create([
            'nom_direction'  => $request->nom_direction,
            'slug'           => Str::slug($request->nom_direction),
            'code_direction' => $request->code_direction,
            'email_contact'  => $request->email_contact,
            'telephone'      => $request->telephone,
            'adresse'        => $request->adresse,
            'responsable'    => $request->responsable,
            'statut'         => $request->statut ?? 'active',
            'type'           => $request->type,
            'logo'           => $request->hasFile('logo') ? $request->file('logo')->store('logos', 'public') : null,
            'web_url'        => $request->web_url,
        ]);

        return redirect()->route('directions.list-direction')->with('success', 'Direction créée avec succès.');
    }

    public function edit($id)
    {
        $direction = Direction::findOrFail($id);
        $dateToday = Carbon::now();
        return view('SuperAdmin.Directions.edit', compact('direction', 'dateToday'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nom_direction'  => 'required|unique:directions,nom_direction,' . $id,
            'code_direction' => 'required|unique:directions,code_direction,' . $id,
            'type'           => 'nullable|in:centrale,déconcentrée,partenaire',
            'logo'           => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'web_url'        => 'nullable|url',
        ]);

        $direction = Direction::findOrFail($id);

        $data = [
            'nom_direction'  => $request->nom_direction,
            'slug'           => Str::slug($request->nom_direction),
            'code_direction' => $request->code_direction,
            'email_contact'  => $request->email_contact,
            'telephone'      => $request->telephone,
            'adresse'        => $request->adresse,
            'responsable'    => $request->responsable,
            'statut'         => $request->statut,
            'type'           => $request->type,
            'web_url'        => $request->web_url,
        ];

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $direction->update($data);

        return redirect()->route('directions.list-direction')->with('success', 'Direction mise à jour avec succès.');
    }

    public function deactivate($id)
    {
        $direction = Direction::findOrFail($id);
        $direction->update(['statut' => $direction->statut === 'active' ? 'inactive' : 'active']);
        return back()->with('success', 'Statut de la direction mis à jour.');
    }

    public function assignAdmin($id)
    {
        $direction = Direction::findOrFail($id);
        $admins    = User::where('role', 'admin')->whereNull('direction_id')->get();
        return view('SuperAdmin.Directions.assign-admin', compact('direction', 'admins'));
    }

    public function storeAssignAdmin(Request $request, $id)
    {
        $request->validate(['admin_id' => 'required|exists:users,id']);

        $direction = Direction::findOrFail($id);
        $admin     = User::findOrFail($request->admin_id);

        if ($admin->direction_id !== null) {
            return back()->with('error', 'Cet administrateur est déjà assigné à une direction.');
        }

        $admin->update(['direction_id' => $direction->id]);
        $direction->update(['admin_id' => $admin->id]);

        return back()->with('success', 'Administrateur assigné avec succès.');
    }

    public function destroy($id)
    {
        $direction = Direction::findOrFail($id);

        $nbEquipements = $direction->equipements()->count();
        $nbUsers       = $direction->users()->count();
        $nbVehicules   = Vehicule::where('direction_id', $direction->id)->count();
        $nbMobiliers   = Mobilier::where('direction_id', $direction->id)->count();
        $nbDivers      = PatrimoineDivers::where('direction_id', $direction->id)->count();

        if ($nbEquipements > 0 || $nbUsers > 0 || $nbVehicules > 0 || $nbMobiliers > 0 || $nbDivers > 0) {
            return back()->with('error', sprintf(
                "Impossible de supprimer la direction \"%s\" : elle contient encore %d équipement(s), %d véhicule(s), %d mobilier(s), %d article(s) de patrimoine divers et %d utilisateur(s). Réaffectez-les ou désactivez plutôt la direction.",
                $direction->nom_direction,
                $nbEquipements,
                $nbVehicules,
                $nbMobiliers,
                $nbDivers,
                $nbUsers
            ));
        }

        $direction->delete();
        return back()->with('success', 'Direction supprimée.');
    }
}
