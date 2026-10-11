@extends('layouts.main-board')
@section('title', 'Postes informatiques')
@section('content')
{{-- Select2 CSS --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        background-color: #f9fafb !important;
        border: 1px solid #dee2e6 !important;
        border-radius: 5px !important;
        height: 40px !important;
        padding: 5px 12px !important;
        display: flex; align-items: center;
        font-size: 15px;
    }
    .select2-selection__rendered { line-height: 28px !important; color: #374151 !important; }
    .select2-selection__arrow { height: 100% !important; right: 10px !important; }
    .select2-container { width: 100% !important; }
    .select2-results__option--highlighted { background: #3b82f6 !important; color: white !important; }

    .details-row { background-color: #f8f9fa; }
    .details-cell { padding: 1.25rem !important; }
    .equipment-card.main-unit {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 1.25rem;
        max-width: 640px;
    }
    .equipment-card.main-unit .detail-row { display: flex; gap: .5rem; margin-bottom: .5rem; font-size: .85rem; }
    .equipment-card.main-unit .detail-label { font-weight: 700; color: #6c757d; width: 140px; }
    .qr-image { max-width: 160px; height: auto; border: 1px solid #dee2e6; border-radius: 8px; }
</style>

<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between py-4 gap-3">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-pc-display text-success"></i> Postes Informatiques ({{ $postes->count() }})
        </h1>
        <p class="text-muted mb-0">Postes de travail assemblés à partir des équipements du parc</p>
    </div>
    <a href="{{ route('poste-complet.create') }}" class="btn btn-success d-flex align-items-center gap-2">
        <i class="bi bi-plus-circle fs-5"></i> Créer un nouveau poste
    </a>
</div>

@include('layouts.message')

{{-- Filtres --}}
<form method="GET" action="{{ route('list_poste.index') }}" class="card shadow-sm mb-4">
    <div class="card-body py-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label small text-muted mb-1">Recherche</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control"
                           placeholder="Code poste, emplacement, utilisateur…" value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Statut</label>
                <select name="statut" class="form-select">
                    <option value="">Tous</option>
                    <option value="affecte" {{ request('statut') == 'affecte' ? 'selected' : '' }}>Affecté</option>
                    <option value="non_affecte" {{ request('statut') == 'non_affecte' ? 'selected' : '' }}>Non affecté</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel"></i> Filtrer</button>
                <a href="{{ route('list_poste.index') }}" class="btn btn-outline-secondary w-100">Réinitialiser</a>
            </div>
        </div>
    </div>
</form>

@if($postes->count() > 0)
<div class="table-responsive shadow-sm rounded">
    <table class="table table-striped table-hover align-middle mb-0">
        <thead class="table-dark">
            <tr>
                <th>Code Poste</th>
                <th>Emplacement</th>
                <th>Utilisateur</th>
                <th>Composants</th>
                <th>Date création</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($postes as $poste)
            <tr>
                <td><strong>{{ $poste->code_poste }}</strong></td>
                <td>{{ $poste->emplacement ?? '—' }}</td>
                <td>
                    @if($poste->user)
                        <span class="badge bg-primary">
                            <i class="bi bi-person-fill"></i> {{ $poste->user->nom }} {{ $poste->user->prenom }}
                        </span>
                    @else
                        <span class="badge bg-secondary">Non affecté</span>
                    @endif
                </td>
                <td><span class="badge bg-success">{{ $poste->equipements->count() }}</span></td>
                <td class="small">{{ $poste->created_at->format('d/m/Y') }}</td>
                <td class="text-center">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <button class="dropdown-item toggle-details" data-post-id="{{ $poste->id }}">
                                    <i class="bi bi-eye me-2 text-info"></i>
                                    <span class="show-text">Voir les détails</span>
                                    <span class="hide-text" style="display:none">Masquer les détails</span>
                                </button>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('postes.edit', $poste->id) }}">
                                    <i class="bi bi-pencil me-2 text-primary"></i> Modifier
                                </a>
                            </li>
                            @if($poste->user)
                            <li>
                                <form action="{{ route('postes.retirer', $poste->id) }}" method="POST"
                                      onsubmit="return confirm('Retirer {{ $poste->user->nom }} {{ $poste->user->prenom }} de ce poste ?')">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-person-dash text-warning me-2"></i> Retirer l'utilisateur
                                    </button>
                                </form>
                            </li>
                            @else
                            <li>
                                <button class="dropdown-item d-flex align-items-center gap-2"
                                        data-bs-toggle="modal" data-bs-target="#modalAffecterPoste{{ $poste->id }}">
                                    <i class="bi bi-person-check text-success"></i> Affecter
                                </button>
                            </li>
                            @endif
                            <li>
                                <a class="dropdown-item" href="{{ route('postes.print', $poste->id) }}">
                                    <i class="bi bi-printer me-2 text-secondary"></i> Imprimer la fiche
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('postes.destroy', $poste->id) }}" method="POST"
                                      onsubmit="return confirm('Supprimer le poste {{ $poste->code_poste }} ?{{ $poste->user ? ' Il est actuellement affecté à ' . $poste->user->nom . '.' : '' }}{{ $poste->equipements->count() ? ' ' . $poste->equipements->count() . ' équipement(s) y sont rattachés.' : '' }}')">
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

            <tr class="details-row" id="details-{{ $poste->id }}" style="display:none">
                <td colspan="6" class="details-cell">
                    @if($poste->equipements->isEmpty())
                        <p class="text-muted mb-0">Aucun équipement associé à ce poste.</p>
                    @else
                        @php
                            $uc = $poste->equipements->where('categorie', 'unite centrale')->first();
                            $autresGroupes = $poste->equipements
                                ->where('id', '!=', $uc?->id)
                                ->groupBy('categorie');
                        @endphp

                        @if($uc)
                        <div class="d-flex flex-wrap gap-4 mb-4">
                            <div class="equipment-card main-unit flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="fw-bold mb-0"><i class="bi bi-pc-display-horizontal me-1"></i> Unité centrale</h6>
                                    <span class="badge bg-{{ $uc->etat === 'bon' ? 'success' : ($uc->etat === 'moyen' ? 'warning text-dark' : 'danger') }}">
                                        {{ ucfirst($uc->etat) }}
                                    </span>
                                </div>
                                <div class="detail-row"><span class="detail-label">N° Série :</span> {{ $uc->numero_serie }}</div>
                                <div class="detail-row"><span class="detail-label">Marque / Modèle :</span> {{ $uc->marque ?? 'N/A' }} {{ $uc->modele ?? '' }}</div>
                                <div class="detail-row"><span class="detail-label">Processeur :</span> {{ $uc->processeur ?? 'N/A' }}</div>
                                <div class="detail-row"><span class="detail-label">RAM :</span> {{ $uc->ram ?? 'N/A' }} Go</div>
                                <div class="detail-row"><span class="detail-label">Système :</span> {{ $uc->systeme ?? 'N/A' }}</div>
                                <div class="detail-row"><span class="detail-label">Adresse MAC :</span> {{ $uc->adresse_mac ?? 'N/A' }}</div>
                                <a href="/equipement_details/{{ $uc->id }}" class="btn btn-sm btn-outline-primary mt-2">
                                    Voir la fiche <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            </div>
                            @if($poste->qr_code)
                            <div class="text-center">
                                <img src="{{ asset('storage/' . $poste->qr_code) }}" alt="QR Code" class="qr-image">
                                <div class="mt-2">
                                    <a href="{{ asset('storage/' . $poste->qr_code) }}" download="qr-code-{{ $poste->code_poste }}.svg" class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-download"></i> QR Code
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif

                        @foreach($autresGroupes as $categorie => $equipements)
                        <div class="mb-3">
                            <h6 class="bg-secondary text-white p-2 rounded-top mb-0">{{ ucfirst($categorie) }} ({{ $equipements->count() }})</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0 bg-white">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Utilisateur</th>
                                            <th>N° Série</th>
                                            <th>Marque</th>
                                            <th>Modèle</th>
                                            <th>État</th>
                                            <th>Date</th>
                                            <th class="text-center">Détails</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($equipements as $equipement)
                                        @php
                                            $lastAssignment = $equipement->assignments->whereNull('returned_at')->sortByDesc('assigned_at')->first();
                                        @endphp
                                        <tr>
                                            <td class="small">
                                                @if($lastAssignment && $lastAssignment->user)
                                                    {{ $lastAssignment->user->nom }} {{ $lastAssignment->user->prenom }}
                                                @else
                                                    <span class="text-muted">Non assigné</span>
                                                @endif
                                            </td>
                                            <td class="small">{{ $equipement->numero_serie }}</td>
                                            <td class="small">{{ $equipement->marque ?? 'N/A' }}</td>
                                            <td class="small">{{ $equipement->modele ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $equipement->etat === 'bon' ? 'success' : ($equipement->etat === 'moyen' ? 'warning text-dark' : 'danger') }}">
                                                    {{ ucfirst($equipement->etat) }}
                                                </span>
                                            </td>
                                            <td class="small">{{ $equipement->created_at->format('d/m/Y') }}</td>
                                            <td class="text-center">
                                                <a href="/equipement_details/{{ $equipement->id }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="text-center py-5">
    <i class="bi bi-pc-display fs-1 text-muted"></i>
    <p class="text-muted mt-2">
        @if(request('search') || request('statut'))
            Aucun poste ne correspond à ces critères.
        @else
            Aucun poste créé pour l'instant.
        @endif
    </p>
    <a href="{{ route('poste-complet.create') }}" class="btn btn-success mt-2">
        <i class="bi bi-plus-circle me-1"></i> Créer un poste
    </a>
</div>
@endif

{{-- ===== MODALS AFFECTATION ===== --}}
@foreach($postes as $poste)
@if(!$poste->user)
<div class="modal fade" id="modalAffecterPoste{{ $poste->id }}" tabindex="-1" aria-labelledby="modalAffecterPosteLabel{{ $poste->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAffecterPosteLabel{{ $poste->id }}">Affecter le poste {{ $poste->code_poste }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('assigner_poste', $poste->id) }}" method="POST">
                    @csrf
                    <label class="form-label fw-semibold">Utilisateur <span class="text-danger">*</span></label>
                    <select name="user_id" class="form-control" id="user_id_select2_{{ $poste->id }}" required>
                        <option value=""></option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->nom }} {{ $user->prenom }} - {{ $user->matricule }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-success mt-3 w-100">
                        <i class="bi bi-person-check me-1"></i> Affecter
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endforeach

<script>
$(document).ready(function() {
    $('.toggle-details').click(function() {
        var postId = $(this).data('post-id');
        var details = $('#details-' + postId);

        if (details.is(':visible')) {
            details.hide();
            $(this).find('.show-text').show();
            $(this).find('.hide-text').hide();
        } else {
            details.show();
            $(this).find('.show-text').hide();
            $(this).find('.hide-text').show();
        }
    });

    @foreach($postes as $poste)
        @if(!$poste->user)
        $('#user_id_select2_{{ $poste->id }}').select2({
            dropdownParent: $('#modalAffecterPoste{{ $poste->id }}'),
            placeholder: '— Rechercher un utilisateur —',
            allowClear: true,
        });
        @endif
    @endforeach

    // .table-responsive a overflow-x:auto, ce qui force le navigateur à clipper aussi l'axe Y
    // (règle CSS : un seul axe "visible" avec l'autre "auto" fait passer les deux en "auto").
    // Le menu du bouton "..." se retrouvait donc coincé dans le conteneur, d'où le scroll au
    // lieu du survol. En stratégie "fixed", Popper positionne le menu par rapport à la fenêtre
    // et il n'est plus soumis au clipping de son ancêtre défilant.
    document.querySelectorAll('.table-responsive [data-bs-toggle="dropdown"]').forEach(function (el) {
        new bootstrap.Dropdown(el, { popperConfig: { strategy: 'fixed' } });
    });
});
</script>
@endsection
