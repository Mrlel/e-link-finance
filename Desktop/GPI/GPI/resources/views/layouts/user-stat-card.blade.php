{{-- En-tête --}}
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between py-4 gap-3">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-person text-success"></i> Gestion du personnel
        </h1>
        <p class="text-muted mb-0">Gestion complète de tout les agents de la direction
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('users.create') }}" class="btn btn-success d-flex align-items-center gap-2">
            <i class="bi bi-plus-circle fs-5"></i> Ajouter un utilisateur
        </a>
        <div class="dropdown">
    <button class="btn btn-primary dropdown-toggle d-flex align-items-center gap-2 px-3 py-2" 
            type="button" 
            data-bs-toggle="dropdown" 
            aria-expanded="false">
      <i class="bi bi-download me-2"></i>
      Importer/Exporter
    </button>
    
    <ul class="dropdown-menu dropdown-menu-end p-2 border-0 shadow-lg rounded-3">
      
      <li>
        <button type="button" 
                class="dropdown-item d-flex align-items-center gap-2 rounded-2" 
                data-bs-toggle="modal" 
                data-bs-target="#importModal">
          <i class="bi bi-file-earmark-arrow-up fs-5 text-success"></i>
          <span>Importer (depuis Excel)</span>
        </button>
      </li>
      
      <li><hr class="dropdown-divider"></li>

      <li>
        <form action="{{ route('ExportUsers') }}" method="post" class="m-0">
          @csrf  
          <button type="submit" class="dropdown-item d-flex align-items-center gap-2 rounded-2">
            <i class="bi bi-file-earmark-pdf-fill fs-5 text-danger"></i>
            <span>Exporter en PDF</span>
          </button>
        </form>
      </li>
      
      <li>
        <form action="{{ route('ExportUsersExcel') }}" method="post" class="m-0">
          @csrf
          <button type="submit" class="dropdown-item d-flex align-items-center gap-2 rounded-2">
              <i class="bi bi-file-earmark-excel-fill fs-5 text-success"></i>
              <span>Exporter en Excel</span>
          </button>
        </form>
      </li>
    </ul>
  </div>
    </div>
</div>

<div class="row g-4 mb-5">
  <!-- Total utilisateurs -->
  <div class="col-12 col-sm-6 col-lg-4">
    <a href="/userlist" class="card border-0 bg-light shadow-sm p-5 position-relative stat-card text-decoration-none text-dark">
      <!-- Gradient bar -->
      <div class="position-absolute top-0 start-0 end-0"
           style="height:5px; background:linear-gradient(90deg,#ff7f50,#ffb347);"></div>

      <div class="d-flex justify-content-between align-items-start">
        <div>
          <h4 class="fw-bold text-muted mb-2">Nombre de personnel</h4>
          <div class="d-flex align-items-baseline gap-2">
            <span class="fs-1 fw-bold text-dark">{{ $users->count() }}</span>
            <span class="small text-muted">Personnel enregistré</span>
          </div>
          <div class="d-flex align-items-center gap-1 mt-2">
            <span class="small fw-semibold text-success">{{ $informaticiens->count() }}</span>
            <span class="small text-muted">personnel informaticiens</span>
          </div>
        </div>

        <!-- Icône -->
        <div class="d-flex align-items-center justify-content-center rounded p-3"
             style="background:linear-gradient(90deg,#ff7f50,#ffb347); width:64px; height:64px;">
          <i class="bi bi-people fs-3 text-white"></i>
        </div>
      </div>

      <!-- Pattern -->
      <div class="position-absolute bottom-0 end-0 opacity-25" style="width:100px; height:100px;">
        <i class="bi bi-people" style="font-size:100px;"></i>
      </div>
    </a>
  </div>

  <!-- Utilisateurs équipés -->
  <div class="col-12 col-sm-6 col-lg-4">
    <a href="/utilisateurs/equipes" class="card border-0 bg-light shadow-sm p-5 position-relative stat-card text-decoration-none text-dark">
      <div class="position-absolute top-0 start-0 end-0"
           style="height:5px; background:linear-gradient(135deg,#28a745,#20c997);"></div>

      <div class="d-flex justify-content-between align-items-start">
        <div class="py-3">
          <h4 class="fw-bold text-muted mb-2">Utilisateurs équipés</h4>
          <div class="d-flex align-items-baseline gap-2">
            <span class="fs-1 fw-bold text-dark">{{ $users_equipes->count() }}</span>
            <span class="small text-muted">Comptes activés</span>
          </div>
     
        </div>

        <div class="d-flex align-items-center justify-content-center rounded p-3"
             style="background:linear-gradient(135deg,#28a745,#20c997); width:64px; height:64px;">
          <i class="bi bi-laptop fs-3 text-white"></i>
        </div>
      </div>

      <div class="position-absolute bottom-0 end-0 opacity-25" style="width:100px; height:100px;">
        <i class="bi bi-laptop" style="font-size:100px;"></i>
      </div>
    </a>
  </div>

  <!-- Utilisateurs non équipés -->
  <div class="col-12 col-sm-6 col-lg-4">
    <a href="/utilisateurs/non-equipes" class="card border-0 bg-light shadow-sm p-5 position-relative stat-card text-decoration-none text-dark">
      <div class="position-absolute top-0 start-0 end-0"
           style="height:5px; background:linear-gradient(90deg,#ff7f50,#ffb347);"></div>

      <div class="d-flex justify-content-between align-items-start">
        <div class="py-3">
          <h4 class="fw-bold text-muted mb-2">Utilisateurs non équipés</h4>
          <div class="d-flex align-items-baseline gap-2">
            <span class="fs-1 fw-bold text-dark">{{ $users_non_equipes->count() }}</span>
            <span class="small text-muted">Comptes inactifs</span>
          </div>
  
        </div>

        <div class="d-flex align-items-center justify-content-center rounded p-3"
             style="background:linear-gradient(90deg,#ff7f50,#ffb347); width:64px; height:64px;">
          <i class="bi bi-slash-circle fs-3 text-white"></i>
        </div>
      </div>

      <div class="position-absolute bottom-0 end-0 opacity-25" style="width:100px; height:100px;">
        <i class="bi bi-slash-circle" style="font-size:100px;"></i>
      </div>
    </a>
  </div>
</div>
<!-- Barre de recherche -->
<form method="GET" action="{{ url()->current() }}" class="card shadow-sm mb-4">
    <div class="card-body py-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-{{ isset($roles) ? 4 : 5 }}">
                <label class="form-label small text-muted mb-1">Recherche</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="q" id="searchInput" value="{{ request('q') }}" class="form-control" placeholder="Nom, matricule, email…">
                </div>
            </div>
            @isset($roles)
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Rôle</label>
                <select name="role" class="form-select">
                    <option value="">Tous les rôles</option>
                    @foreach($roles as $value => $label)
                        <option value="{{ $value }}" @selected(request('role') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Fonction</label>
                <select name="fonction_id" class="form-select">
                    <option value="">Toutes les fonctions</option>
                    @foreach($fonctions as $fonction)
                        <option value="{{ $fonction->id }}" @selected((string) request('fonction_id') === (string) $fonction->id)>{{ $fonction->fonction }}</option>
                    @endforeach
                </select>
            </div>
            @endisset
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-outline-primary flex-fill"><i class="bi bi-search"></i> Filtrer</button>
                @if(request()->filled('q') || request()->filled('role') || request()->filled('fonction_id'))
                    <a href="{{ url()->current() }}" class="btn btn-outline-secondary">Réinitialiser</a>
                @endif
            </div>
        </div>
    </div>
</form>

    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                <h4>Importer des utilisateurs</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="import-section">
                    <p class="small mb-2">
                        <code>Role</code> optionnel (par défaut <code>user</code>) parmi : Utilisateur, Chef de service, Sous-directeur,
                        Gestionnaire de parc, Admin, Technicien, Directeur, Ministre, Point focal, Service gestionnaire — la casse et les accents n'ont pas d'importance.
                        Une valeur non reconnue n'annule pas la ligne : elle est importée avec le rôle « Utilisateur » par défaut, signalé après import.
                    </p>
                    <a href="{{ route('import.users.template') }}" class="btn btn-outline-primary btn-sm mb-4 d-flex align-items-center gap-2">
                        <i class="fas fa-file-excel"></i> Télécharger le modèle Excel ici
                    </a>
                    <form action="{{ route('import.users') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="fichier">Fichier Excel (.xlsx, .xls)</label>
                            <input type="file" name="fichier" class="form-control" accept=".xlsx,.xls" required>
                        </div>
                        <button type="submit" class="btn btn-success mt-3 w-100"><i class="fas fa-download"></i> Importer</button>
                    </form>
                </div>
                </div>
               
            </div>
        </div>
    </div>

        <div class="modal fade" id="fonctionModal" tabindex="-1" aria-labelledby="fonctionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                <h4>Fonction</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="import-section">
                    <form action="{{ route('fonctions.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="fichier">Fonction agent</label>
                            <input type="text" name="fonction" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success mt-3 w-100"><i class="fas fa-download"></i> Enregistrer</button>
                    </form>
                </div>
                </div>
               
            </div>
        </div>
    </div>








