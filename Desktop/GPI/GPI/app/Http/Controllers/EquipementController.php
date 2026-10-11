<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Equipement;
use App\Models\User;
use App\Models\Licence;
use App\Models\Assignment;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EquipementImport;
use App\Models\Poste;
use App\Models\Direction;
use App\Models\Assigner_logiciel;
use App\Traits\LogsModifications;




class EquipementController extends Controller
{
    use LogsModifications;

    public function create_equipement()
    {
        $equipements = Equipement::where('direction_id', Auth::user()->direction_id)->get();

        $postes = Poste::where('direction_id', Auth::user()->direction_id)->get();
        return view('Patrimoine.equipements.Ajouter', compact('equipements', 'postes'));
    }
    public function downloadImportTemplate()
    {
        return Excel::download(new \App\Exports\EquipementImportTemplateExport(), 'modele_import_equipements.xlsx');
    }

    public function importEquipement(Request $request)
    {
        $request->validate([
            'fichier' => 'required|mimes:xlsx,xls'
        ]);

        $user = Auth::user();
        $baseUrl = request()->getSchemeAndHttpHost();
        $import = new \App\Imports\EquipementImport($user->id, $user->direction_id, $baseUrl);

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
        $rowWarnings = collect($import->rowWarnings());

        $this->logModification(
            'import',
            "Importation d'équipements via fichier Excel" . ($skippedLines->isNotEmpty() ? " ({$skippedLines->count()} ligne(s) ignorée(s))" : ''),
            null
        );

        $notices = [];
        if ($skippedLines->isNotEmpty()) {
            $notices[] = "{$skippedLines->count()} ligne(s) ignorée(s) : " . $skippedLines->implode(' | ');
        }
        if ($rowWarnings->isNotEmpty()) {
            $notices[] = $rowWarnings->implode(' | ');
        }

        if (!empty($notices)) {
            return redirect()->back()->with('warning', 'Import terminé. ' . implode(' — ', $notices));
        }

        return redirect()->back()->with('success', 'Équipements importés avec succès.');
    }

    public function downloadQrCodePdf($id)
    {
        $equipement = Equipement::findOrFail($id);
        $pdfPath = storage_path('app/public/' . $equipement->qr_code);

        if (!file_exists($pdfPath)) {
            return redirect()->back()->with('error', 'Fichier PDF non trouvé.');
        }

        return response()->download($pdfPath, 'qr_code_equipement_' . $equipement->id . '.pdf');
    }
    public function saveOrdi(Request $request)
    {
        try {
            // Validation des données
            $validator = Validator::make($request->all(), [
                'des_equipement' => 'required|string|max:255',
                'marque' => 'required|string|max:255',
                'modele' => 'required|string|max:255',
                'categorie' => ['required', \Illuminate\Validation\Rule::in(Equipement::CATEGORIES)],
                'nature' => ['required', \Illuminate\Validation\Rule::in(Equipement::NATURES)],
                'num_inventaire' => 'nullable|string|max:255',
                'adresse_mac' => 'nullable|string|max:255',
                'numero_serie' => 'required|string|unique:equipements,numero_serie',
                'date_acquis' => 'required|date',
                'date_fin_vie' => 'nullable|date',
                'capacite' => 'nullable|integer',
                'ram' => 'nullable|integer',
                'source_acquisition' => ['nullable', \Illuminate\Validation\Rule::in(Equipement::SOURCES_ACQUISITION)],
                'nom_fn' => 'nullable|string|max:255',
                'processeur' => 'nullable|string|max:255',
                'systeme' => 'nullable|string|max:255',
                'etat' => ['required', \Illuminate\Validation\Rule::in(Equipement::ETATS)],
                'statut' => ['nullable', \Illuminate\Validation\Rule::in(Equipement::STATUTS)],
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
    
            // Création de l'équipement
            $equipement = Equipement::create([
                'des_equipement' => $request->des_equipement,
                'marque' => $request->marque,
                'modele' => $request->modele,
                'categorie' => $request->categorie,
                'nature' => $request->nature,
                'num_inventaire' => $request->num_inventaire,
                'adresse_mac' => $request->adresse_mac,
                'numero_serie' => $request->numero_serie,
                'date_acquis' => $request->date_acquis,
                'date_fin_vie' => $request->date_fin_vie,
                'capacite' => $request->capacite,
                'ram' => $request->ram,
                'source_acquisition' => $request->source_acquisition,
                'nom_fn' => $request->nom_fn,
                'processeur' => $request->processeur,
                'systeme' => $request->systeme,
                'etat' => $request->etat,
                'statut' => $request->statut ?? 'en stock',
                'user_id' => Auth::id(),
                'direction_id' => Auth::user()->direction_id
            ]);
    
            $baseUrl = request()->getSchemeAndHttpHost(); 
            $qrData = $baseUrl . route('equipement.details', $equipement->id, false);

            $qrCodeSvg = QrCode::format('svg')->size(200)->generate($qrData);

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.qr_code', [
                'qrCode' => $qrCodeSvg,
                'equipement' => $equipement
            ]);

            // Enregistrer le PDF dans le dossier public/qrcodes
            $pdfFileName = 'qrcodes/equipement_' . $equipement->id . '.pdf';
            \Storage::disk('public')->put($pdfFileName, $pdf->output());

            // Sauvegarder le chemin du PDF dans la BDD
            $equipement->qr_code = $pdfFileName;
            $equipement->save();

            $this->logModification(
                'ajout',
                "Ajout d'un nouvel équipement",
                $request->id ?? null
            );
            return redirect('/stock_materiel')->with('status', 'Équipement ajouté avec succès!');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage())
                ->withInput();
        }
    }
    
/*
public function show($id)
{
    $equipement = Equipement::findOrFail($id);
    return view('Admin.details_Materiel', compact('equipement'));
}
*/ 
public function delete_ordi($id)
    {
        $equipement = Equipement::find($id);
        if(!$equipement) {
            return redirect('/stock_materiel')->with('error', 'Équipement non trouvé');
        }
        
        $this->logModification(
            'suppression',
            "Suppression d'un équipement",
            $equipement->id
        );
        $equipement->delete();
        
        return redirect('/stock_materiel')->with('message', 'L\'équipement a bien été supprimé !');
    }

    public function showUpdateForm($id){
        $equipement = Equipement::find($id);
        if(!$equipement) {
            return redirect('/stock_materiel')->with('error', 'Équipement non trouvé');
        }
        return view('Patrimoine.equipements.modifier', compact('equipement')); // ✅ Changé le nom de la vue
    }
    
    public function updateEquipement(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:equipements,id',
            'des_equipement' => 'required|string|max:255',
            'marque' => 'required|string|max:255',
            'modele' => 'required|string|max:255',
            'categorie' => ['required', Rule::in(Equipement::CATEGORIES)],
            'nature' => ['required', Rule::in(Equipement::NATURES)],
            'num_inventaire' => 'nullable|string|max:255',
            'adresse_mac' => 'nullable|string|max:255',
            'numero_serie' => [
                'required',
                'string',
                Rule::unique('equipements', 'numero_serie')->ignore($request->id),
            ],
            'date_acquis' => 'required|date',
            'date_fin_vie' => 'nullable|date',
            'capacite' => 'nullable|integer',
            'ram' => 'nullable|integer',
            'source_acquisition' => ['required', Rule::in(Equipement::SOURCES_ACQUISITION)],
            'nom_fn' => 'nullable|string|max:255',
            'processeur' => 'nullable|string|max:255',
            'systeme' => 'nullable|string|max:255',
            'etat' => ['required', Rule::in(Equipement::ETATS)],
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        $equipement = Equipement::find($request->id);
        if (!$equipement) {
            return redirect()->back()->with('error', 'Équipement non trouvé');
        }
    
        $equipement->update([
            'des_equipement' => $request->des_equipement,
            'marque' => $request->marque,
            'modele' => $request->modele,
            'categorie' => $request->categorie,
            'nature' => $request->nature,
            'num_inventaire' => $request->num_inventaire,
            'adresse_mac' => $request->adresse_mac,
            'numero_serie' => $request->numero_serie,
            'date_acquis' => $request->date_acquis,
            'date_fin_vie' => $request->date_fin_vie,
            'capacite' => $request->capacite,
            'ram' => $request->ram,
            'nom_fn' => $request->nom_fn,
            'processeur' => $request->processeur,
            'systeme' => $request->systeme,
            'etat' => $request->etat,
            'source_acquisition' => $request->source_acquisition, 
        ]);
        $this->logModification(
            'modification',
            "Modification d'un équipement",
            $request->id ?? null
        );
    
        return redirect('/stock_materiel')->with('status', 'Équipement modifié avec succès !');
    }

    public function filter(Request $request)
{
   
    $categorie = $request->input('categorie');
    $marque = $request->input('marque');
    $modele = $request->input('modele');

    $query = Equipement::query();

    if ($categorie) {
        $query->where('categorie', 'like', '%' . $categorie . '%');
    }
    if ($marque) {
        $query->where('marque', 'like', '%' . $marque . '%');
    }
    if ($modele) {
        $query->where('modele', 'like', '%' . $modele . '%');
    }

    // Récupérer les équipements filtrés
    $equipements = $query->get();

    // Retourner la vue avec les données filtrées
    return view('Admin.stock_materiels', compact('equipements'));
}
}
