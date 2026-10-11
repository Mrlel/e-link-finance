@extends('layouts.main-board')
@section('title', 'Patrimoines enlevés')
@section('content')

<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between py-4 gap-3">
    <div>
        <h1 class="h3 fw-bold mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-archive-fill text-secondary"></i> Patrimoines enlevés
        </h1>
        <p class="text-muted mb-0">
            Inventaire des biens définitivement retirés du parc —
            <strong>{{ $total }}</strong> élément(s) au total
        </p>
    </div>
</div>

@include('layouts.message')

{{-- Filtres période --}}
<form method="GET" action="{{ route('patrimoine-enleves.index') }}" class="card shadow-sm mb-4">
    <div class="card-body py-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small text-muted mb-1">Date de sortie — du</label>
                <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label small text-muted mb-1">au</label>
                <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                <a href="{{ route('patrimoine-enleves.index') }}" class="btn btn-outline-secondary w-100">Réinitialiser</a>
            </div>
        </div>
    </div>
</form>

{{-- Onglets --}}
<ul class="nav nav-tabs mb-4" id="enlevesTab" role="tablist">
    <li class="nav-item">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-equipements">
            <i class="bi bi-pc-display me-1"></i> Équipements
            <span class="badge bg-secondary ms-1">{{ $equipements->count() }}</span>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-vehicules">
            <i class="bi bi-car-front me-1"></i> Véhicules
            <span class="badge bg-secondary ms-1">{{ $vehicules->count() }}</span>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-mobiliers">
            <i class="bi bi-lamp me-1"></i> Mobilier
            <span class="badge bg-secondary ms-1">{{ $mobiliers->count() }}</span>
        </button>
    </li>
</ul>

<div class="tab-content">

    {{-- ===== ÉQUIPEMENTS ===== --}}
    <div class="tab-pane fade show active" id="tab-equipements">
        @if($equipements->count() > 0)
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Désignation</th>
                        <th>Catégorie</th>
                        <th>N° Série</th>
                        <th>Date d'enlèvement</th>
                        <th>Motif</th>
                        <th>Demandeur</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($equipements as $e)
                    @php $sortie = $e->sorties->first(); @endphp
                    <tr>
                        <td class="fw-semibold">{{ $e->des_equipement }}</td>
                        <td>{{ $e->categorie }}</td>
                        <td><code>{{ $e->numero_serie }}</code></td>
                        <td>{{ $sortie?->date_sortie?->format('d/m/Y') ?? '—' }}</td>
                        <td class="text-truncate" style="max-width:180px;">{{ $sortie?->motif ?? '—' }}</td>
                        <td>{{ $sortie?->demandeur?->nom ?? '—' }}</td>
                        <td>
                            <a href="{{ route('equipement.details', $e->id) }}"
                               class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-pc-display fs-1 mb-2 d-block"></i>
            Aucun équipement enlevé
        </div>
        @endif
    </div>

    {{-- ===== VÉHICULES ===== --}}
    <div class="tab-pane fade" id="tab-vehicules">
        @if($vehicules->count() > 0)
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Immatriculation</th>
                        <th>Marque / Modèle</th>
                        <th>Catégorie</th>
                        <th>Date d'enlèvement</th>
                        <th>Motif</th>
                        <th>Demandeur</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vehicules as $v)
                    @php $sortie = $v->sorties->first(); @endphp
                    <tr>
                        <td class="fw-semibold">{{ $v->immatriculation }}</td>
                        <td>{{ $v->marque }} {{ $v->modele }}</td>
                        <td>{{ ucfirst($v->categorie) }}</td>
                        <td>{{ $sortie?->date_sortie?->format('d/m/Y') ?? '—' }}</td>
                        <td class="text-truncate" style="max-width:180px;">{{ $sortie?->motif ?? '—' }}</td>
                        <td>{{ $sortie?->demandeur?->nom ?? '—' }}</td>
                        <td>
                            <a href="{{ route('vehicules.show', $v->id) }}"
                               class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-car-front fs-1 mb-2 d-block"></i>
            Aucun véhicule enlevé
        </div>
        @endif
    </div>

    {{-- ===== MOBILIER ===== --}}
    <div class="tab-pane fade" id="tab-mobiliers">
        @if($mobiliers->count() > 0)
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Désignation</th>
                        <th>N° Inventaire</th>
                        <th>Marque</th>
                        <th>Date d'enlèvement</th>
                        <th>Motif</th>
                        <th>Demandeur</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mobiliers as $m)
                    @php $sortie = $m->sorties->first(); @endphp
                    <tr>
                        <td class="fw-semibold">{{ $m->designation }}</td>
                        <td>{{ $m->num_inventaire ?? '—' }}</td>
                        <td>{{ $m->marque ?? '—' }}</td>
                        <td>{{ $sortie?->date_sortie?->format('d/m/Y') ?? '—' }}</td>
                        <td class="text-truncate" style="max-width:180px;">{{ $sortie?->motif ?? '—' }}</td>
                        <td>{{ $sortie?->demandeur?->nom ?? '—' }}</td>
                        <td>
                            <a href="{{ route('mobiliers.show', $m->id) }}"
                               class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-lamp fs-1 mb-2 d-block"></i>
            Aucun mobilier enlevé
        </div>
        @endif
    </div>

</div>

@endsection
