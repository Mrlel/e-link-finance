@extends('layouts.main-board')
@section('title', 'Inventaire Patrimoine')
@section('content')

<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between py-4 gap-3">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-clipboard-data text-success"></i> Inventaire Patrimoine (audit)
        </h1>
        <p class="text-muted mb-0">Parc automobile, fournitures & consommables, mobilier & matériel de bureau — vue de consultation, non modifiable.</p>
    </div>
</div>

@include('layouts.message')

<ul class="nav nav-tabs mb-4" id="inventaireTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ $activeTab === 'vehicules' ? 'active' : '' }}" id="tab-vehicules-btn"
                data-bs-toggle="tab" data-bs-target="#tab-vehicules" type="button" role="tab">
            <i class="bi bi-car-front-fill me-1"></i> Parc automobile
            <span class="badge bg-secondary ms-1">{{ $vehicules->count() }}</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ $activeTab === 'divers' ? 'active' : '' }}" id="tab-divers-btn"
                data-bs-toggle="tab" data-bs-target="#tab-divers" type="button" role="tab">
            <i class="bi bi-journal-album me-1"></i> Fournitures & consommables
            <span class="badge bg-secondary ms-1">{{ $diversItems->count() }}</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ $activeTab === 'mobilier' ? 'active' : '' }}" id="tab-mobilier-btn"
                data-bs-toggle="tab" data-bs-target="#tab-mobilier" type="button" role="tab">
            <i class="bi bi-lamp me-1"></i> Mobilier & matériel de bureau
            <span class="badge bg-secondary ms-1">{{ $mobiliers->count() }}</span>
        </button>
    </li>
</ul>

<div class="tab-content" id="inventaireTabsContent">

    {{-- ===================== VÉHICULES ===================== --}}
    <div class="tab-pane fade {{ $activeTab === 'vehicules' ? 'show active' : '' }}" id="tab-vehicules" role="tabpanel">

        <form method="GET" action="{{ route('inventaire-patrimoine.index') }}" class="card shadow-sm mb-3">
            <input type="hidden" name="tab" value="vehicules">
            <div class="card-body py-3">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small text-muted mb-1">Recherche</label>
                        <input type="text" name="v_search" class="form-control" placeholder="Immatriculation, marque…" value="{{ request('v_search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted mb-1">Catégorie</label>
                        <select name="v_categorie" class="form-select">
                            <option value="">Toutes</option>
                            <option value="auto" {{ request('v_categorie') == 'auto' ? 'selected' : '' }}>Auto</option>
                            <option value="moto" {{ request('v_categorie') == 'moto' ? 'selected' : '' }}>Moto</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted mb-1">État</label>
                        <select name="v_etat" class="form-select">
                            <option value="">Tous</option>
                            @foreach(['NEUF','BON','MOYEN','MAUVAIS','HORS SERVICE'] as $e)
                                <option value="{{ $e }}" {{ request('v_etat') == $e ? 'selected' : '' }}>{{ $e }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted mb-1">Statut</label>
                        <select name="v_statut" class="form-select">
                            <option value="">Tous</option>
                            @foreach(['disponible','affecté','en maintenance','hors service','enlevé'] as $s)
                                <option value="{{ $s }}" {{ request('v_statut') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel"></i> Filtrer</button>
                        <a href="{{ route('inventaire-patrimoine.index', ['tab' => 'vehicules']) }}" class="btn btn-outline-secondary w-100">Reset</a>
                    </div>
                </div>
            </div>
        </form>

        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="text-muted small">{{ $vehicules->count() }} résultat(s)</span>
            <div class="d-flex gap-2">
                <a href="{{ route('vehicules.export-excel') }}" class="btn btn-outline-success btn-sm"><i class="bi bi-file-earmark-excel me-1"></i> Excel</a>
                <a href="{{ route('vehicules.export-pdf') }}" class="btn btn-outline-danger btn-sm"><i class="bi bi-file-earmark-pdf me-1"></i> PDF</a>
            </div>
        </div>

        @if($vehicules->count() > 0)
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-striped table-hover align-middle mb-0">
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
                        <th class="text-center">Détails</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vehicules as $v)
                    @php
                        $statutColor = match($v->statut) {
                            'affecté'        => 'bg-primary',
                            'en maintenance' => 'bg-warning text-dark',
                            'hors service'   => 'bg-danger',
                            'enlevé'         => 'bg-secondary',
                            default          => 'bg-success',
                        };
                    @endphp
                    <tr>
                        <td class="fw-semibold">{{ $v->immatriculation }}</td>
                        <td><i class="bi {{ $v->categorie === 'moto' ? 'bi-bicycle' : 'bi-car-front' }} me-1"></i>{{ ucfirst($v->categorie) }}</td>
                        <td>{{ $v->marque ?? '—' }} {{ $v->modele ? '/ '.$v->modele : '' }}</td>
                        <td>{{ $v->couleur ?? '—' }}</td>
                        <td>{{ $v->etat }}</td>
                        <td><span class="badge {{ $statutColor }}">{{ ucfirst($v->statut) }}</span></td>
                        <td class="small">
                            @if($v->affectationActive && $v->affectationActive->user)
                                <i class="bi bi-person-fill text-primary"></i> {{ $v->affectationActive->user->nom }} {{ $v->affectationActive->user->prenom }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="small">{{ ucfirst($v->mode_acquisition ?? '—') }}</td>
                        <td class="text-center">
                            <a href="{{ route('vehicules.show', $v->id) }}" class="btn btn-sm border-success text-success">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-car-front fs-1 text-muted"></i>
            <p class="text-muted mt-2">Aucun véhicule trouvé</p>
        </div>
        @endif
    </div>

    {{-- ===================== FOURNITURES & CONSOMMABLES ===================== --}}
    <div class="tab-pane fade {{ $activeTab === 'divers' ? 'show active' : '' }}" id="tab-divers" role="tabpanel">

        <form method="GET" action="{{ route('inventaire-patrimoine.index') }}" class="card shadow-sm mb-3">
            <input type="hidden" name="tab" value="divers">
            <div class="card-body py-3">
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small text-muted mb-1">Recherche</label>
                        <input type="text" name="d_search" class="form-control" placeholder="Nom de l'article…" value="{{ request('d_search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted mb-1">Catégorie</label>
                        <select name="d_categorie" class="form-select">
                            <option value="">Toutes</option>
                            @foreach(['fournitures' => 'Fournitures', 'consommables' => 'Consommables', 'autre' => 'Autre'] as $val => $label)
                                <option value="{{ $val }}" {{ request('d_categorie') == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted mb-1">Statut</label>
                        <select name="d_statut" class="form-select">
                            <option value="">Tous</option>
                            @foreach(['en stock' => 'En stock', 'partiellement assigné' => 'Part. assigné', 'épuisé' => 'Épuisé'] as $val => $label)
                                <option value="{{ $val }}" {{ request('d_statut') == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted mb-1">État</label>
                        <select name="d_etat" class="form-select">
                            <option value="">Tous</option>
                            @foreach(['neuf' => 'Neuf', 'bon' => 'Bon', 'use' => 'Usé'] as $val => $label)
                                <option value="{{ $val }}" {{ request('d_etat') == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel"></i> Filtrer</button>
                        <a href="{{ route('inventaire-patrimoine.index', ['tab' => 'divers']) }}" class="btn btn-outline-secondary w-100">Reset</a>
                    </div>
                </div>
            </div>
        </form>

        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="text-muted small">{{ $diversItems->count() }} résultat(s)</span>
            <div class="d-flex gap-2">
                <a href="{{ route('patrimoine-divers.export-excel') }}" class="btn btn-outline-success btn-sm"><i class="bi bi-file-earmark-excel me-1"></i> Excel</a>
                <a href="{{ route('patrimoine-divers.export-pdf') }}" class="btn btn-outline-danger btn-sm"><i class="bi bi-file-earmark-pdf me-1"></i> PDF</a>
            </div>
        </div>

        @if($diversItems->count() > 0)
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Libellé</th>
                        <th>Catégorie</th>
                        <th>Quantité</th>
                        <th>État</th>
                        <th>Statut</th>
                        <th>Date acquisition</th>
                        <th class="text-center">Détails</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($diversItems as $item)
                    <tr>
                        <td class="fw-semibold">{{ $item->libelle }}</td>
                        <td>{{ ucfirst($item->categorie ?? '—') }}</td>
                        <td>
                            <span class="badge {{ $item->nombre <= 0 ? 'bg-danger' : ($item->nombre <= 5 ? 'bg-warning text-dark' : 'bg-success') }}">
                                {{ $item->nombre }}
                            </span>
                        </td>
                        <td>{{ ucfirst($item->etat) }}</td>
                        <td>
                            <span class="badge {{ $item->statut === 'épuisé' ? 'bg-danger' : ($item->statut === 'partiellement assigné' ? 'bg-warning text-dark' : 'bg-success') }}">
                                {{ ucfirst($item->statut) }}
                            </span>
                        </td>
                        <td class="small">{{ $item->date_acquisition ?? '—' }}</td>
                        <td class="text-center">
                            <a href="{{ route('patrimoine-divers.show', $item->id) }}" class="btn btn-sm border-success text-success">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-inbox fs-1 text-muted"></i>
            <p class="text-muted mt-2">Aucun article trouvé</p>
        </div>
        @endif
    </div>

    {{-- ===================== MOBILIER ===================== --}}
    <div class="tab-pane fade {{ $activeTab === 'mobilier' ? 'show active' : '' }}" id="tab-mobilier" role="tabpanel">

        <form method="GET" action="{{ route('inventaire-patrimoine.index') }}" class="card shadow-sm mb-3">
            <input type="hidden" name="tab" value="mobilier">
            <div class="card-body py-3">
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small text-muted mb-1">Recherche</label>
                        <input type="text" name="m_search" class="form-control" placeholder="Désignation, N° inventaire…" value="{{ request('m_search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted mb-1">État</label>
                        <select name="m_etat" class="form-select">
                            <option value="">Tous</option>
                            @foreach(['neuf'=>'Neuf','bon'=>'Bon','moyen'=>'Moyen','mauvais'=>'Mauvais','hors service'=>'Hors service'] as $v=>$l)
                                <option value="{{ $v }}" {{ request('m_etat') == $v ? 'selected' : '' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted mb-1">Statut</label>
                        <select name="m_statut" class="form-select">
                            <option value="">Tous</option>
                            @foreach(['en stock'=>'En stock','affecté'=>'Affecté','en réforme'=>'En réforme'] as $v=>$l)
                                <option value="{{ $v }}" {{ request('m_statut') == $v ? 'selected' : '' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel"></i> Filtrer</button>
                        <a href="{{ route('inventaire-patrimoine.index', ['tab' => 'mobilier']) }}" class="btn btn-outline-secondary w-100">Reset</a>
                    </div>
                </div>
            </div>
        </form>

        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="text-muted small">{{ $mobiliers->count() }} résultat(s)</span>
            <div class="d-flex gap-2">
                <a href="{{ route('mobiliers.export-excel') }}" class="btn btn-outline-success btn-sm"><i class="bi bi-file-earmark-excel me-1"></i> Excel</a>
                <a href="{{ route('mobiliers.export-pdf') }}" class="btn btn-outline-danger btn-sm"><i class="bi bi-file-earmark-pdf me-1"></i> PDF</a>
            </div>
        </div>

        @if($mobiliers->count() > 0)
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>N° Inv.</th>
                        <th>Désignation</th>
                        <th>Marque / Réf.</th>
                        <th>État</th>
                        <th>Statut</th>
                        <th>Affecté à</th>
                        <th>Acquisition</th>
                        <th>Fin de vie</th>
                        <th class="text-center">Détails</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mobiliers as $m)
                    @php
                        $finVieAlert = $m->date_fin_vie && $m->date_fin_vie->lte(now()->addDays(30));
                        $etatColor = match($m->etat) {
                            'neuf' => 'bg-success', 'bon' => 'bg-primary',
                            'moyen' => 'bg-warning text-dark', 'mauvais' => 'bg-danger',
                            default => 'bg-dark',
                        };
                        $statutColor = match($m->statut) {
                            'affecté' => 'bg-primary', 'en réforme' => 'bg-secondary', default => 'bg-success',
                        };
                    @endphp
                    <tr class="{{ $finVieAlert ? 'table-warning' : '' }}">
                        <td class="small text-muted">{{ $m->num_inventaire ?? '—' }}</td>
                        <td class="fw-semibold">
                            {{ $m->designation }}
                            @if($finVieAlert)
                                <i class="bi bi-exclamation-triangle-fill text-warning ms-1" title="Fin de vie proche"></i>
                            @endif
                        </td>
                        <td class="small">{{ $m->marque ?? '—' }}{{ $m->reference ? ' / '.$m->reference : '' }}</td>
                        <td><span class="badge {{ $etatColor }}">{{ ucfirst($m->etat) }}</span></td>
                        <td><span class="badge {{ $statutColor }}">{{ ucfirst($m->statut) }}</span></td>
                        <td class="small">
                            {{ $m->affectationActive?->user ? $m->affectationActive->user->nom.' '.$m->affectationActive->user->prenom : '—' }}
                        </td>
                        <td class="small">{{ $m->date_acquisition?->format('d/m/Y') ?? '—' }}</td>
                        <td class="small {{ $finVieAlert ? 'text-danger fw-semibold' : '' }}">
                            {{ $m->date_fin_vie?->format('d/m/Y') ?? '—' }}
                        </td>
                        <td class="text-center">
                            <a href="{{ route('mobiliers.show', $m->id) }}" class="btn btn-sm border-success text-success">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-journal-text fs-1 text-muted"></i>
            <p class="text-muted mt-2">Aucun mobilier trouvé</p>
        </div>
        @endif
    </div>

</div>

@endsection
