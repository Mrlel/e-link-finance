@extends('layouts.main-board')
@section('title', "Journal d'activités")
@section('content')
@php
    $actionMeta = [
        'création'      => ['icon' => 'bi-plus-circle-fill',        'color' => '#20c997', 'label' => 'Création'],
        'ajout'         => ['icon' => 'bi-plus-circle-fill',        'color' => '#20c997', 'label' => 'Ajout'],
        'ajouter'       => ['icon' => 'bi-plus-circle-fill',        'color' => '#20c997', 'label' => 'Ajout'],
        'modification'  => ['icon' => 'bi-pencil-fill',             'color' => '#f59e0b', 'label' => 'Modification'],
        'suppression'   => ['icon' => 'bi-trash-fill',              'color' => '#dc3545', 'label' => 'Suppression'],
        'supprimer'     => ['icon' => 'bi-trash-fill',              'color' => '#dc3545', 'label' => 'Suppression'],
        'attribution'   => ['icon' => 'bi-person-check-fill',       'color' => '#2f3a6d', 'label' => 'Attribution'],
        'retour'        => ['icon' => 'bi-arrow-return-left',       'color' => '#6f42c1', 'label' => 'Retour'],
        'retirer'       => ['icon' => 'bi-arrow-return-left',       'color' => '#6f42c1', 'label' => 'Retrait'],
        'import'        => ['icon' => 'bi-file-earmark-arrow-up-fill', 'color' => '#0dcaf0', 'label' => 'Import'],
        'transfert'     => ['icon' => 'bi-arrow-left-right',        'color' => '#0aa6a0', 'label' => 'Transfert'],
        'approuver'     => ['icon' => 'bi-check-circle-fill',       'color' => '#198754', 'label' => 'Approbation'],
        'rejet'         => ['icon' => 'bi-x-circle-fill',           'color' => '#b02a37', 'label' => 'Rejet'],
        'reset-password'=> ['icon' => 'bi-key-fill',                'color' => '#fd7e14', 'label' => 'Mot de passe'],
    ];
    $defaultMeta = ['icon' => 'bi-activity', 'color' => '#6c757d', 'label' => 'Autre'];
    $metaFor = fn($action) => $actionMeta[$action] ?? $defaultMeta;

    $counts = $modifications->countBy('action');
    $quickFilters = [
        ''             => ['label' => 'Tout',        'icon' => 'bi-grid-fill',       'color' => '#2C3E50'],
        'création'     => ['label' => 'Créations',   'icon' => 'bi-plus-circle-fill','color' => '#20c997'],
        'modification' => ['label' => 'Modifs',      'icon' => 'bi-pencil-fill',     'color' => '#f59e0b'],
        'suppression'  => ['label' => 'Suppressions','icon' => 'bi-trash-fill',      'color' => '#dc3545'],
        'attribution'  => ['label' => 'Attributions','icon' => 'bi-person-check-fill','color' => '#2f3a6d'],
    ];
@endphp

<style>
    .quick-filters { display: flex; gap: .5rem; flex-wrap: wrap; }
    .quick-filter-chip {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .4rem .9rem;
        border-radius: 999px;
        font-size: .82rem; font-weight: 600;
        text-decoration: none;
        border: 1.5px solid #e9ecef;
        color: #495057;
        background: #fff;
        transition: all .2s ease;
    }
    .quick-filter-chip:hover { transform: translateY(-1px); box-shadow: 0 4px 10px rgba(0,0,0,.08); color: #212529; }
    .quick-filter-chip.active { color: #fff; border-color: transparent; }

    .timeline-day { margin-bottom: 2.2rem; }
    .timeline-day-label {
        display: flex; align-items: center; gap: .7rem;
        font-weight: 700; font-size: .95rem; color: #2C3E50;
        margin-bottom: 1.1rem;
    }
    .timeline-day-label .badge-count {
        background: #eef0f5; color: #6c757d; font-weight: 600;
        font-size: .72rem; padding: .2rem .55rem; border-radius: 999px;
    }
    .timeline-day-label .line { flex: 1; height: 1px; background: #e9ecef; }

    .timeline-list { position: relative; padding-left: 2.9rem; }
    .timeline-list::before {
        content: '';
        position: absolute; left: 1.15rem; top: .4rem; bottom: .4rem;
        width: 2px; background: #e9ecef;
    }
    .timeline-item {
        position: relative;
        background: #fff;
        border-radius: 10px;
        padding: .9rem 1.1rem;
        margin-bottom: .55rem;
        border: 1px solid #e9ecef;
        transition: all .22s ease;
    }
    .timeline-item:hover {
        transform: translateX(5px);
        box-shadow: 0 8px 20px rgba(0,0,0,.08);
    }
    .timeline-icon {
        position: absolute; left: -2.9rem; top: .85rem;
        width: 2.3rem; height: 2.3rem; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: .95rem;
        box-shadow: 0 0 0 4px #f8f9fb;
        z-index: 1;
    }
    .timeline-time { font-size: .76rem; color: #adb5bd; font-weight: 600; white-space: nowrap; }
    .timeline-desc { color: #343a40; font-size: .92rem; margin: .3rem 0 .5rem; }
    .timeline-chip {
        display: inline-flex; align-items: center; gap: .3rem;
        font-size: .76rem; font-weight: 600;
        padding: .2rem .6rem; border-radius: 999px;
        background: #f1f3f8; color: #495057;
    }
    .timeline-avatar {
        width: 22px; height: 22px; border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        background: #2f3a6d; color: #fff; font-size: .68rem; font-weight: 700;
    }
</style>

<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between py-4 gap-3">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-clock-history text-success"></i> Journal d'activités
        </h1>
        <p class="text-muted mb-0">Historique des actions effectuées sur le patrimoine de votre direction</p>
    </div>
</div>

@include('layouts.message')

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 bg-light shadow-sm p-3 h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center rounded flex-shrink-0"
                     style="width:48px; height:48px; background:linear-gradient(135deg,#2f3a6d,#4a5a9e);">
                    <i class="bi bi-activity fs-5 text-white"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold text-dark lh-1">{{ $modifications->count() }}</div>
                    <div class="small text-muted">Actions</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 bg-light shadow-sm p-3 h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center rounded flex-shrink-0"
                     style="width:48px; height:48px; background:linear-gradient(135deg,#28a745,#20c997);">
                    <i class="bi bi-plus-circle-fill fs-5 text-white"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold text-dark lh-1">{{ $counts->get('création', 0) + $counts->get('ajout', 0) + $counts->get('ajouter', 0) }}</div>
                    <div class="small text-muted">Créations</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 bg-light shadow-sm p-3 h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center rounded flex-shrink-0"
                     style="width:48px; height:48px; background:linear-gradient(135deg,#dc3545,#e4606d);">
                    <i class="bi bi-trash-fill fs-5 text-white"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold text-dark lh-1">{{ $counts->get('suppression', 0) + $counts->get('supprimer', 0) }}</div>
                    <div class="small text-muted">Suppressions</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 bg-light shadow-sm p-3 h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center rounded flex-shrink-0"
                     style="width:48px; height:48px; background:linear-gradient(135deg,#ff7f50,#ffb347);">
                    <i class="bi bi-calendar3 fs-5 text-white"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold text-dark lh-1">{{ $modificationsParJour->count() }}</div>
                    <div class="small text-muted">Jours actifs</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filtres --}}
<form method="GET" action="{{ route('journal.index') }}" class="card shadow-sm mb-4">
    <div class="card-body py-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small text-muted mb-1">Recherche</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                           placeholder="Rechercher dans les descriptions…">
                </div>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small text-muted mb-1">Du</label>
                <input type="date" name="date_debut" value="{{ request('date_debut') }}" class="form-control">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small text-muted mb-1">Au</label>
                <input type="date" name="date_fin" value="{{ request('date_fin') }}" class="form-control">
            </div>
            <input type="hidden" name="action" value="{{ request('action') }}">
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel"></i> Filtrer</button>
                <a href="{{ route('journal.index') }}" class="btn btn-outline-secondary w-100">Réinitialiser</a>
            </div>
        </div>

        <div class="quick-filters mt-3 pt-3 border-top">
            @foreach($quickFilters as $value => $meta)
                @php
                    $isActive = request('action', '') === $value;
                    $params = array_filter(array_merge(request()->except('action'), ['action' => $value]));
                @endphp
                <a href="{{ route('journal.index', $params) }}"
                   class="quick-filter-chip {{ $isActive ? 'active' : '' }}"
                   style="{{ $isActive ? 'background:' . $meta['color'] . ';' : '' }}">
                    <i class="bi {{ $meta['icon'] }}"></i> {{ $meta['label'] }}
                </a>
            @endforeach
        </div>
    </div>
</form>

@forelse($modificationsParJour as $jour => $entries)
    @php
        $jourCarbon = $entries->first()->created_at;
        $jourLabel = $jourCarbon->isToday() ? "Aujourd'hui" : ($jourCarbon->isYesterday() ? 'Hier' : $jourCarbon->translatedFormat('l d F Y'));
    @endphp
    <div class="timeline-day">
        <div class="timeline-day-label">
            <span>{{ ucfirst($jourLabel) }}</span>
            <span class="badge-count">{{ $entries->count() }} action{{ $entries->count() > 1 ? 's' : '' }}</span>
            <span class="line"></span>
        </div>

        <div class="timeline-list">
            @foreach($entries as $modif)
                @php $meta = $metaFor($modif->action); @endphp
                <div class="timeline-item">
                    <div class="timeline-icon" style="background: {{ $meta['color'] }};">
                        <i class="bi {{ $meta['icon'] }}"></i>
                    </div>
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div class="flex-grow-1">
                            <span class="timeline-chip" style="background: {{ $meta['color'] }}1a; color: {{ $meta['color'] }};">
                                {{ $meta['label'] }}
                            </span>
                            @if($modif->equipement)
                                <span class="timeline-chip ms-1">
                                    <i class="bi bi-pc-display"></i> {{ $modif->equipement->des_equipement }}
                                </span>
                            @endif
                            @if($modif->vehicule)
                                <span class="timeline-chip ms-1">
                                    <i class="bi bi-car-front"></i> {{ $modif->vehicule->immatriculation }}
                                </span>
                            @endif
                            @if($modif->mobilier)
                                <span class="timeline-chip ms-1">
                                    <i class="bi bi-lamp"></i> {{ $modif->mobilier->designation }}
                                </span>
                            @endif
                            @if($modif->patrimoineDivers)
                                <span class="timeline-chip ms-1">
                                    <i class="bi bi-journal-album"></i> {{ $modif->patrimoineDivers->libelle }}
                                </span>
                            @endif
                            @if($modif->licence)
                                <span class="timeline-chip ms-1">
                                    <i class="bi bi-key"></i> {{ $modif->licence->nom_licence }}
                                </span>
                            @endif
                            @if($modif->poste)
                                <span class="timeline-chip ms-1">
                                    <i class="bi bi-display"></i> {{ $modif->poste->code_poste }}
                                </span>
                            @endif
                            @if($modif->targetUser)
                                <span class="timeline-chip ms-1">
                                    <i class="bi bi-person"></i> {{ $modif->targetUser->nom }} {{ $modif->targetUser->prenom }}
                                </span>
                            @endif
                            <p class="timeline-desc">{{ $modif->description }}</p>
                            <div class="d-flex align-items-center gap-2">
                                <span class="timeline-avatar">
                                    {{ strtoupper(substr($modif->user->nom ?? '?', 0, 1)) }}{{ strtoupper(substr($modif->user->prenom ?? '', 0, 1)) }}
                                </span>
                                <small class="text-muted">{{ $modif->user->nom ?? 'Système' }} {{ $modif->user->prenom ?? '' }}</small>
                            </div>
                        </div>
                        <span class="timeline-time">{{ $modif->created_at->format('H:i') }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@empty
    <div class="text-center py-5">
        <i class="bi bi-inbox fs-1 text-muted"></i>
        <p class="text-muted mt-2 mb-0">Aucune activité enregistrée pour cette période.</p>
    </div>
@endforelse

@endsection
