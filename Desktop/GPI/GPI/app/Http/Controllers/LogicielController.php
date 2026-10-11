<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Equipement;
use App\Models\Licence;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel; 
use App\Imports\LicenceImport;
use App\Models\AssignerLogiciel;
use App\Traits\LogsModifications;
use App\Exports\LogicielsExport;

class LogicielController extends Controller
{
    use LogsModifications;

    /*public function show_details($id)
    {
        $statistique = Licence::with('fournisseur', 'attributions.user')->findOrFail($id);
        return view('', compact(''));
    }*/
    
    public function exportLogicielsExcel()
    {
        $logiciels = Licence::where('direction_id', Auth::user()->direction_id)->get();
        return Excel::download(new LogicielsExport($logiciels), 'logiciels_list.xlsx');
    }

    public function exportLogicielsPdf()
    {
        $logiciels = Licence::where('direction_id', Auth::user()->direction_id)->get();
        $pdf = \PDF::loadView('pdf.export_logiciels_list', compact('logiciels'));
        $pdf->setPaper('A4', 'landscape'); 
        return $pdf->download('logiciels_list.pdf');
    }

    public function importLogiciels(Request $request)
    {
        $request->validate([
            'fichier' => 'required|mimes:xlsx,xls'
        ]);

        $user = Auth::user();
        
        try {
            Excel::import(
                new LicenceImport($user->direction_id),
                $request->file('fichier')
            );

            $this->logModification('import', "Importation de logiciels via fichier Excel");

            return redirect()->back()->with('success', 'Logiciels importés avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'import : ' . $e->getMessage());
        }
    }
    public function create()
    {
        return view('Admin.logiciel_create');
    }

    public function saveLogiciel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'designation_licence' => 'required|string|max:255',
            'type_licence' => 'required|string',
            'date_expiration' => 'nullable|date',
            'cle_licence' => 'nullable|string',
            'environnement' => 'required|string',
            'langage_version' => 'required|string',
            'sgbd_version' => 'required|string',
            'base_donnees' => 'nullable|string',
            'fichier_licence' => 'nullable|file|mimes:sql,sqlite,db,zip,tar,txt|max:20480', // base de données
            'fichier_app' => 'nullable|file|mimes:zip,rar,exe,tar,7z,doc,docx,pdf,txt,apk,msi,sh,bat,jar,py,php,js,html,css|max:20480',
            'libelle_licence' => 'required|string',

        ]);
    
        // Sauvegarde des fichiers
        $fichierAppPath = null;
        $fichierLicencePath = null;
    
        if ($request->hasFile('fichier_app')) {
            $fichierAppPath = $request->file('fichier_app')->store('fichiers_app', 'public');
        }
    
        if ($request->hasFile('fichier_licence')) {
            $fichierLicencePath = $request->file('fichier_licence')->store('bases_donnees', 'public');
        }
    
        // Création du logiciel
        $Logiciels = new Licence();
        $Logiciels->designation_licence = $request->designation_licence;
        $Logiciels->type_licence = $request->type_licence;
        $Logiciels->date_expiration = $request->date_expiration;
        $Logiciels->cle_licence = $request->cle_licence;
        $Logiciels->environnement = $request->environnement;
        $Logiciels->langage_version = $request->langage_version;
        $Logiciels->sgbd_version = $request->sgbd_version;
        $Logiciels->base_donnees = $request->base_donnees;
        $Logiciels->fichier_licence = $fichierLicencePath;
        $Logiciels->fichier_app = $fichierAppPath;
        $Logiciels->libelle_licence = $request->libelle_licence;
                
                $Logiciels->direction_id = Auth::user()->direction_id;

                $Logiciels->save();

        $this->logAction('ajout', "Ajout du logiciel {$Logiciels->libelle_licence}", licenceId: $Logiciels->id);

        return redirect('/list_logiciel')->with('status', 'Logiciel ajouté avec succès !');
    }

    public function showList_logiciel()
    {
            $now = Carbon::now();
            $limitDate = $now->copy()->addDays(10);
        
            $directionId = Auth::user()->direction_id; 
            
            $attributions = AssignerLogiciel::where('direction_id', auth()->user()->direction_id)
                ->with(['equipement', 'licence', 'assignedBy'])
                ->get();
            $equipements = Equipement::where('direction_id', auth()->user()->direction_id)
                ->whereIn('categorie', ['Ordinateur portable', 'Ordinateur All-in-one', 'unite centrale'])
                ->get();           
            $licences = Licence::where('direction_id', auth()->user()->direction_id)
            ->orderBy('designation_licence', 'asc')
            ->get();
            $users = User::where('direction_id', auth()->user()->direction_id)->get();
            $licencesExpirees = Licence::where('direction_id', $directionId)
                ->where('date_expiration', '<', $now)
                ->get();
            $licencesBientotExpirees = Licence::where('direction_id', $directionId)
                ->whereBetween('date_expiration', [$now, $limitDate])
                ->get();
            return view('Admin.tableau_logiciels', compact('licences', 'licencesExpirees', 'licencesBientotExpirees', 'equipements', 'users'));
        }
    
    
    public function logiciel_expirer()
    {  $now = Carbon::now();
    $limitDate = $now->copy()->addDays(10);
    
    $directionId = Auth::user()->direction_id; 
    
    $attributions = AssignerLogiciel::where('direction_id', auth()->user()->direction_id)
        ->with(['equipement', 'licence', 'assignedBy'])
        ->get();
    
    $equipements = Equipement::where('direction_id', auth()->user()->direction_id)
        ->whereIn('categorie', ['Ordinateur portable', 'Ordinateur All-in-one', 'unite centrale'])
        ->get();           
    
    // Modification ici pour trier par nom
    $licences = Licence::where('direction_id', auth()->user()->direction_id)
        ->orderBy('designation_licence', 'asc')
        ->get();
    
    $users = User::where('direction_id', auth()->user()->direction_id)->get();
    
    $licencesExpirees = Licence::where('direction_id', $directionId)
        ->where('date_expiration', '<', $now)
        ->orderBy('designation_licence', 'asc')
        ->get();
    
    $licencesBientotExpirees = Licence::where('direction_id', $directionId)
        ->whereBetween('date_expiration', [$now, $limitDate])
        ->orderBy('designation_licence', 'asc')
        ->get();
        if ($licences->isEmpty() && $licencesExpirees->isEmpty() && $licencesBientotExpirees->isEmpty()) {
            return redirect()->back()->with('info', 'Aucune licence concernée.');
        }
    
        return view('Admin.logiciel_expirer', compact('licences', 'licencesExpirees', 'licencesBientotExpirees', 'equipements', 'users'));
    }
    public function logiciel_bientot_expirer()
    {
        $now = Carbon::now();
        $limitDate = $now->copy()->addDays(10);
    
        $directionId = Auth::user()->direction_id;
    
        $licencesExpirees = Licence::where('direction_id', $directionId)
                                    ->where('date_expiration', '<', $now)
                                    ->get();
        $licencesBientotExpirees = Licence::where('direction_id', $directionId)
                                          ->whereBetween('date_expiration', [$now, $limitDate])
                                          ->get();
        $licences = Licence::where('direction_id', $directionId)
        ->orderBy('designation_licence', 'asc')
        ->get();
        $equipements = Equipement::where('direction_id', $directionId)->get();
        $users = User::where('direction_id', $directionId)->get();
    
        if ($licences->isEmpty() && $licencesExpirees->isEmpty() && $licencesBientotExpirees->isEmpty()) {
            return redirect()->back()->with('info', 'Aucune licence concernée.');
        }
    
        return view('Admin.logiciel_bientot_expirer', compact('licences', 'licencesExpirees', 'licencesBientotExpirees', 'equipements', 'users'));
    }
    

    public function delete_logiciel($id){
        $licences = Licence::find($id);
        if (!$licences) {
            return redirect('/list_logiciel')->with('error', 'Logiciel introuvable.');
        }
        $this->logAction('suppression', "Suppression du logiciel {$licences->libelle_licence}", licenceId: $licences->id);
        $licences->delete();
        return redirect('/list_logiciel')->with('status','Logiciel supprimer avec succès !');
    }
    public function modifier_logiciel($id){
        $licences = Licence::find($id);
        if (!$licences) {
            return redirect('/list_logiciel')->with('error', 'Logiciel introuvable.');
        }
        return view('Admin.modifier_logiciel', compact('licences'));
    }
    public function update_logiciel(Request $request, $id){
        $request->validate([
            'base_donnees' => 'nullable',
            'fichier_licence' => 'nullable',
            'libelle_licence' => 'required|string|max:255',
            'designation_licence' => 'required|string|max:255',
            'type_licence' => 'nullable|string|max:255',
            'date_expiration' => 'nullable|date',
            'cle_licence' => 'nullable|string|max:255',
            'environnement' => 'nullable|string|max:255',
            'langage_version' => 'nullable|string|max:255',
            'sgbd_version' => 'nullable|string|max:255',
        ]);
        $licences = Licence::find($id);
        if (!$licences) {
            return redirect('/list_logiciel')->with('error', 'Logiciel introuvable.');
        }
        $licences->designation_licence = $request->designation_licence;
        $licences->type_licence = $request->type_licence;
        $licences->date_expiration = $request->date_expiration;
        $licences->cle_licence = $request->cle_licence;
        $licences->environnement = $request->environnement;
        $licences->langage_version = $request->langage_version;
        $licences->sgbd_version = $request->sgbd_version;
        $licences->base_donnees = $request->base_donnees;
        // fichier_licence n'est pas modifiable depuis ce formulaire (pas de champ fichier ici,
        // cf. gestion des fichiers non gérée correctement par cette méthode) : on préserve la
        // valeur existante plutôt que de l'écraser par null si le champ est absent de la requête.
        if ($request->has('fichier_licence')) {
            $licences->fichier_licence = $request->fichier_licence;
        }
        $licences->libelle_licence = $request->libelle_licence;
        $licences->save();
        $this->logAction('modification', "Modification du logiciel {$licences->libelle_licence}", licenceId: $licences->id);
        return redirect('/list_logiciel')->with('status','Logiciel modifier avec succès !');
    }
    public function showLogiciel_details($id){
        $logiciel = Licence::find($id);
        return view('Admin.details_logiciel', compact('logiciel'));
    }
}