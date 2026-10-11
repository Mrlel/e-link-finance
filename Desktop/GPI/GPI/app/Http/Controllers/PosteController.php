<?php


namespace App\Http\Controllers;

use App\Models\Poste;
use App\Models\Equipement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Assignment;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Traits\LogsModifications;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class PosteController extends Controller
{
    use LogsModifications;

      public function list_poste(Request $request)
    {
        $query = Poste::with(['equipements.assignments.user', 'user'])
            ->where('direction_id', Auth::user()->direction_id);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('code_poste', 'like', "%$s%")
                  ->orWhere('emplacement', 'like', "%$s%")
                  ->orWhereHas('user', function ($q2) use ($s) {
                      $q2->where('nom', 'like', "%$s%")->orWhere('prenom', 'like', "%$s%");
                  });
            });
        }

        if ($request->filled('statut')) {
            if ($request->statut === 'affecte') {
                $query->whereNotNull('user_id');
            } elseif ($request->statut === 'non_affecte') {
                $query->whereNull('user_id');
            }
        }

        $postes = $query->orderBy('code_poste')->get();
        $users = User::where('direction_id', Auth::user()->direction_id)->get();

        return view('Materiels.liste-postes', compact('postes', 'users'));
    }

public function createPosteComplet(Request $request)
{
    $postes = Poste::where('direction_id', Auth::user()->direction_id)->get();
    $equipementsStock = Equipement::where('direction_id', Auth::user()->direction_id)
                                ->where('statut', 'en stock')
                                ->whereNull('poste_id')
                                ->get();
    
    return view('Materiels.create-poste-complet', compact('postes', 'equipementsStock'));
}
public function retirer_poste($poste_id)
{
    DB::beginTransaction();

    try {
        $poste = Poste::with('equipements')->findOrFail($poste_id);

        if (!$poste->user_id) {
            return redirect()->back()->with('info', 'Ce poste n\'est pas assigné à un utilisateur.');
        }

        $user_id = $poste->user_id;

        // Retirer l'utilisateur du poste
        $poste->user_id = null;
        $poste->save();

        // Remettre en stock les équipements et clôturer les affectations
        foreach ($poste->equipements as $equipement) {
            $equipement->statut = 'en stock';
            $equipement->save();

            Assignment::where('equipement_id', $equipement->id)
                ->where('user_id', $user_id)
                ->whereNull('returned_at')
                ->update([
                    'returned_at' => now(),
                    'etat_retour' => $equipement->etat,
                    'returned_by' => Auth::id(),
                    'commentaire_retour' => 'Retour automatique suite retrait du poste',
                ]);
        }

        DB::commit();

        return redirect()->back()->with('success', 'Poste retiré avec succès. Historique conservé.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Erreur retrait poste : " . $e->getMessage());
        return redirect()->back()->with('error', 'Erreur lors du retrait du poste.');
    }
}

public function storePosteComplet(Request $request)
{
    DB::beginTransaction();

    try {
        $validator = Validator::make($request->all(), [
            'emplacement' => 'nullable|string|max:100',
            'unite_centrale_id' => 'nullable|exists:equipements,id',
            'composants_existants' => 'nullable|array',
            'composants_existants.*' => 'exists:equipements,id',
            'nouveaux_composants' => 'nullable|array',
            'nouveaux_composants.*.categorie' => 'required|string',
            'nouveaux_composants.*.numero_serie' => 'required|string',
            'nouveaux_composants.*.marque' => 'required|string|max:255',
            'nouveaux_composants.*.modele' => 'required|string|max:255',
            'nouveaux_composants.*.date_acquis' => 'nullable|date',
            'nouveaux_composants.*.nature' => 'nullable|string|in:accesoires informatiques,reseaux,informatiques et bureautiques,multimedia,telephonie et connectivite,autre',
            'nouveaux_composants.*.etat' => 'nullable|string|in:bon,moyen,hors service',
            'nouveaux_composants.*.statut' => 'nullable|string|in:en stock,en service,en maintenance',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Vérification des composants existants hors service
        if ($request->composants_existants) {
            $horsServiceComponents = Equipement::whereIn('id', $request->composants_existants)
                ->where('etat', 'hors service')
                ->count();

            if ($horsServiceComponents > 0) {
                return redirect()->back()
                    ->with('error', 'Un ou plusieurs composants sélectionnés sont hors service et ne peuvent pas être associés à un poste.')
                    ->withInput();
            }
        }

        // Récupération de la direction de l'utilisateur connecté
        $userDirection = Auth::user()->direction->nom_direction ?? 'DIRECTION';
        $userDirectionCode = strtoupper(substr(str_replace(' ', '', $userDirection), 0, 4));

        // Génération du code_poste
        $lastPosteCount = Poste::where('direction_id', Auth::user()->direction_id)
            ->count();
        $nextPosteNumber = str_pad($lastPosteCount + 1, 3, '0', STR_PAD_LEFT);

        // Si on a une unité centrale, on prend sa catégorie, sinon on utilise "POSTE"
        $categorie = 'POSTE';
        if ($request->unite_centrale_id) {
            $uniteCentrale = Equipement::find($request->unite_centrale_id);
            $categorie = strtoupper(substr(str_replace(' ', '', $uniteCentrale->categorie ?? 'POSTE'), 0, 4));
        }

        $codePoste = "MEPD-{$userDirectionCode}-{$categorie}{$nextPosteNumber}";
        
        // Génération de l'emplacement
        $emplacement = "DESKTOP-{$userDirectionCode}-{$nextPosteNumber}";

        // Création du poste
        $poste = Poste::create([
            'code_poste' => $codePoste,
            'emplacement' => $emplacement,
            'direction_id' => Auth::user()->direction_id,
            'user_id' => null
        ]);
        
        $baseUrl = request()->getSchemeAndHttpHost(); 
        $qrData = $baseUrl . route('postes.show', $poste->id, false);
        
        $qrSvg = \QrCode::format('svg')->size(200)->generate($qrData);
        $fileName = 'qrcodes/poste_' . $poste->id . '.svg';
        
        \Storage::disk('public')->put($fileName, $qrSvg);
        
        $poste->qr_code = $fileName;
        $poste->save();
        
        
        // Association de l'unité centrale
        if ($request->unite_centrale_id) {
            Equipement::where('id', $request->unite_centrale_id)
                    ->update(['poste_id' => $poste->id]);
        }

        // Association des composants existants
        if ($request->composants_existants) {
            Equipement::whereIn('id', $request->composants_existants)
                    ->update([
                        'poste_id' => $poste->id,
                    ]);
        }

        // Création des nouveaux composants
        if ($request->nouveaux_composants) {
            foreach ($request->nouveaux_composants as $composant) {
                // Vérification que le composant n'est pas hors service
                if (($composant['etat'] ?? 'bon') === 'hors service') {
                    DB::rollBack();
                    return redirect()->back()
                        ->with('error', 'Impossible d\'ajouter un composant hors service.')
                        ->withInput();
                }

                Equipement::create([
                    'nature' => $composant['nature'] ?? 'autre',
                    'categorie' => $composant['categorie'],
                    'des_equipement' => $composant['categorie'],
                    'marque' => $composant['marque'] ?? null,
                    'modele' => $composant['modele'] ?? null,
                    'numero_serie' => $composant['numero_serie'],
                    'etat' => $composant['etat'] ?? 'bon',
                    'date_acquis' => $composant['date_acquis'] ?? now(),
                    'poste_id' => $poste->id,
                    'statut' =>  $composant['statut'] ?? 'en stock', 
                    'user_id' => Auth::id(),
                    'direction_id' => Auth::user()->direction_id,
                ]);
            }
        }

        DB::commit();
        $this->logAction(
            'création',
            "Création d'un poste complet ou d'équipement",
            posteId: $poste->id ?? null
        );
        return redirect()->back()->with('success', 'Poste complet créé avec succès!');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Erreur création poste complet: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Erreur lors de la création du poste');
    }
}
   
public function assigner_poste(Request $request, $poste_id)
{
    $poste = Poste::findOrFail($poste_id);

    // Vérifie si le poste est déjà assigné
    if ($poste->user_id !== null) {
        return redirect()->back()->with('error', 'Ce poste est déjà assigné à un utilisateur.');
    }

    // Assigner le poste à l'utilisateur
    $poste->user_id = $request->user_id;
    $poste->save();

    foreach ($poste->equipements as $equipement) {
        // Vérifie si l’équipement est déjà assigné (facultatif)
        $alreadyAssigned = Assignment::where('equipement_id', $equipement->id)
            ->whereNull('returned_at')
            ->exists();

        if (!$alreadyAssigned) {
            Assignment::create([
                'user_id'      => $request->user_id,
                'equipement_id'=> $equipement->id,
                'direction_id' => Auth::user()->direction_id,
                'statut'       => 'en service',
            ]);

            $equipement->statut = 'en service';
            $equipement->save();
        }
    }

    return redirect()->back()->with('status', 'Poste et équipements assignés avec succès !');
}

    public function show($id)
{
    $poste = Poste::with('equipements')->findOrFail($id);
    return view('Materiels.poste_details', compact('poste'));
}

    // Afficher le formulaire d'édition
    public function edit($id)
    {
        $poste = Poste::where('direction_id', Auth::user()->direction_id)->findOrFail($id);
        $users = User::where('direction_id', Auth::user()->direction_id)->get();
        return view('Materiels.postes-edit', compact('poste', 'users'));
    }

    // Mettre à jour un poste
    public function update(Request $request, $id)
    {
        $poste = Poste::where('direction_id', Auth::user()->direction_id)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'code_poste' => 'required|string|unique:postes,code_poste,' . $poste->id,
            'emplacement' => 'nullable|string|max:255',
            'user_id' => 'nullable|exists:users,id',
            
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $poste->update($request->only(['code_poste', 'emplacement', 'user_id']));

        return redirect()->back()->with('status', 'Poste modifié avec succès !');
    }

    // Supprimer un poste
    public function destroy($id)
    {
        $poste = Poste::where('direction_id', Auth::user()->direction_id)->findOrFail($id);
        $poste->delete();

        return redirect()->back()->with('status', 'Poste supprimé avec succès !');
    }

    /**
     * Affiche la vue d'impression d'un poste avec ses composants
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function printPoste($id)
    {
        $poste = Poste::with(['equipements' => function($query) {
            $query->with(['assignments' => function($q) {
                $q->latest('assigned_at');
            }]);
        }])->findOrFail($id);
        $pdf = Pdf::loadView('pdf.print-poste', compact('poste'));
        return $pdf->download('poste_'.$poste->code_poste.'.pdf');
    }
   
}