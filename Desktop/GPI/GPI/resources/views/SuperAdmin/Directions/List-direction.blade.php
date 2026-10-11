@extends('layouts.superDashboard')

@section('content')
<div class="py-4">
  <!-- Header -->
  <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-3">
    <div>
      <h1 class="fw-bold font-serif mb-1">Gestion des directions ({{ $directions->total() }})</h1>
      <p class="small text-muted mb-0">Structure organisationnelle du ministère</p>
    </div>
    <a href="{{ route('directions.create') }}" class="btn btn-success d-flex align-items-center gap-2">
      <i class="fas fa-plus"></i> Nouvelle direction
    </a>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <!-- Résumé rapide -->
  <div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-lg-3">
      <a href="{{ route('directions.list-direction') }}?statut=active" class="card border-0 bg-light shadow-sm p-4 position-relative text-decoration-none text-dark h-100 sa-stat-card">
        <div class="position-absolute top-0 start-0 end-0" style="height:5px; background:linear-gradient(90deg,var(--sa-green-start),var(--sa-green-end));"></div>
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h4 class="fw-bold text-muted mb-2" style="font-size:.8rem; text-transform:uppercase; letter-spacing:.5px;">Actives</h4>
            <div class="fs-1 fw-bold text-dark">{{ $directionsActives }}</div>
            <div class="small text-muted">Directions en fonctionnement</div>
          </div>
          <div class="d-flex align-items-center justify-content-center rounded p-3 flex-shrink-0"
               style="background:linear-gradient(135deg,var(--sa-green-start),var(--sa-green-end)); width:56px; height:56px;">
            <i class="fas fa-check fs-4 text-white"></i>
          </div>
        </div>
      </a>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
      <a href="{{ route('directions.list-direction') }}?statut=inactive" class="card border-0 bg-light shadow-sm p-4 position-relative text-decoration-none text-dark h-100 sa-stat-card">
        <div class="position-absolute top-0 start-0 end-0" style="height:5px; background:linear-gradient(90deg,var(--sa-gray-start),var(--sa-gray-end));"></div>
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h4 class="fw-bold text-muted mb-2" style="font-size:.8rem; text-transform:uppercase; letter-spacing:.5px;">Inactives</h4>
            <div class="fs-1 fw-bold text-dark">{{ $directionsInactives }}</div>
            <div class="small text-muted">Directions à l'arrêt</div>
          </div>
          <div class="d-flex align-items-center justify-content-center rounded p-3 flex-shrink-0"
               style="background:linear-gradient(135deg,var(--sa-gray-start),var(--sa-gray-end)); width:56px; height:56px;">
            <i class="fas fa-pause fs-4 text-white"></i>
          </div>
        </div>
      </a>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
      <div class="card border-0 bg-light shadow-sm p-4 position-relative h-100 sa-stat-card">
        <div class="position-absolute top-0 start-0 end-0" style="height:5px; background:linear-gradient(90deg,var(--sa-blue-start),var(--sa-blue-end));"></div>
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h4 class="fw-bold text-muted mb-2" style="font-size:.8rem; text-transform:uppercase; letter-spacing:.5px;">Administrées</h4>
            <div class="fs-1 fw-bold text-dark">{{ $directionsAdministrés }}</div>
            <div class="small text-muted">Avec un administrateur</div>
          </div>
          <div class="d-flex align-items-center justify-content-center rounded p-3 flex-shrink-0"
               style="background:linear-gradient(135deg,var(--sa-blue-start),var(--sa-blue-end)); width:56px; height:56px;">
            <i class="fas fa-user-shield fs-4 text-white"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
      <a href="{{ route('directions.list-admin') }}?role=admin" class="card border-0 bg-light shadow-sm p-4 position-relative text-decoration-none text-dark h-100 sa-stat-card">
        <div class="position-absolute top-0 start-0 end-0" style="height:5px; background:linear-gradient(90deg,var(--sa-orange-start),var(--sa-orange-end));"></div>
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h4 class="fw-bold text-muted mb-2" style="font-size:.8rem; text-transform:uppercase; letter-spacing:.5px;">Admins</h4>
            <div class="fs-1 fw-bold text-dark">{{ $admins->count() }}</div>
            <div class="small text-muted">Comptes administrateur</div>
          </div>
          <div class="d-flex align-items-center justify-content-center rounded p-3 flex-shrink-0"
               style="background:linear-gradient(135deg,var(--sa-orange-start),var(--sa-orange-end)); width:56px; height:56px;">
            <i class="fas fa-users fs-4 text-white"></i>
          </div>
        </div>
      </a>
    </div>
  </div>

  <!-- Barre de recherche + filtres -->
  <form method="GET" action="{{ url()->current() }}" class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
      <div class="row g-2 align-items-end">
        <div class="col-md-5">
          <label class="form-label small text-muted mb-1">Recherche</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                   placeholder="Nom ou code de la direction…">
          </div>
        </div>
        <div class="col-md-3">
          <label class="form-label small text-muted mb-1">Statut</label>
          <select name="statut" class="form-select">
            <option value="">Tous les statuts</option>
            <option value="active" @selected(request('statut') === 'active')>Active</option>
            <option value="inactive" @selected(request('statut') === 'inactive')>Inactive</option>
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label small text-muted mb-1">Type</label>
          <select name="type" class="form-select">
            <option value="">Tous</option>
            <option value="centrale" @selected(request('type') === 'centrale')>Centrale</option>
            <option value="déconcentrée" @selected(request('type') === 'déconcentrée')>Déconcentrée</option>
            <option value="partenaire" @selected(request('type') === 'partenaire')>Partenaire</option>
          </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
          <button type="submit" class="btn btn-outline-primary flex-fill"><i class="fas fa-filter"></i> Filtrer</button>
          @if(request()->filled('q') || request()->filled('statut') || request()->filled('type'))
            <a href="{{ url()->current() }}" class="btn btn-outline-secondary">Réinit.</a>
          @endif
        </div>
      </div>
    </div>
  </form>

  <!-- Grille des directions -->
  @if($directions->isEmpty())
    <div class="text-center py-5 text-muted bg-white rounded-3 shadow-sm">
      <i class="fas fa-building fa-3x mb-3 opacity-25"></i>
      <p>Aucune direction trouvée.</p>
      <a href="{{ route('directions.create') }}" class="btn btn-success btn-sm">
        <i class="fas fa-plus me-1"></i> Créer la première direction
      </a>
    </div>
  @else
  <div class="row g-3">
    @foreach ($directions as $direction)
    <div class="col-12 col-md-6 col-lg-4">
      <div class="card border-0 shadow-sm h-100" style="border-radius:12px; transition: transform .2s, box-shadow .2s;"
           onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,.1)';"
           onmouseout="this.style.transform='';this.style.boxShadow='';">

        <!-- Barre de couleur selon statut -->
        <div style="height:4px; border-radius:12px 12px 0 0;
             background: {{ $direction->statut === 'active' ? 'linear-gradient(90deg,#009E60,#00C778)' : '#adb5bd' }};"></div>

        <div class="card-body p-3">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <!-- Logo + statut -->
            <div>
              <div class="rounded-2 overflow-hidden mb-2" style="width:48px;height:48px;background:#f8f9fa;">
                @if($direction->logo)
                  <img src="{{ asset('storage/' . $direction->logo) }}" alt="logo"
                       style="width:100%;height:100%;object-fit:cover;">
                @else
                  <div class="d-flex align-items-center justify-content-center h-100 fw-bold text-success" style="font-size:18px;">
                    {{ strtoupper(substr($direction->nom_direction, 0, 2)) }}
                  </div>
                @endif
              </div>
              @if($direction->statut === 'active')
                <span class="badge bg-success bg-opacity-10 text-success border border-success" style="font-size:10px;">Active</span>
              @else
                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary" style="font-size:10px;">Inactive</span>
              @endif
            </div>

            <!-- Actions -->
            <div class="dropdown">
              <button class="btn btn-sm btn-light border" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-ellipsis-v"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                <li>
                  <a href="{{ route('directions.edit', $direction->id) }}" class="dropdown-item">
                    <i class="fas fa-pen me-2 text-primary"></i>Modifier
                  </a>
                </li>
                <li>
                  <a href="{{ route('directions.assign-admin', $direction->id) }}" class="dropdown-item">
                    <i class="fas fa-user-plus me-2 text-success"></i>Assigner un admin
                  </a>
                </li>
                <li><hr class="dropdown-divider my-1"></li>
                <li>
                  <form action="{{ route('directions.destroy', $direction->id) }}" method="POST"
                        onsubmit="return confirm('Supprimer la direction {{ addslashes($direction->nom_direction) }} ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="dropdown-item text-danger">
                      <i class="fas fa-trash me-2"></i>Supprimer
                    </button>
                  </form>
                </li>
              </ul>
            </div>
          </div>

          <!-- Informations -->
          <h6 class="fw-bold mb-0">{{ $direction->code_direction }}</h6>
          <p class="text-muted small mb-3" style="line-height:1.3;">{{ $direction->nom_direction }}</p>

          <div class="d-flex justify-content-between align-items-center border-top pt-2">
            <div class="small text-muted">
              <i class="fas fa-user-tie me-1"></i>
              <span class="fw-medium text-dark">{{ $direction->responsable ?? 'Non défini' }}</span>
            </div>
            <div class="d-flex align-items-center gap-1 text-muted small">
              <i class="fas fa-users"></i>
              <!-- Utilise users_count chargé avec withCount() — pas de N+1 -->
              <span class="fw-semibold">{{ $direction->users_count }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
  <div class="d-flex justify-content-between align-items-center mt-3">
    <p class="small text-muted mb-0">
      {{ $directions->firstItem() }}–{{ $directions->lastItem() }} sur {{ $directions->total() }} direction(s)
    </p>
    {{ $directions->links('pagination::bootstrap-5') }}
  </div>
  @endif
</div>
@endsection
