@extends('layouts.main-board')
@section('title', 'Parc Automobile')
@section('content')

<style>
    .select2-container--default .select2-selection--single {
        background-color: #f9fafb !important; border: 1px solid #dee2e6 !important;
        border-radius: 5px !important; height: 40px !important; padding: 5px 12px !important;
        display: flex; align-items: center; font-size: 15px;
    }
    .select2-selection__rendered { line-height: 28px !important; color: #374151 !important; }
    .select2-selection__arrow { height: 100% !important; right: 10px !important; }
    .select2-container { width: 100% !important; }
    .select2-results__option--highlighted { background: #198754 !important; color: white !important; }
    .badge-etat-NEUF          { background:#198754; }
    .badge-etat-BON           { background:#0d6efd; }
    .badge-etat-MOYEN         { background:#ffc107; color:#000; }
    .badge-etat-MAUVAIS       { background:#fd7e14; }
    .badge-etat-HORS\ SERVICE { background:#dc3545; }
</style>

{{-- En-tête --}}
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between py-4 gap-3">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-car-front-fill text-success"></i> Gestion du Parc Automobile
        </h1>
        <p class="text-muted mb-0">Véhicules de la direction • Affectations & Historique</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('vehicules.create') }}" class="btn btn-success d-flex align-items-center gap-2">
            <i class="bi bi-plus-circle fs-5"></i> Ajouter un véhicule
        </a>
        <a href="{{ route('vehicules.historique') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
            <i class="bi bi-clock-history fs-5"></i> Historique
        </a>
        <a href="{{ route('sorties-vehicules.index') }}" class="btn btn-outline-danger d-flex align-items-center gap-2">
            <i class="bi bi-box-arrow-right fs-5"></i> Sorties
        </a>
    </div>
</div>

@include('layouts.message')
@include('layouts.vehicules-stat-card')
{{-- Filtres --}}
<form method="GET" action="{{ route('vehicules.index') }}" class="card shadow-sm mb-4">
    <div class="card-body py-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Recherche</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control"
                           placeholder="Immatriculation, marque…" value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1">Catégorie</label>
                <select name="categorie" class="form-select">
                    <option value="">Toutes</option>
                    <option value="auto"  {{ request('categorie') == 'auto'  ? 'selected' : '' }}>Auto</option>
                    <option value="moto"  {{ request('categorie') == 'moto'  ? 'selected' : '' }}>Moto</option>
                </select>
            </div>
            <div class="col-md-1">
                <label class="form-label small text-muted mb-1">État</label>
                <select name="etat" class="form-select">
                    <option value="">Tous</option>
                    @foreach(['NEUF','BON','MOYEN','MAUVAIS','HORS SERVICE'] as $e)
                        <option value="{{ $e }}" {{ request('etat') == $e ? 'selected' : '' }}>{{ $e }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1">Statut</label>
                <select name="statut" class="form-select">
                    <option value="">Tous</option>
                    @foreach(['disponible','affecté','en maintenance','hors service','enlevé'] as $s)
                        <option value="{{ $s }}" {{ request('statut') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1">Acquisition</label>
                <select name="mode_acquisition" class="form-select">
                    <option value="">Tous</option>
                    <option value="BUDGET ETAT" {{ request('mode_acquisition') == 'BUDGET ETAT' ? 'selected' : '' }}>Budget État</option>
                    <option value="DON"         {{ request('mode_acquisition') == 'DON'         ? 'selected' : '' }}>Don</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel"></i>
                </button>
                <a href="{{ route('vehicules.index') }}" class="btn btn-outline-secondary w-100">
                    Réinitialiser
                </a>
            </div>
        </div>
    </div>
</form>

{{-- Tableau --}}
@if($vehicules->count() > 0)

    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>Immatriculation</th>
                <th>Catégorie</th>
                <th>Marque / Modèle</th>
                <th>Couleur</th>
                <th>État</th>
                <th>Statut</th>
                <th>Affecté à</th>
                <th>Acquisition</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vehicules as $v)
            @php
                $statutColor = match($v->statut) {
                    'affecté'        => 'bg-primary',
                    'en maintenance' => 'bg-warning text-dark',
                    'hors service'   => 'bg-danger',
                    default          => 'bg-success',
                };
            @endphp
            <tr>
                <td>
                    <a href="{{ route('vehicules.show', $v->id) }}" class="fw-semibold text-decoration-none">
                        {{ $v->immatriculation }}
                    </a>
                </td>
                <td>
                    <i class="bi {{ $v->categorie === 'moto' ? 'bi-bicycle' : 'bi-car-front' }} me-1"></i>
                    {{ ucfirst($v->categorie) }}
                </td>
                <td>{{ $v->marque ?? '—' }} {{ $v->modele ? '/ '.$v->modele : '' }}</td>
                <td>{{ $v->couleur ?? '—' }}</td>
                <td>{{ $v->etat }}</td>
                <td><span class="badge {{ $statutColor }}">{{ ucfirst($v->statut) }}</span></td>
                <td>
                    @if($v->affectationActive && $v->affectationActive->user)
                        <span class="d-flex align-items-center gap-1">
                            <i class="bi bi-person-fill text-primary"></i>
                            {{ $v->affectationActive->user->nom }} {{ $v->affectationActive->user->prenom }}
                        </span>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>{{ $v->mode_acquisition }}</td>
                <td class="text-center">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('vehicules.show', $v->id) }}">
                                    <i class="bi bi-eye me-2 text-info"></i> Détails
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('vehicules.edit', $v->id) }}">
                                    <i class="bi bi-pencil me-2 text-primary"></i> Modifier
                                </a>
                            </li>
                            @if($v->statut === 'disponible')
                            <li>
                                <button class="dropdown-item d-flex align-items-center gap-2"
                                        data-bs-toggle="modal" data-bs-target="#affecterModal"
                                        data-vehicule-id="{{ $v->id }}"
                                        data-vehicule-immat="{{ $v->immatriculation }}">
                                    <i class="bi bi-person-check text-success"></i> Affecter
                                </button>
                            </li>
                            @endif
                            @if($v->statut !== 'enlevé')
                            <li>
                                <a class="dropdown-item" href="{{ route('sorties-vehicules.create', $v->id) }}">
                                    <i class="bi bi-box-arrow-right me-2 text-danger"></i> Enregistrer une sortie
                                </a>
                            </li>
                            @endif
                            
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('vehicules.destroy', $v->id) }}" method="POST"
                                      onsubmit="return confirm('Supprimer ce véhicule ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-trash me-2"></i> Supprimer
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

@else
<div class="text-center py-5">
    <i class="bi bi-car-front fs-1 text-muted"></i>
    <p class="text-muted mt-2">Aucun véhicule trouvé</p>
    <a href="{{ route('vehicules.create') }}" class="btn btn-success mt-2">
        <i class="bi bi-plus-circle me-1"></i> Ajouter un véhicule
    </a>
</div>
@endif

{{-- ===== MODAL AFFECTATION ===== --}}
<div class="modal fade" id="affecterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-check me-2"></i>Affecter un véhicule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="affecterForm" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <p class="text-muted mb-3">Véhicule : <strong id="affecterImmat"></strong></p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Utilisateur <span class="text-danger">*</span></label>
                        <select id="affecterUserId" name="user_id" class="form-select" required>
                            <option value=""></option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->nom }} {{ $user->prenom }} — {{ $user->matricule }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i> Confirmer l'affectation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(function () {
    $('#affecterUserId').select2({
        dropdownParent: $('#affecterModal'),
        placeholder: '— Rechercher un utilisateur —',
        allowClear: true,
    });

    $('#affecterModal').on('show.bs.modal', function (e) {
        const btn   = e.relatedTarget;
        const id    = btn.getAttribute('data-vehicule-id');
        const immat = btn.getAttribute('data-vehicule-immat');
        $('#affecterImmat').text(immat);
        $('#affecterForm').attr('action', '/vehicules/' + id + '/affecter');
        $('#affecterUserId').val(null).trigger('change');
    });
});
</script>

@endsection
