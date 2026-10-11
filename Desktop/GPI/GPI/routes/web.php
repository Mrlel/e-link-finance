<?php

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EquipementImport;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EquipementController;
use App\Http\Controllers\LogicielController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DemandeMaintenanceController;
use App\Http\Controllers\DemandeMaterielController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\MaterielController;
use App\Http\Controllers\Auth\ChangePasswordController;

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AssignerLogicielController;
use App\Http\Controllers\userDashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\AcceuilController;
use App\Http\Controllers\Direction\DirectionController;
use App\Http\Controllers\superAdmin\superAdminController;
use App\Http\Controllers\FiltreController;
use App\Http\Controllers\PosteController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\JournalModifController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\MesDemandesController;
use App\Http\Controllers\FonctionController;
use App\Http\Controllers\SortieVehiculeController;
use App\Http\Controllers\PatrimoineEnleveController;
use App\Http\Controllers\MobilierController;
use App\Http\Controllers\SortieEquipementController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\FinVieController;
use App\Http\Controllers\PatrimoineDiversController;
use App\Http\Controllers\PatrimoineInventaireController;
use App\Http\Controllers\VehiculeController;

/*
|--------------------------------------------------------------------------
| ROUTES PUBLIQUES (NON AUTHENTIFIÉES)
|--------------------------------------------------------------------------
*/

// AcceuilController
Route::get('/', [AcceuilController::class, 'webHome']);
Route::get('/home', [AcceuilController::class, 'webHome'])->name('webHome');
Route::get('/demo', [AcceuilController::class, 'webDemo'])->name('webDemo');

// LoginController (Authentification)
Route::get('/login', function () {
    return view('/welcome');
})->name('login');
Route::post('/login/traitement', [LoginController::class, 'loginUser']);
Route::post('/loginUser/traitement', [LoginController::class, 'loginUser']); // Dupliqué, conservé pour l'instant
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/userLog_out', [LoginController::class, 'logout_user']);

// Routes Utilitaires Publiques
Route::get('/aide', function () {
    return view('Users.aide');
})->name('aide');

/*
|--------------------------------------------------------------------------
| ROUTES AUTHENTIFIÉES (REQUIRENT LE MIDDLEWARE 'auth')
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'password.change'])->group(function () {

    // --- 1. DASHBOARDS et VUES UTILISATEURS ---
    
    // userDashboardController
    Route::get('/userDashboard', [userDashboardController::class, 'showUserDashboard'])->name('userDashboard');
    
    // DashboardController (Admin/Gestionnaire)
    Route::get('/Admin_dashboard', [DashboardController::class, 'showdashboard'])->name('admin/dashboard');
    Route::get('/adminDashboard', [DashboardController::class, 'showAdmin_dashboard'])->name('Admin/dashboard');
    Route::get('/adminDashboard/export-pdf', [DashboardController::class, 'exportDashboardPdf'])->name('admin.dashboard.export-pdf');

    // --- 2. GESTION DES COMPTES (Profil/Utilisateur) ---

    // ChangePasswordController
    Route::get('/password/change', [ChangePasswordController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/password/change', [ChangePasswordController::class, 'changePassword'])->name('password.update');

    // UserController (Profil) - libre accès à tout utilisateur authentifié, chacun ne gère que son propre compte
    Route::get('/profile/edit', [LoginController::class, 'edit'])->name('profile.edit'); // Utilise LoginController pour l'édition du profil
    Route::get('/profile/edit_mdp', [LoginController::class, 'edit_mdp']); // Utilise LoginController
    Route::post('/profile/update', [UserController::class, 'update'])->name('profile.update');
    Route::post('/password/update', [UserController::class, 'password_update']);

    // UserController (CRUD Utilisateurs) - réservé aux admins de direction, directeurs et superadmin
    Route::middleware(['role:admin,directeur,superadmin'])->group(function () {
        Route::resource('fonctions', FonctionController::class);
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::get('/update_user/{id}', [UserController::class, 'showUpdateUserForm'])->name('admin/adduser');
        Route::post('/save_user/traitement', [UserController::class, 'saveAdd_user'])->name('users.store');
        Route::post('/update_user/traitement', [UserController::class, 'save_update']);
        Route::get('/user_Delete/{id}', [UserController::class, 'userDelete']);
        Route::get('/userlist', [UserController::class, 'showUserlist'])->name('admin/userlist');
        Route::get('/utilisateurs/connecter', [UserController::class, 'showLoggedUsers']);
        Route::get('/utilisateurs/equipes', [UserController::class, 'showUser_equipes']);
        Route::get('/utilisateurs/non-equipes', [UserController::class, 'showUser_non_equipes']);
        Route::get('/users/search', [UserController::class, 'search'])->name('users.search');
        Route::post('/import-users', [UserController::class, 'importUsers'])->name('import.users');
        Route::get('/import-users/modele', [UserController::class, 'downloadImportTemplate'])->name('import.users.template');
        Route::post('/export-users-excel', [UserController::class, 'ExportUsers_listExcel'])->name('ExportUsersExcel');
        Route::post('/ExportUsers_Pdf', [UserController::class, 'ExportUsers_listPdf'])->name('ExportUsers');
    });
    // --- 3. SUPER ADMIN et GESTION DES DIRECTIONS ---
    // Réservé au rôle superadmin (création/suppression de directions et de comptes admin)
    Route::middleware(['role:superadmin'])->group(function () {

        // DirectionController
        Route::get('/directions/create', [DirectionController::class, 'create'])->name('directions.create');
        Route::post('/directions', [DirectionController::class, 'store'])->name('directions.store');
        Route::get('/directions/{id}/edit', [DirectionController::class, 'edit'])->name('directions.edit');
        Route::post('/directions/{id}/update', [DirectionController::class, 'update'])->name('directions.update');
        Route::post('/directions/{id}/deactivate', [DirectionController::class, 'deactivate'])->name('directions.deactivate');
        Route::delete('/directions/{id}', [DirectionController::class, 'destroy'])->name('directions.destroy');
        Route::get('/directions/{id}/assign-admin', [DirectionController::class, 'assignAdmin'])->name('directions.assign-admin');
        Route::post('/directions/{id}/assign-admin', [DirectionController::class, 'storeAssignAdmin'])->name('directions.store-assign-admin');
        Route::get('directions/list-direction', [DirectionController::class, 'listDirections'])->name('directions.list-direction');
        Route::get('/superadmin/dashboard', [DirectionController::class, 'superadminDashboard'])->name('superadmin.dashboard');
        Route::get('/superadmin/rapport', [DirectionController::class, 'rapport'])->name('superadmin.rapport');
        Route::get('/superadmin/rapport/{id}', [DirectionController::class, 'rapport']);
        Route::get('/rapport/pdf', [DirectionController::class, 'rapportPDF'])->name('rapport.pdf');
        Route::get('/rapport/pdf/{directionId}', [DirectionController::class, 'rapportPDF'])->name('rapport.pdf.direction');
        /*Route::get('/rapport/word', [DirectionController::class, 'rapportWord'])->name('rapport.word');*/ // Commenté

        // superAdminController
        Route::get('/directions/create-admin', [superAdminController::class, 'createAdmin'])->name('directions.create-admin');
        Route::post('/directions/store-admin', [superAdminController::class, 'storeAdmin'])->name('directions.store-admin');
        Route::get('directions/list-admin', [superAdminController::class, 'listAdmins'])->name('directions.list-admin');
        Route::get('directions/{id}/edit-admin', [superAdminController::class, 'editAdmin'])->name('directions.edit-admin');
        Route::put('directions/{id}/update-admin', [superAdminController::class, 'updateAdmin'])->name('directions.update-admin');
        Route::get('directions/{id}/show-admin', [superAdminController::class, 'showAdmin'])->name('directions.show-admin');
        Route::delete('directions/{id}/destroy-admin', [superAdminController::class, 'destroyAdmin'])->name('directions.destroy-admin');
    });

    // Réinitialisation de mot de passe : utilisée par les admins de direction (leurs propres
    // employés) ET par le superadmin (comptes admin). Le contrôle d'accès et le périmètre
    // (même direction, sauf superadmin) sont appliqués dans superAdminController.
    Route::put('directions/{id}/update-password-admin', [superAdminController::class, 'updatePassword'])->name('directions.update-password-admin');
    Route::get('directions/{id}/update-password-admin', [superAdminController::class, 'updatePasswordAdmin']);
    Route::put('directions/{id}/reset-password-admin', [superAdminController::class, 'resetPassword'])->name('directions.reset-password-admin');

    // --- 4. GESTION DES ÉQUIPEMENTS (Matériel/Equipement/Poste) ---

    // EquipementController
    Route::get('/equipements/create', [EquipementController::class, 'create_equipement'])->name('equipement.create');
    Route::post('/import-equipements', [EquipementController::class, 'importEquipement'])->name('equipement.import');
    Route::get('/import-equipements/modele', [EquipementController::class, 'downloadImportTemplate'])->name('equipement.import-template');
    Route::get('/equipement/{id}', [EquipementController::class, 'show'])->name('equipement.show');
    Route::get('/ordinateur', [EquipementController::class, 'showOrdiPage']);
    Route::get('/delete_ordinateur/{id}', [EquipementController::class, 'delete_ordi']);
    Route::post('/ordinateur/traitement', [EquipementController::class, 'saveOrdi'])->name('ordinateur.save');
    Route::get('/update_equipemnt/{id}', [EquipementController::class, 'showUpdateForm']);
    Route::post('/update_equipemnt/traitement', [EquipementController::class, 'updateEquipement'])->name('equipement.update');
    Route::get('/filter_equipements', [EquipementController::class, 'filter'])->name('filter.equipements');
    Route::get('/equipements/{id}/download-qr', [EquipementController::class, 'downloadQrCodePdf'])->name('equipements.downloadQr');

    // Sorties d'équipements (maintenance externe, réforme, transfert)
    Route::get('/sorties-equipements', [SortieEquipementController::class, 'index'])->name('sorties-equipements.index');
    Route::get('/sorties-equipements/{id}', [SortieEquipementController::class, 'show'])->name('sorties-equipements.show');
    Route::get('/equipements/{equipementId}/sortie/create', [SortieEquipementController::class, 'create'])->name('sorties-equipements.create');
    Route::post('/sorties-equipements', [SortieEquipementController::class, 'store'])->name('sorties-equipements.store');
    Route::put('/sorties-equipements/{sortieId}/retour', [SortieEquipementController::class, 'retour'])->name('sorties-equipements.retour');

    // MaterielController
    Route::get('/materiels/en-service', [MaterielController::class, 'materielsEnService'])->name('materiels.en-service');
    Route::get('/stock_materiel', [MaterielController::class, 'list_stock'])->name('materiels.stock');
    Route::get('/materiels-en-service', [MaterielController::class, 'materielsEnService'])->name('materiels.en.service');
    Route::get('/materiels-en-maintenance', [MaterielController::class, 'materielsEnMaintenance']);
    Route::get('/materiels-hors-service', [MaterielController::class, 'materielsHorsService']);
    Route::get('/materiels/recherche', [MaterielController::class, 'recherche'])->name('materiels.recherche');

    // PosteController
    Route::resource('postes', PosteController::class)->except(['show']); // Conservez le show personnalisé ci-dessous
    Route::get('/postes/{poste}', [PosteController::class, 'show'])->name('postes.show');
    Route::get('/list_poste', [PosteController::class, 'list_poste'])->name('list_poste.index');
    Route::post('/postes/assigner/{poste}', [PosteController::class, 'assigner_poste'])->name('assigner_poste');
    Route::post('/postes/{poste}/retirer', [PosteController::class, 'retirer_poste'])->name('postes.retirer');
    Route::get('/poste-complet/create', [PosteController::class, 'createPosteComplet'])->name('poste-complet.create');
    Route::post('/poste-complet/store', [PosteController::class, 'storePosteComplet'])->name('poste-complet.store');
    Route::get('/postes/{id}/print', [PosteController::class, 'printPoste'])->name('postes.print');
    
    // FiltreController (Inventaire)
    Route::get('/inventaire', [FiltreController::class, 'index'])->name('inventaire.index');
    Route::post('/inventaire/export', [FiltreController::class, 'export'])->name('inventaire.export');

    // --- 5. GESTION DES LOGICIELS ---

    // LogicielController
    Route::get('/list_logiciel', [LogicielController::class, 'showList_logiciel'])->name('list_logiciel');
    Route::get('/logiciel/nouveau', [LogicielController::class, 'create'])->name('logiciel.create');
    Route::get('/logiciel_expire', [LogicielController::class, 'logiciel_expirer']);
    Route::get('/logiciel_bientot_expire', [LogicielController::class, 'logiciel_bientot_expirer']);
    Route::get('/delete_logiciel/{id}', [LogicielController::class, 'delete_logiciel']);
    Route::get('modifier_logiciel/{id}', [LogicielController::class, 'modifier_logiciel']);
    Route::post('update_logiciel/{id}', [LogicielController::class, 'update_logiciel']);
    Route::get('/logiciel/{id}', [LogicielController::class, 'showLogiciel_details'])->name('logiciel.details');
    Route::post('/logiciel/traitement', [LogicielController::class, 'saveLogiciel']);
    Route::get('/logicielList', [LogicielController::class, 'showList_Logiciel']); // Dupliqué, conservé pour l'instant
    Route::post('/import-logiciels', [LogicielController::class, 'importLogiciels'])->name('import.logiciels');
    Route::post('/export-logiciels-excel', [LogicielController::class, 'exportLogicielsExcel'])->name('export.logiciels.excel');
    Route::post('/export-logiciels-pdf', [LogicielController::class, 'exportLogicielsPdf'])->name('export.logiciels.pdf');

    // AssignerLogicielController
    Route::post('/assigner_logiciels/store', [AssignerLogicielController::class, 'store'])->name('assigner_logiciels.store');
    Route::get('/list_historique_logiciel_assigner', [AssignerLogicielController::class, 'index'])->name('list_historique_logiciel_assigner');
    Route::get('/retirer/{id}', [AssignerLogicielController::class, 'retirer'])->name('retirer');
    Route::get('/historique_retraits', [AssignerLogicielController::class, 'historiqueRetraitsLicence'])->name('historique_retraits');
    Route::get('/historiqueLicence', [AssignerLogicielController::class, 'historiqueLicence'])->name('historiqueLicence');

    // --- 6. GESTION DES AFFECTATIONS (Assignment/Attribution) ---

    // AssignmentController
    Route::get('/Page_assigner', [AssignmentController::class, 'Page_assigner'])->name('assigner.page');
    Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
    Route::get('/assignment/assign', [AssignmentController::class, 'create'])->name('assignments.create');
    Route::post('/assign-equipement', [AssignmentController::class, 'assignerEquipement'])->name('assign.equipement');
    Route::put('/retourner-equipement/{assignmentId}', [AssignmentController::class, 'retournerEquipement'])->name('retourner.equipement');
    Route::get('/list_historique_attribution', [AssignmentController::class, 'index']); // Dupliqué, conservé pour l'instant
    Route::get('/user_details/{id}', [AssignmentController::class, 'user_details'])->name('user.details');
    Route::get('/equipement_details/{id}', [AssignmentController::class, 'equipement_details'])->name('equipement.details');
    Route::put('/equipement/{equipement}/confirmer', [AssignmentController::class, 'confirmerReception'])->name('equipement.confirmer');
    Route::get('/usersEquipement/{id}', [AssignmentController::class, 'showEquipementsUser'])->name('usersEquipement');
    Route::get('/users/{id}/all-equipements', [AssignmentController::class, 'allEquipementsUser'])->name('users.all_equipements');
    Route::get('/adminsEquipement/{id}', [AssignmentController::class, 'showEquipementsAdmin'])->name('adminsEquipement');

    // --- 7. GESTION DES DEMANDES (Maintenance/Matériel) ---

    // 
    Route::get('/Mes_demandes_en_cours', [MesDemandesController::class, 'showMesDemandesEnCours'])->name('Mes_demandes_en_cours.index');
    Route::resource('demande_maintenances', DemandeMaintenanceController::class)->only(['index', 'show']);
    Route::post('demande_maintenances/{id}/update-status', [DemandeMaintenanceController::class, 'updateStatus'])->name('demande_maintenances.updateStatus');
    Route::post('/demandes/{id}/transferer', [DemandeMaintenanceController::class, 'transfererDemande'])->name('demandes.transferer');
    Route::put('/chef-service/demande-maintenance/{id}/approuver', [DemandeMaintenanceController::class, 'approuverParChef'])->name('chef_de_service.demande-maintenance.approuver');
    Route::put('/admin/demande-maintenance/{id}/approuver', [DemandeMaintenanceController::class, 'approuverParAdmin'])->name('admin.demande-maintenance.approuver');
    Route::put('/admin/demande-maintenance/{id}/rejeter', [DemandeMaintenanceController::class, 'rejeter'])->name('admin.demande-maintenance.rejeter');
    Route::get('/demandeMaintenance', [DemandeMaintenanceController::class, 'showFormDemande']);
    Route::post('/demandeMaintenance/traitement', [DemandeMaintenanceController::class, 'saveDemande']);
    Route::get('/update_demandeMaintenance/{id}', [DemandeMaintenanceController::class, 'showUpdate_FormDemande']);
    Route::post('/update_demandeMaintenance/traitement', [DemandeMaintenanceController::class, 'save_update_Demande']);
    Route::get('/demandeList', [DemandeMaintenanceController::class, 'showDemandeList']);
    Route::post('/update_en_maintenance/{id}/status', [DemandeMaintenanceController::class, 'update_en_maintenance'])->name('update_en_maintenance');
    Route::post('/update_en_service/{id}/status', [DemandeMaintenanceController::class, 'update_en_service'])->name('update_en_service');
    Route::post('/update_traitee/{id}/status', [DemandeMaintenanceController::class, 'update_traitee'])->name('update_traitee');
    Route::post('/demande-maintenance/{id}/cancel', [DemandeMaintenanceController::class, 'cancelDemandeMaintenance'])->name('demande-maintenance.cancel');
    Route::get('/demande-maintenance/{id}', [DemandeMaintenanceController::class, 'detail'])->name('demande-materiel.detail'); // Nom de route incohérent

    // DemandeMaterielController
    Route::get('/demande-materiel', [DemandeMaterielController::class, 'index'])->name('demande-materiel.index');
    Route::get('/demande-materiel/{id}', [DemandeMaterielController::class, 'showDetail'])->name('demande-materiel.show');
    Route::put('/chef-service/demandes/{id}/approuver', [DemandeMaterielController::class, 'approuverParChef'])->name('chef_de_service.demande-materiel.approuver');
    Route::put('/gestionnaire_parc/demandes/{id}/approuver', [DemandeMaterielController::class, 'approuverParGestParc'])->name('gestionnaire_parc.demande-materiel.approuver');
    Route::put('/admin/demandes/{id}/approuver', [DemandeMaterielController::class, 'approuverParAdmin'])->name('admin.demande-materiel.approuver');
    Route::put('/admin/demandes/{id}/rejeter', [DemandeMaterielController::class, 'rejeterParAdmin'])->name('admin.demande-materiel.rejeter');
    Route::get('/demandeMateriel', [DemandeMaterielController::class, 'show_demande_materiel_form']);
    Route::post('/demandeMateriel/traitement', [DemandeMaterielController::class, 'saveDemandeMateriel']);
    Route::post('/demande-materiel/{id}/status', [DemandeMaterielController::class, 'updateStatus'])->name('demande-materiel.updateStatus');
    Route::post('/demande-materiel/{id}/cancel', [DemandeMaterielController::class, 'cancelDemandeMateriel'])->name('demande-materiel.cancel');

    // MaintenanceController (Actions liées aux fiches de maintenance)
    Route::get('/admin/maintenance/create-from-demande/{id}', [MaintenanceController::class, 'createFromDemande'])->name('admin.maintenance.createFromDemande');
    Route::post('/admin/maintenance/store', [MaintenanceController::class, 'store'])->name('admin.maintenance.store');
    Route::get('/list_maintenance', [MaintenanceController::class, 'listMaintenance'])->name('list_maintenance');
    Route::get('/maintenances/{id}', [MaintenanceController::class, 'show'])->name('maintenances.show');
    Route::post('/maintenances/create-from-demande/{demandeId}', [MaintenanceController::class, 'createFromDemande'])->name('maintenances.createFromDemande');

    // --- 8. NOTIFICATIONS, JOURNAL, DOCUMENTS ---

    // NotificationController
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead']); // Dupliqué
    Route::get('/userNotifications', [NotificationController::class, 'userNotifications'])->name('userNotifications.index');
    Route::get('/notifications/count', [NotificationController::class, 'count'])->name('notifications.count');

    // JournalModifController
    Route::get('/journal', [JournalModifController::class, 'index'])->name('journal.index');

    // DocumentController
    Route::resource('/documents', DocumentController::class);

    // Formation
    Route::get('/centre-de-formation', [FormationController::class, 'index'])->name('formation.index');
    Route::get('/formation/{slug}', [FormationController::class, 'module'])->name('formation.module');

    // Fin de vie (tableau de bord multi-actifs)
    Route::get('/fin-de-vie', [FinVieController::class, 'index'])->name('fin-vie.index');
    Route::get('/patrimoine-divers', [PatrimoineDiversController::class, 'index'])->name('patrimoine-divers.index');
    Route::get('/patrimoine-divers/create', [PatrimoineDiversController::class, 'create'])->name('patrimoine-divers.create');
    Route::post('/patrimoine-divers', [PatrimoineDiversController::class, 'store'])->name('patrimoine-divers.store');
    Route::get('/patrimoine-divers/export-excel', [PatrimoineDiversController::class, 'exportExcel'])->name('patrimoine-divers.export-excel');
    Route::get('/patrimoine-divers/export-pdf', [PatrimoineDiversController::class, 'exportPdf'])->name('patrimoine-divers.export-pdf');
    Route::get('/patrimoine-divers/{id}', [PatrimoineDiversController::class, 'show'])->name('patrimoine-divers.show');
    Route::get('/patrimoine-divers/{id}/edit', [PatrimoineDiversController::class, 'edit'])->name('patrimoine-divers.edit');
    Route::put('/patrimoine-divers/{id}', [PatrimoineDiversController::class, 'update'])->name('patrimoine-divers.update');
    Route::delete('/patrimoine-divers/{id}', [PatrimoineDiversController::class, 'destroy'])->name('patrimoine-divers.destroy');
    Route::get('/patrimoine-divers/{id}/assigner', [PatrimoineDiversController::class, 'pageAssigner'])->name('patrimoine-divers.page-assigner');
    Route::post('/patrimoine-divers/{id}/assigner', [PatrimoineDiversController::class, 'assigner'])->name('patrimoine-divers.assigner');
    Route::put('/patrimoine-divers-assignment/{assignmentId}/retourner', [PatrimoineDiversController::class, 'retourner'])->name('patrimoine-divers.retourner');
    Route::post('/patrimoine-divers/{id}/reapprovisionner', [PatrimoineDiversController::class, 'reapprovisionner'])->name('patrimoine-divers.reapprovisionner');
    Route::get('/patrimoine-divers-historique', [PatrimoineDiversController::class, 'historique'])->name('patrimoine-divers.historique');

    // --- 9b. MOBILIER & MATÉRIEL DE BUREAU ---

    Route::get('/mobiliers', [MobilierController::class, 'index'])->name('mobiliers.index');
    Route::get('/mobiliers/create', [MobilierController::class, 'create'])->name('mobiliers.create');
    Route::post('/mobiliers', [MobilierController::class, 'store'])->name('mobiliers.store');
    Route::get('/mobiliers/inventaire', [MobilierController::class, 'inventaire'])->name('mobiliers.inventaire');
    Route::get('/mobiliers/historique', [MobilierController::class, 'historique'])->name('mobiliers.historique');
    Route::get('/mobiliers/export-excel', [MobilierController::class, 'exportExcel'])->name('mobiliers.export-excel');
    Route::get('/mobiliers/export-pdf', [MobilierController::class, 'exportPdf'])->name('mobiliers.export-pdf');
    Route::get('/mobiliers/{id}', [MobilierController::class, 'show'])->name('mobiliers.show');
    Route::get('/mobiliers/{id}/edit', [MobilierController::class, 'edit'])->name('mobiliers.edit');
    Route::put('/mobiliers/{id}', [MobilierController::class, 'update'])->name('mobiliers.update');
    Route::delete('/mobiliers/{id}', [MobilierController::class, 'destroy'])->name('mobiliers.destroy');
    Route::post('/mobiliers/{id}/affecter', [MobilierController::class, 'affecter'])->name('mobiliers.affecter');
    Route::put('/mobilier-assignments/{assignmentId}/retirer', [MobilierController::class, 'retirer'])->name('mobiliers.retirer');
    Route::post('/mobiliers/{id}/sortie', [MobilierController::class, 'sortie'])->name('mobiliers.sortie');

    // --- 10. PARC AUTOMOBILE ---
    Route::get('/vehicules', [VehiculeController::class, 'index'])->name('vehicules.index');
    Route::get('/vehicules/create', [VehiculeController::class, 'create'])->name('vehicules.create');
    Route::post('/vehicules', [VehiculeController::class, 'store'])->name('vehicules.store');
    Route::get('/vehicules/historique', [VehiculeController::class, 'historique'])->name('vehicules.historique');
    Route::get('/vehicules/export-excel', [VehiculeController::class, 'exportExcel'])->name('vehicules.export-excel');
    Route::get('/vehicules/export-pdf', [VehiculeController::class, 'exportPdf'])->name('vehicules.export-pdf');
    Route::get('/vehicules/{id}', [VehiculeController::class, 'show'])->name('vehicules.show');
    Route::get('/vehicules/{id}/edit', [VehiculeController::class, 'edit'])->name('vehicules.edit');
    Route::put('/vehicules/{id}', [VehiculeController::class, 'update'])->name('vehicules.update');
    Route::delete('/vehicules/{id}', [VehiculeController::class, 'destroy'])->name('vehicules.destroy');
    Route::post('/vehicules/{id}/affecter', [VehiculeController::class, 'affecter'])->name('vehicules.affecter');
    Route::put('/vehicule-assignments/{assignmentId}/retirer', [VehiculeController::class, 'retirer'])->name('vehicules.retirer');

    // Sorties véhicules
    Route::get('/sorties-vehicules', [SortieVehiculeController::class, 'index'])->name('sorties-vehicules.index');
    Route::get('/sorties-vehicules/{id}', [SortieVehiculeController::class, 'show'])->name('sorties-vehicules.show');
    Route::get('/vehicules/{vehiculeId}/sortie/create', [SortieVehiculeController::class, 'create'])->name('sorties-vehicules.create');
    Route::post('/sorties-vehicules', [SortieVehiculeController::class, 'store'])->name('sorties-vehicules.store');
    Route::put('/sorties-vehicules/{sortieId}/retour', [SortieVehiculeController::class, 'retour'])->name('sorties-vehicules.retour');

    // Inventaire patrimoine partagé (audit lecture seule : véhicules + fournitures/consommables + mobilier)
    Route::get('/inventaire-patrimoine', [PatrimoineInventaireController::class, 'index'])->name('inventaire-patrimoine.index');

    // Patrimoines enlevés (page unifiée)
    Route::get('/patrimoine-enleves', [PatrimoineEnleveController::class, 'index'])->name('patrimoine-enleves.index');

    // --- 11. EXPORT & STATISTIQUES ---

    // ExportController
    Route::get('/export-PDF', [ExportController::class, 'exportToPDF']);
    Route::get('/preview-pdf', [ExportController::class, 'previewPDF'])->name('preview.pdf');
    Route::get('/preview-licence-pdf', [ExportController::class, 'previewLicencePDF'])->name('preview.licence.pdf');

    // StatistiqueController
    Route::get('/stats/equipements', [StatistiqueController::class, 'getEquipementStats'])->name('stats.equipements');
    Route::get('/stats/maintenances', [StatistiqueController::class, 'getMaintenanceStats'])->name('stats.maintenances');
    Route::get('/stats/user-assignments/{userId}', [StatistiqueController::class, 'getUserAssignments'])->name('stats.user.assignments');

});