@extends('layouts.superDashboard')

@section('content')

<div class="py-4">
  <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-3">
    <div>
      <h1 class="fw-bold font-serif mb-1">Gestion des utilisateurs ({{ $admins->total() }})</h1>
      <p class="small text-muted mb-0">Gérez les comptes utilisateurs de toutes les directions</p>
    </div>
    <a href="{{ route('directions.create-admin') }}" class="btn btn-success d-flex align-items-center gap-2">
      <i class="fas fa-plus"></i> Nouvel utilisateur
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

  <!-- Barre de recherche + filtres -->
  <form method="GET" action="{{ url()->current() }}" class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
      <div class="row g-2 align-items-end">
        <div class="col-md-4">
          <label class="form-label small text-muted mb-1">Recherche</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                   placeholder="Nom, matricule, email…">
          </div>
        </div>
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
          <label class="form-label small text-muted mb-1">Direction</label>
          <select name="direction_id" class="form-select">
            <option value="">Toutes les directions</option>
            @foreach($directions as $direction)
              <option value="{{ $direction->id }}" @selected((string) request('direction_id') === (string) $direction->id)>{{ $direction->nom_direction }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
          <button type="submit" class="btn btn-outline-primary flex-fill"><i class="fas fa-filter"></i> Filtrer</button>
          @if(request()->filled('q') || request()->filled('role') || request()->filled('direction_id'))
            <a href="{{ url()->current() }}" class="btn btn-outline-secondary">Réinitialiser</a>
          @endif
        </div>
      </div>
    </div>
  </form>

  @if($admins->isEmpty())
    <div class="text-center py-5 text-muted bg-white rounded-3 shadow-sm">
      <i class="fas fa-users fa-3x mb-3 opacity-25"></i>
      <p class="mb-0">Aucun utilisateur trouvé.</p>
    </div>
  @else
    <div class="table-responsive shadow-sm rounded">
      <table class="table table-striped table-hover align-middle mb-0 bg-white">
        <thead class="table-dark">
          <tr>
            <th>Utilisateur</th>
            <th>Matricule</th>
            <th>Contact</th>
            <th>Direction</th>
            <th>Email</th>
            <th>Rôle</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($admins as $admin)
          <tr>
            <td>
              <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle overflow-hidden flex-shrink-0" style="width:36px;height:36px;">
                  @if($admin->direction && $admin->direction->logo)
                    <img src="{{ asset('storage/' . $admin->direction->logo) }}" alt="logo" style="width:100%;height:100%;object-fit:cover;">
                  @else
                    <div class="d-flex align-items-center justify-content-center h-100 w-100 bg-success text-white fw-bold" style="font-size:14px;">
                      {{ strtoupper(substr($admin->nom, 0, 1)) }}
                    </div>
                  @endif
                </div>
                <span class="fw-medium">{{ $admin->nom }} {{ $admin->prenom }}</span>
              </div>
            </td>
            <td>{{ $admin->matricule ?? '—' }}</td>
            <td>{{ $admin->contact ?? '—' }}</td>
            <td>
              @if($admin->direction)
                <span class="badge bg-light text-dark border">{{ $admin->direction->nom_direction }}</span>
              @else
                <span class="text-muted small">Non affecté</span>
              @endif
            </td>
            <td>{{ $admin->email }}</td>
            <td>
              @php $roleInfo = $roleBadges[$admin->role] ?? ['label' => $roles[$admin->role] ?? $admin->role, 'class' => 'bg-light text-dark border']; @endphp
              <span class="badge {{ $roleInfo['class'] }}">{{ $roleInfo['label'] }}</span>
            </td>
            <td class="text-center">
              <div class="dropdown">
                <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                        id="dropdownMenuButtonAdmin{{ $admin->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bi bi-three-dots-vertical"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButtonAdmin{{ $admin->id }}">
                  <li>
                    <a class="dropdown-item" href="{{ route('directions.edit-admin', $admin->id) }}">
                      <i class="fas fa-pen text-primary me-2"></i> Modifier
                    </a>
                  </li>
                  @if($admin->role !== 'superadmin')
                  <li>
                    <form action="{{ route('directions.reset-password-admin', $admin->id) }}" method="POST"
                          onsubmit="return confirm('Réinitialiser le mot de passe de {{ addslashes($admin->nom . ' ' . $admin->prenom) }} ?')">
                      @csrf
                      @method('PUT')
                      <button type="submit" class="dropdown-item">
                        <i class="fas fa-key text-warning me-2"></i> Réinitialiser le mot de passe
                      </button>
                    </form>
                  </li>
                  <li><hr class="dropdown-divider"></li>
                  <li>
                    <form action="{{ route('directions.destroy-admin', $admin->id) }}" method="POST"
                          onsubmit="return confirm('Voulez-vous vraiment supprimer {{ addslashes($admin->nom . ' ' . $admin->prenom) }} ?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="dropdown-item text-danger">
                        <i class="fas fa-trash me-2"></i> Supprimer
                      </button>
                    </form>
                  </li>
                  @endif
                </ul>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="d-flex justify-content-between align-items-center mt-3">
      <p class="small text-muted mb-0">
        {{ $admins->firstItem() }}–{{ $admins->lastItem() }} sur {{ $admins->total() }} utilisateur(s)
      </p>
      {{ $admins->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>
@endsection
