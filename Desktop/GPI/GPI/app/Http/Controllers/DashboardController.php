<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Equipement;
use App\Models\Administrateur;
use App\Models\DemandeMaintenance;
use App\Models\DemandeMateriel;
use App\Models\Licence;
use App\Models\Assignment;
use App\Models\Vehicule;
use App\Models\Mobilier;
use App\Models\PatrimoineDivers;
use Carbon\Carbon;
use PDF;

class DashboardController extends Controller
{
    public function showWelcome()
    {
        return view('welcome');
    }
    /*public function showdashboard()
    {
        $Users = User::all();
        $materielsEnStock= Equipement::where('etat', 'en stock')->get();
        $materielsHorsService = Equipement::where('etat', 'hors service')->get();
        $materielsEnMaintenance = Equipement::where('etat', 'en maintenance')->get();
        $materielsEnService = Equipement::where('etat', 'en service')->get();
        $nombreDeployes = Assignment::where('returned_at', null)->count();
        $materielsNonAttribues = Equipement::whereDoesntHave('assignments')->get();
        return view('Admin.dashboard',compact('Users','materielsEnStock','materielsHorsService','materielsEnMaintenance','materielsEnService','nombreDeployes','materielsNonAttribues'));
    }*/
    public function showAdmin_dashboard()
    {
        $directionId = auth()->user()->direction_id;
        $now = Carbon::now();
        $limitDate = $now->copy()->addDays(10);

        $Users = User::where('direction_id', $directionId)->where('role', '!=', 'superadmin')->count();
        $equipements = Equipement::where('direction_id', $directionId)->count();

        // Un seul aller-retour DB groupé par statut au lieu de 3 requêtes ->get() séparées
        // (chacune ne servait qu'à faire ->count() dans la vue / le graphique).
        $equipementsParStatut = Equipement::where('direction_id', $directionId)
            ->selectRaw('statut, count(*) as total')->groupBy('statut')->pluck('total', 'statut');
        $equipementsEnStock       = $equipementsParStatut['en stock'] ?? 0;
        $equipementsEnService     = $equipementsParStatut['en service'] ?? 0;
        $equipementsEnMaintenance = $equipementsParStatut['en maintenance'] ?? 0;

        // ->count() direct plutôt que ->get() suivi de ->count() : pas besoin d'hydrater les modèles.
        $licencesExpirees = Licence::where('direction_id', $directionId)->where('date_expiration', '<', $now)->count();
        $licencesBientotExpirees = Licence::where('direction_id', $directionId)->whereBetween('date_expiration', [$now, $limitDate])->count();
        // Remplace l'ancien Licence::...->paginate(100) : paginate() exécute 2 requêtes (count +
        // select limité) pour au final n'utiliser que ->count() dans la vue, et ce count() sur un
        // paginator ne comptait que les éléments de la page courante (plafonné à 100, jamais le vrai total).
        $licences = Licence::where('direction_id', $directionId)->where('date_expiration', '>', $limitDate)->count();

        // Un seul aller-retour DB groupé par catégorie au lieu de 23 requêtes ->get() séparées
        // (chacune ne servait qu'à faire ->count() dans la vue).
        $equipementsParCategorie = Equipement::where('direction_id', $directionId)
            ->selectRaw('categorie, count(*) as total')->groupBy('categorie')->pluck('total', 'categorie');

        $ordinateurs_portables = $equipementsParCategorie['Ordinateur portable'] ?? 0;
        $ordinateurs_allinone = $equipementsParCategorie['Ordinateur All-in-one'] ?? 0;
        $unites_centrales = $equipementsParCategorie['unite centrale'] ?? 0;
        $outillages_techniques = $equipementsParCategorie['outillage technique'] ?? 0;
        $imprimantes = $equipementsParCategorie['Imprimante'] ?? 0;
        $ecrans = $equipementsParCategorie['ecran'] ?? 0;
        $claviers = $equipementsParCategorie['clavier'] ?? 0;
        $souris = $equipementsParCategorie['souris'] ?? 0;
        $scanners = $equipementsParCategorie['Scanner'] ?? 0;
        $serveurs = $equipementsParCategorie['Serveur'] ?? 0;
        $routeurs = $equipementsParCategorie['Routeur'] ?? 0;
        $switchs = $equipementsParCategorie['Switch'] ?? 0;
        $onduleurs = $equipementsParCategorie['Onduleur'] ?? 0;
        $projecteurs = $equipementsParCategorie['Projecteur'] ?? 0;
        $telephones_ip = $equipementsParCategorie['Téléphone IP'] ?? 0;
        $parefeux = $equipementsParCategorie['pare-feu'] ?? 0;
        $photocopieuses = $equipementsParCategorie['photocopieuse'] ?? 0;
        $stockages = $equipementsParCategorie['stockage'] ?? 0;
        $visio = $equipementsParCategorie['systeme visio conference'] ?? 0;
        $accessoires_electriques = $equipementsParCategorie['Accessoire electrique'] ?? 0;
        $accessoires_reseau = $equipementsParCategorie['Accessoire reseau'] ?? 0;
        $accessoires_securite = $equipementsParCategorie['Accessoire securite'] ?? 0;
        $autres = $equipementsParCategorie['Autre'] ?? 0;

        // Autres patrimoines (parc auto, mobilier/matériel de bureau, fournitures & consommables)
        $nombreVehicules        = Vehicule::where('direction_id', auth()->user()->direction_id)->count();
        $nombreMobiliers        = Mobilier::where('direction_id', auth()->user()->direction_id)->count();
        $nombrePatrimoineDivers = PatrimoineDivers::where('direction_id', auth()->user()->direction_id)->count();

        // Répartitions pour les onglets "Autres patrimoines" du tableau de bord
        $vehiculesParStatut = Vehicule::where('direction_id', auth()->user()->direction_id)
            ->selectRaw('statut, count(*) as total')->groupBy('statut')->pluck('total', 'statut');
        $mobiliersParStatut = Mobilier::where('direction_id', auth()->user()->direction_id)
            ->selectRaw('statut, count(*) as total')->groupBy('statut')->pluck('total', 'statut');
        $diversParCategorie = PatrimoineDivers::where('direction_id', auth()->user()->direction_id)
            ->selectRaw('categorie, count(*) as total')->groupBy('categorie')->pluck('total', 'categorie');

        // Demandes de maintenance par mois : une seule requête groupée au lieu de 12 (une par
        // mois). Corrige au passage un oubli du filtre direction_id - la version précédente
        // comptait les demandes de TOUTES les directions dans ce graphique.
        $demandesParMois = DemandeMaintenance::where('direction_id', $directionId)
            ->whereYear('created_at', $now->year)
            ->selectRaw('MONTH(created_at) as mois, count(*) as total')
            ->groupBy('mois')
            ->pluck('total', 'mois');

        // Données pour les graphiques
        $chartData = [
            // Graphique des licences
            'licences' => [
                'labels' => ['Licences expirées', 'Licences bientôt expirées', 'Licences actives'],
                'values' => [
                    $licencesExpirees,
                    $licencesBientotExpirees,
                    $licences,
                ]
            ],
            // Graphique des équipements
            'equipements' => [
                'labels' => ['En stock', 'En service', 'En maintenance'],
                'values' => [
                    $equipementsEnStock,
                    $equipementsEnService,
                    $equipementsEnMaintenance,
                ]
            ],
            // Graphique des demandes de maintenance
            'demandes' => [
                'labels' => ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
                'values' => array_map(fn ($mois) => $demandesParMois[$mois] ?? 0, range(1, 12)),
            ]
        ];

        return view('Directeur.dashboard',compact(
        'Users',
        'equipements',
        'licences',
        'equipementsEnStock',
        'equipementsEnService',
        'equipementsEnMaintenance',
        'licencesExpirees',
        'licencesBientotExpirees',
        'chartData',
        'ordinateurs_portables',
        'ordinateurs_allinone',
        'unites_centrales',
        'outillages_techniques',
        'imprimantes',
        'ecrans',
        'claviers',
        'souris',
        'scanners',
        'serveurs',
        'routeurs',
        'switchs',
        'onduleurs',
        'projecteurs',
        'telephones_ip',
        'accessoires_electriques',
        'parefeux',
        'photocopieuses',
        'stockages',
        'visio',
        'accessoires_reseau',
        'accessoires_securite',
        'autres',
        'nombreVehicules',
        'nombreMobiliers',
        'nombrePatrimoineDivers',
        'vehiculesParStatut',
        'mobiliersParStatut',
        'diversParCategorie'
));
    }
    public function exportDashboardPdf()
    {
        $now = Carbon::now();
        $limitDate = $now->copy()->addDays(10);
        
        $data = [
            'equipementsEnStock' => Equipement::where('statut', 'en stock')
                ->where('direction_id', auth()->user()->direction_id)
                ->count(),
                
            'equipementsEnService' => Equipement::where('statut', 'en service')
                ->where('direction_id', auth()->user()->direction_id)
                ->has('assignments')
                ->count(),
                
            'equipementsEnMaintenance' => Equipement::where('statut', 'en maintenance')
                ->where('direction_id', auth()->user()->direction_id)
                ->has('assignments')
                ->count(),
                
            'equipementsHorsService' => Equipement::where('etat', 'hors service')
                ->where('direction_id', auth()->user()->direction_id)
                ->count(),
                
            'licencesExpirees' => Licence::where('direction_id', auth()->user()->direction_id)
                ->where('date_expiration', '<', $now)
                ->count(),
                
            'licencesBientotExpirees' => Licence::where('direction_id', auth()->user()->direction_id)
                ->whereBetween('date_expiration', [$now, $limitDate])
                ->count(),
                
            'licencesActives' => Licence::where('direction_id', auth()->user()->direction_id)
                ->where('date_expiration', '>', $limitDate)
                ->count(),
                
            'usersEquipes' => User::where('direction_id', auth()->user()->direction_id)
                ->whereHas('assignments', function ($query) {
                    $query->whereNull('returned_at');
                })->count(),
                
            'usersNonEquipes' => User::where('direction_id', auth()->user()->direction_id)
                ->whereDoesntHave('assignments', function ($query) {
                    $query->whereNull('returned_at');
                })->count(),
                
            'statsEquipements' => [
                'ordinateurs portable' => Equipement::where('direction_id', auth()->user()->direction_id)->where('categorie', 'Ordinateur portable')->count(),
                'ordinateurs all-in-one' => Equipement::where('direction_id', auth()->user()->direction_id)->where('categorie', 'Ordinateur All-in-one')->count(),
                'unite centrale' => Equipement::where('direction_id', auth()->user()->direction_id)->where('categorie', 'unite centrale')->count(),
                'outillage technique' => Equipement::where('direction_id', auth()->user()->direction_id)->where('categorie', 'outillage technique')->count(),
                'imprimantes' => Equipement::where('direction_id', auth()->user()->direction_id)->where('categorie', 'imprimante')->count(),
                'souris' => Equipement::where('direction_id', auth()->user()->direction_id)->where('categorie', 'souris')->count(),
                'clavier' => Equipement::where('direction_id', auth()->user()->direction_id)->where('categorie', 'clavier')->count(),
                'ecran' => Equipement::where('direction_id', auth()->user()->direction_id)->where('categorie', 'ecran')->count(),
                'scanners' => Equipement::where('direction_id', auth()->user()->direction_id)->where('categorie', 'scanner')->count(),
                'serveurs' => Equipement::where('direction_id', auth()->user()->direction_id)->where('categorie', 'serveur')->count(),
                'routeurs' => Equipement::where('direction_id', auth()->user()->direction_id)->where('categorie', 'routeur')->count(),
                'switchs' => Equipement::where('direction_id', auth()->user()->direction_id)->where('categorie', 'switch')->count(),
                'onduleurs' => Equipement::where('direction_id', auth()->user()->direction_id)->where('categorie', 'onduleur')->count(),
                'projecteurs' => Equipement::where('direction_id', auth()->user()->direction_id)->where('categorie', 'projecteur')->count(),
                'telephones_ip' => Equipement::where('direction_id', auth()->user()->direction_id)->where('categorie', 'téléphone IP')->count(),
                'pare-feu' => Equipement::where('direction_id', auth()->user()->direction_id)->where('categorie', 'pare-feu')->count(),
                'photocopieuse' => Equipement::where('direction_id', auth()->user()->direction_id)->where('categorie', 'photocopieuse')->count(),
                'stockage' => Equipement::where('direction_id', auth()->user()->direction_id)->where('categorie', 'stockage')->count(),
                'systeme visio conference' => Equipement::where('direction_id', auth()->user()->direction_id)->where('categorie', 'systeme visio conference')->count(),
                'Accessoire electrique' => Equipement::where('direction_id', auth()->user()->direction_id)->where('categorie', 'Accessoire electrique')->count(),
                'Accessoire reseau' => Equipement::where('direction_id', auth()->user()->direction_id)->where('categorie', 'Accessoire reseau')->count(),
                'Accessoire securite' => Equipement::where('direction_id', auth()->user()->direction_id)->where('categorie', 'Accessoire securite')->count(),
                'autres' => Equipement::where('direction_id', auth()->user()->direction_id)->where('categorie', 'autre')->count(),
            ],
            'dateGeneration' => $now->format('d/m/Y H:i')
        ];

        $pdf = PDF::loadView('pdf.dashboard', $data);
        return $pdf->download('tableau-de-bord-'.now()->format('Y-m-d').'.pdf');
    }
}
