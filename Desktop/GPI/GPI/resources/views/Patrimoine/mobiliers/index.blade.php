@extends('layouts.main-board')
@section('title', 'Mobilier & Matériel de bureau')
@section('content')

<style>
    .select2-container--default .select2-selection--single {
        background:#f9fafb!important;border:1px solid #dee2e6!important;border-radius:5px!important;
        height:40px!important;padding:5px 12px!important;display:flex;align-items:center;font-size:15px;
    }
    .select2-selection__rendered{line-height:28px!important;color:#374151!important;}
    .select2-selection__arrow{height:100%!important;right:10px!important;}
    .select2-container{width:100%!important;}
    .select2-results__option--highlighted{background:#198754!important;color:#fff!important;}
    .stat-card { border-left: 4px solid transparent; }
</style>

{{-- En-tête --}}
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between py-4 gap-3">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-lamp text-success"></i> Mobilier & Matériel de bureau
        </h1>
        <p class="text-muted mb-0">Gestion complète du mobilier et du matériel de bureau de la direction</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('mobiliers.create') }}" class="btn btn-success d-flex align-items-center gap-2">
            <i class="bi bi-plus-circle fs-5"></i> Ajouter
        </a>
        <a href="{{ route('mobiliers.inventaire') }}" class="btn btn-outline-primary d-flex align-items-center gap-2">
            <i class="bi bi-journal-text fs-5"></i> Inventaire
        </a>
        <a href="{{ route('mobiliers.historique') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
            <i class="bi bi-clock-history fs-5"></i> Historique
        </a>
        <div class="dropdown">
            <button class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bi bi-download me-1"></i> Exporter
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('mobiliers.export-excel') }}"><i class="bi bi-file-earmark-excel text-success me-2"></i>Excel</a></li>
                <li><a class="dropdown-item" href="{{ route('mobiliers.export-pdf') }}"><i class="bi bi-file-earmark-pdf text-danger me-2"></i>PDF</a></li>
            </ul>
        </div>
    </div>
</div>

@include('layouts.message')
{{-- Styles personnalisés à ajouter dans ton CSS --}}
<style>
    .stat-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;

    }
    .stat-card:hover {
        transform: translateY(-5px);

    }
</style>

{{-- Statistiques --}}
<div class="row g-3 mb-4">
    @php
    $cards = [
        ['label'=>'En stock',       'val'=>$stats['en_stock'],   'color'=>'#000000ff', 'icon'=>'bi-box-seam'],
        ['label'=>'Affectés',       'val'=>$stats['affectes'],   'color'=>'#0e8a1c', 'icon'=>'bi-person-badge'],
        ['label'=>'En réforme',     'val'=>$stats['en_reforme'], 'color'=>'#fa0909',  'icon'=>'bi-trash3'],
        ['label'=>'Fin de vie ≤30j','val'=>$stats['fin_vie'],    'color'=>'#ffda07ff', 'icon'=>'bi-clock-history'],
    ];
    @endphp

    @foreach($cards as $c)
     <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 bg-light shadow-sm p-4 position-relative stat-card text-decoration-none text-dark d-block">
            <div class="position-absolute top-0 start-0 end-0" style="height:4px;background:linear-gradient(90deg,{{ $c['color'] }},{{ $c['color'] }});border-radius:4px 4px 0 0;"></div>
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="fw-semibold text-muted mb-1 small"> {{ $c['label'] }}</p>
                    <span class="fs-2 fw-bold text-dark">{{ number_format($c['val'], 0, ',', ' ') }}</span>
                </div>
                <div class="d-flex align-items-center justify-content-center rounded-3" style="background:linear-gradient(90deg,{{ $c['color'] }},{{ $c['color'] }});width:52px;height:52px;">
                    <i class="bi {{ $c['icon'] }} fs-4 text-white"></i>
                </div>
            </div>
            <div class="position-absolute bottom-0 end-0 opacity-25" style="width:50px; height:50px;">
                <i class="bi {{ $c['icon'] }}" style="font-size:50px;"></i>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Filtres --}}
<form method="GET" action="{{ route('mobiliers.index') }}" class="card mb-4">
    <div class="card-body py-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Recherche</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control"
                           placeholder="Désignation, marque, réf…" value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">État</label>
                <select name="etat" class="form-select">
                    <option value="">Tous</option>
                    @foreach(['neuf'=>'Neuf','bon'=>'Bon','moyen'=>'Moyen','mauvais'=>'Mauvais','hors service'=>'Hors service'] as $v=>$l)
                        <option value="{{ $v }}" {{ request('etat') == $v ? 'selected' : '' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Statut</label>
                <select name="statut" class="form-select">
                    <option value="">Tous</option>
                    @foreach(['en stock'=>'En stock','affecté'=>'Affecté','en réforme'=>'En réforme','enlevé'=>'Enlevé'] as $v=>$l)
                        <option value="{{ $v }}" {{ request('statut') == $v ? 'selected' : '' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel me-1"></i>Filtrer</button>
                <a href="{{ route('mobiliers.index') }}" class="btn btn-outline-secondary w-100">Réinitialiser</a>
            </div>
        </div>
    </div>
</form>

{{-- Tableau --}}
@if($mobiliers->count() > 0)
<div class="table-responsive shadow-sm rounded">
    <table class="table table-striped table-hover align-middle mb-0">
        <thead class="table-dark">
            <tr>
                <th>N° Inv.</th>
                <th>Désignation</th>
                <th>Marque / Réf.</th>
                <th>État</th>
                <th>Statut</th>
                <th>Affecté à</th>
                <th>Fin de vie</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mobiliers as $m)
            @php
                $statutColor = match($m->statut) {
                    'affecté'    => 'bg-primary',
                    'en réforme' => 'bg-secondary',
                    default      => 'bg-success',
                };
                $finVieAlert = $m->date_fin_vie && $m->date_fin_vie->lte(now()->addDays(30));
            @endphp
            <tr class="{{ $finVieAlert ? 'table-danger' : '' }}">
                <td class="small text-muted">{{ $m->num_inventaire ?? '—' }}</td>
                <td>
                    <a href="{{ route('mobiliers.show', $m->id) }}" class="fw-semibold text-decoration-none">
                        {{ $m->designation }}
                    </a>
                    @if($finVieAlert)
                        <i class="bi bi-exclamation-triangle-fill text-warning ms-1" title="Fin de vie proche"></i>
                    @endif
                </td>
                <td class="small">{{ $m->marque ?? '—' }}{{ $m->reference ? ' / '.$m->reference : '' }}</td>
                <td>{{ ucfirst($m->etat) }}</td>
                <td><span class="badge {{ $statutColor }}">{{ ucfirst($m->statut) }}</span></td>
                <td>
                    @if($m->affectationActive?->user)
                        <span class="d-flex align-items-center gap-1">
                            <i class="bi bi-person-fill text-primary small"></i>
                            {{ $m->affectationActive->user->nom }} {{ $m->affectationActive->user->prenom }}
                        </span>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td class="small">
                    @if($m->date_fin_vie)
                        <span class="{{ $m->date_fin_vie->isPast() ? 'text-danger fw-semibold' : ($finVieAlert ? 'text-dark fw-semibold' : 'text-muted') }}">
                            {{ $m->date_fin_vie->format('d/m/Y') }}
                        </span>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td class="text-center">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('mobiliers.show', $m->id) }}">
                                    <i class="bi bi-eye me-2 text-info"></i> Détails
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('mobiliers.edit', $m->id) }}">
                                    <i class="bi bi-pencil me-2 text-primary"></i> Modifier
                                </a>
                            </li>
                            @if($m->statut === 'en stock')
                            <li>
                                <button class="dropdown-item"
                                        data-bs-toggle="modal" data-bs-target="#affecterModal"
                                        data-id="{{ $m->id }}" data-designation="{{ $m->designation }}">
                                    <i class="bi bi-person-check me-2 text-success"></i> Affecter
                                </button>
                            </li>
                            @endif
                            @if($m->statut !== 'en réforme')
                            <li>
                                <button class="dropdown-item"
                                        data-bs-toggle="modal" data-bs-target="#sortieModal"
                                        data-id="{{ $m->id }}" data-designation="{{ $m->designation }}">
                                    <i class="bi bi-box-arrow-right me-2 text-danger"></i> Sortie / Réforme
                                </button>
                            </li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('mobiliers.destroy', $m->id) }}" method="POST"
                                      onsubmit="return confirm('Supprimer ce mobilier ?')">
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
</div>
@else
<div class="text-center py-5">
    <i class="bi bi-journal-album fs-1 text-muted"></i>
    <p class="text-muted mt-2">Aucun mobilier trouvé</p>
    <a href="{{ route('mobiliers.create') }}" class="btn btn-success mt-2">
        <i class="bi bi-plus-circle me-1"></i> Ajouter un mobilier
    </a>
</div>
@endif

{{-- ===== MODAL AFFECTATION ===== --}}
<div class="modal fade" id="affecterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-check me-2"></i>Affecter un mobilier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="affecterForm" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <p class="text-muted mb-3">Mobilier : <strong id="affecterDesignation"></strong></p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Utilisateur <span class="text-danger">*</span></label>
                        <select id="affecterUserId" name="user_id" class="form-select" required>
                            <option value=""></option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->nom }} {{ $u->prenom }} — {{ $u->matricule }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Motif d'affectation</label>
                        <input type="text" name="motif_affectation" class="form-control"
                               placeholder="Ex: Nouveau bureau, remplacement…">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i> Confirmer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== MODAL SORTIE ===== --}}
<div class="modal fade" id="sortieModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-box-arrow-right me-2 text-danger"></i>Sortie / Réforme</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="sortieForm" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <p class="text-muted mb-3">Mobilier : <strong id="sortieDesignation"></strong></p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Type de sortie <span class="text-danger">*</span></label>
                        <select name="type_sortie" class="form-select" required>
                            <option value="reforme">Réforme</option>
                            <option value="enlevement">Enlèvement définitif</option>
                            <option value="transfert"> Transfert</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Motif <span class="text-danger">*</span></label>
                        <textarea name="motif" class="form-control" rows="2" required
                                  placeholder="Décrivez la raison de la sortie…"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Date de sortie <span class="text-danger">*</span></label>
                        <input type="date" name="date_sortie" class="form-control"
                               value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Observations</label>
                        <textarea name="observations" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-check-circle me-1"></i> Enregistrer la sortie
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
        const btn = e.relatedTarget;
        $('#affecterDesignation').text(btn.getAttribute('data-designation'));
        $('#affecterForm').attr('action', '/mobiliers/' + btn.getAttribute('data-id') + '/affecter');
        $('#affecterUserId').val(null).trigger('change');
    });

    $('#sortieModal').on('show.bs.modal', function (e) {
        const btn = e.relatedTarget;
        $('#sortieDesignation').text(btn.getAttribute('data-designation'));
        $('#sortieForm').attr('action', '/mobiliers/' + btn.getAttribute('data-id') + '/sortie');
    });

    // .table-responsive a overflow-x:auto, ce qui force le navigateur à clipper aussi l'axe Y
    // (règle CSS : un seul axe "visible" avec l'autre "auto" fait passer les deux en "auto").
    // Le menu du bouton "..." se retrouvait donc coincé dans le conteneur (scroll au lieu du
    // survol). En stratégie "fixed", Popper positionne le menu par rapport à la fenêtre.
    document.querySelectorAll('.table-responsive [data-bs-toggle="dropdown"]').forEach(function (el) {
        new bootstrap.Dropdown(el, { popperConfig: { strategy: 'fixed' } });
    });
});
</script>

@endsection
