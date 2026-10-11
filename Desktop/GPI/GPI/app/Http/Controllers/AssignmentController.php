<?php

    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use App\Models\User;
    use App\Models\Equipement;
    use App\Models\Assignment;
    use App\Models\Licence;
    use Carbon\Carbon;
    use App\Models\DemandeMaintenance;
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
    use App\Notifications\EquipementAssignedNotification;
    use Illuminate\Support\Facades\DB;
    use App\Notifications\EquipementReturnedNotification;
    use Illuminate\Support\Facades\Log;
    use App\Models\Direction;
    use App\Models\AssignerLogiciel;
    use App\Traits\LogsModifications;
    use App\Models\Documents;


    class AssignmentController extends Controller
    {
        use LogsModifications;
    public function allEquipementsUser($id)
    {
        $user = User::where('direction_id', auth()->user()->direction_id)->findOrFail($id);

        // On récupère tous les assignments (actuels et passés) de l'utilisateur avec les détails équipements
        $assignments = Assignment::where('user_id', $id)
            ->with(['equipement', 'assignedBy', 'returnedBy', 'direction'])
            ->orderByDesc('assigned_at')
            ->get();

        return view('Users.all_equipements_user', compact('user', 'assignments'));
    }
        public function showEquipementsUser($id)
        {
            $user = User::where('direction_id', auth()->user()->direction_id)->with(['assignments' => function ($query) {
                $query->whereNull('returned_at')->with('equipement');
            }])->findOrFail($id);
            $equipements = $user->assignments->map(fn($assignment) => $assignment->equipement)->filter();
            $assignments = Assignment::where('user_id', $id)->whereNull('returned_at')->get();
            $demande_maintenances = DemandeMaintenance::where('user_id', $id)->get();
            return view('Users.usersEquipement', compact('user', 'equipements', 'assignments', 'demande_maintenances'));
        } 
        
        public function showEquipementsAdmin($id)
        {
            $user = User::where('direction_id', auth()->user()->direction_id)->with(['assignments' => function ($query) {
                $query->whereNull('returned_at')->with('equipement');
            }])->findOrFail($id);
            $equipements = $user->assignments->map(fn($assignment) => $assignment->equipement)->filter();
            $assignments = Assignment::where('user_id', $id)->whereNull('returned_at')->get();
            $demande_maintenances = DemandeMaintenance::where('user_id', $id)->get();
            return view('Admin.adminEquipements.index', compact('user', 'equipements', 'assignments', 'demande_maintenances'));
        } 

       public function equipement_details($id)
{
    $documents = Documents::where('equipement_id', $id)->get();
    $equipement = Equipement::with(['assignments.user', 'sorties.demandeur', 'sortieActive'])
        ->where('direction_id', auth()->user()->direction_id)
        ->findOrFail($id);
    $logiciels = AssignerLogiciel::where('equipement_id', $id)->get();

    $qrCode = QrCode::size(100)->generate(route('equipement.details', $id));

    return view('Patrimoine.equipements.details_Materiel', compact(
        'equipement', 'qrCode', 'logiciels', 'documents'
    ));
}


public function user_details($id)
{
    // Récupérer l'utilisateur avec ses équipements Assignés
    $user = User::with('assignments.equipement')
        ->where('direction_id', auth()->user()->direction_id)
        ->findOrFail($id);

    // URL complète automatique (localhost ou production)
    $url = route('user.details', $id);

    // QR Code
    $qrCode = QrCode::size(100)->generate($url);

    return view('Users.details_user', compact('user', 'qrCode'));
}


        public function index(Request $request)
        {
            $directionId = auth()->user()->direction_id;

            $assignments = Assignment::where('direction_id', $directionId)
                ->with(['user', 'equipement', 'assignedBy'])
                ->when($request->filled('q'), function ($assignmentQuery) use ($request) {
                    $term = $request->q;
                    $assignmentQuery->where(function ($assignmentQuery) use ($term) {
                        $assignmentQuery->whereHas('user', function ($userQuery) use ($term) {
                                $userQuery->where('nom', 'like', "%{$term}%")
                                    ->orWhere('prenom', 'like', "%{$term}%");
                            })
                            ->orWhereHas('equipement', function ($equipementQuery) use ($term) {
                                $equipementQuery->where('des_equipement', 'like', "%{$term}%")
                                    ->orWhere('numero_serie', 'like', "%{$term}%");
                            });
                    });
                })
                ->orderByRaw('returned_at IS NOT NULL, returned_at ASC')
                ->paginate(20)
                ->withQueryString();

            $equipements = Equipement::where('direction_id', $directionId)->where('statut', 'en stock')->get();

            $licences = Licence::where('direction_id', $directionId)->get();
            $users = User::where('direction_id', $directionId)->get();
            return view('Admin.list_historique_attribution',compact('assignments','equipements','users','licences'));
        }
       /* public function create()
        {
            $users = User::all();
            $equipements = Equipement::all();
            return view('Materiels.assignmentCreate', compact('users', 'equipements'));
        }*/
    
        public function confirmerReception(Request $request, $equipementId)
        {
            $request->validate([
                'confirmed' => 'required|boolean', 
            ]);

            $assignment = Assignment::where('equipement_id', $equipementId)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            $assignment->update([
                'confirmed' => $request->confirmed,
            ]);

            return redirect()->back()->with('status', 'Confirmation de réception mise à jour avec succès.');
        }

        public function Page_assigner(){
            
            $equipements = Equipement::where('direction_id', auth()->user()->direction_id)->where('statut', 'en stock')->get();
            $users = User::where('direction_id', auth()->user()->direction_id)->get();
            return view('Patrimoine.equipements.page_assigner', compact('equipements', 'users'));
        }

        public function assignerEquipement(Request $request)
        {
                $data = $request->validate([
                    'user_id' => 'required|exists:users,id',
                    'equipement_id' => 'required|exists:equipements,id',
                ]);

                $equipement = Equipement::findOrFail($data['equipement_id']);
                $user = User::findOrFail($data['user_id']);

                if ($equipement->statut === 'en service') {
                    return redirect()->back()->with('error', 'Cet équipement est déjà en service.');
                }

                $assignment = Assignment::create([
                    'user_id' => $user->id,
                    'equipement_id' => $equipement->id,
                    'assigned_by' => auth()->id(),
                    'assigned_at' => now(),
                    'direction_id' => $user->direction_id,
                ]);

                $equipement->update(['statut' => 'en service']);

                
                $this->logModification(
                    'attribution',
                    "Attribution de l'équipement {$equipement->des_equipement} à l'utilisateur {$user->nom}",
                    $equipement->id,
                    $user->direction_id
                );
                $assignedByUser = auth()->user();
                 $user->notify(new EquipementAssignedNotification($equipement, $assignedByUser));

                return redirect()->back()->with('status', 'Équipement attribué avec succès!');
        }

        // Méthode pour traiter le retour d'un équipement
        
public function retournerEquipement(Request $request, $assignmentId)
{
    $request->validate([
        'commentaire_retour' => 'nullable|string|max:255',
        'etat_retour' => 'required|string|max:50',
    ]);

    try {
        DB::beginTransaction();

        $assignment = Assignment::where('direction_id', auth()->user()->direction_id)->findOrFail($assignmentId);

        // Vérification de l'équipement avant mise à jour
        if (!$assignment->equipement) {
            throw new \Exception("Équipement introuvable pour l'assignation ID: $assignmentId");
        }

        // Mise à jour de l'assignment
        $assignment->update([
            'returned_at' => now(),
            'commentaire_retour' => $request->commentaire_retour,
            'etat_retour' => $request->etat_retour,
            'returned_by' => auth()->id(),
            'direction_id' => auth()->user()->direction_id,
        ]);

        // Mise à jour de l'état de l'équipement
        $assignment->equipement->update([
            'statut' => 'en stock',
        ]);

        $this->logModification(
            'retour',
            "Retour de l'équipement {$assignment->equipement->des_equipemrnt} par l'utilisateur {$assignment->user->nom}. État: {$request->etat_retour}",
            $assignment->equipement->id,
            auth()->user()->direction_id
        );
        // Vérification de l'utilisateur avant envoi de la notification
        if ($assignment->user) {
            $assignment->user->notify(new EquipementReturnedNotification($assignment));
        }

        DB::commit();

        return redirect()->back()->with('success', 'Équipement retourné avec succès !');

    } catch (\Exception $e) {
        DB::rollback();

        Log::error("Erreur lors du retour d'équipement:", [
            'error' => $e->getMessage(),
            'assignment_id' => $assignmentId,
            'stack_trace' => $e->getTraceAsString()
        ]);

        return redirect()->back()->with('error', 'Une erreur est survenue lors du retour de l\'équipement.');
    }
}

    
    }