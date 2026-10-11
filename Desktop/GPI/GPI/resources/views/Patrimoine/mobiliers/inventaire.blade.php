@extends('layouts.main-board')
@section('title', 'Inventaire mobilier')
@section('content')

<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between py-4 gap-3">
    <div>
        <h1 class="h3 fw-bold mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-lamp text-success"></i> Inventaire du mobilier
        </h1>
        <p class="text-muted mb-0">{{ $mobiliers->count() }} article(s) — disponible</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('mobiliers.export-excel') }}" class="btn btn-outline-success">
            <i class="bi bi-file-earmark-excel me-1"></i> Excel
        </a>
        <a href="{{ route('mobiliers.export-pdf') }}" class="btn btn-outline-danger">
            <i class="bi bi-file-earmark-pdf me-1"></i> PDF
        </a>
        <a href="{{ route('mobiliers.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Retour
        </a>
    </div>
</div>

<form method="GET" action="{{ route('mobiliers.inventaire') }}" class="card shadow-sm mb-4">
    <div class="card-body py-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Recherche</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control"
                           placeholder="Désignation, N° inventaire…" value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1">État</label>
                <select name="etat" class="form-select">
                    <option value="">Tous</option>
                    @foreach(['neuf'=>'Neuf','bon'=>'Bon','moyen'=>'Moyen','mauvais'=>'Mauvais','hors service'=>'Hors service'] as $v=>$l)
                        <option value="{{ $v }}" {{ request('etat') == $v ? 'selected' : '' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1">Statut</label>
                <select name="statut" class="form-select">
                    <option value="">Tous</option>
                    @foreach(['en stock'=>'En stock','affecté'=>'Affecté','en réforme'=>'En réforme'] as $v=>$l)
                        <option value="{{ $v }}" {{ request('statut') == $v ? 'selected' : '' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                <a href="{{ route('mobiliers.inventaire') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </div>
    </div>
</form>

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
                <th>Mode acq.</th>
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
                <td>
                    <a href="{{ route('mobiliers.show', $m->id) }}" class="text-decoration-none fw-semibold">
                        {{ $m->designation }}
                    </a>
                    @if($finVieAlert)
                        <i class="bi bi-exclamation-triangle-fill text-warning ms-1"></i>
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
                <td class="small">{{ ucfirst($m->mode_acquisition) }}</td>
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

@endsection
