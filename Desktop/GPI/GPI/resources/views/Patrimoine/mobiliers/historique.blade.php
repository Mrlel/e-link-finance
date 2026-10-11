@extends('layouts.main-board')
@section('title', 'Historique affectations mobilier')
@section('content')

<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between py-4 gap-3">
    <div>
        <h1 class="h3 fw-bold mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-clock-history text-success"></i> Historique des affectations
        </h1>
        <p class="text-muted mb-0">Tous les mouvements de mobilier de la direction</p>
    </div>
    <a href="{{ route('mobiliers.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

@include('layouts.message')

<form method="GET" action="{{ route('mobiliers.historique') }}" class="card shadow-sm mb-4">
    <div class="card-body py-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label small text-muted mb-1">Recherche</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control"
                           placeholder="Désignation ou nom utilisateur…" value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                <a href="{{ route('mobiliers.historique') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </div>
    </div>
</form>

@if($assignments->count() > 0)
<div class="table-responsive shadow-sm rounded">
    <table class="table table-striped table-hover align-middle mb-0">
        <thead class="table-dark">
            <tr>
                <th>Mobilier</th>
                <th>Utilisateur</th>
                <th>Affecté le</th>
                <th>Affecté par</th>
                <th>Retiré le</th>
                <th>Retiré par</th>
                <th>Statut</th>
                <th>Commentaire</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assignments as $a)
            <tr>
                <td>
                    @if($a->mobilier)
                    <a href="{{ route('mobiliers.show', $a->mobilier->id) }}" class="fw-semibold text-decoration-none">
                        {{ $a->mobilier->designation }}
                    </a>
                    <div class="small text-muted">{{ \App\Models\Mobilier::$categories[$a->mobilier->categorie] ?? '' }}</div>
                    @else <span class="text-muted">—</span> @endif
                </td>
                <td>{{ $a->user->nom ?? '—' }} {{ $a->user->prenom ?? '' }}</td>
                <td>{{ \Carbon\Carbon::parse($a->assigned_at)->format('d/m/Y') }}</td>
                <td>{{ $a->assignedBy->nom ?? '—' }}</td>
                <td>
                    @if($a->returned_at)
                        {{ \Carbon\Carbon::parse($a->returned_at)->format('d/m/Y') }}
                    @else <span class="text-muted">En cours</span> @endif
                </td>
                <td>{{ $a->returnedBy->nom ?? '—' }}</td>
                <td>
                    @if($a->returned_at)
                        <span class="badge bg-secondary">Terminé</span>
                    @else
                        <span class="badge bg-primary">Actif</span>
                    @endif
                </td>
                <td>{{ $a->commentaire_retour ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="text-center py-5">
    <i class="bi bi-clock-history fs-1 text-muted"></i>
    <p class="text-muted mt-2">Aucun mouvement enregistré</p>
</div>
@endif

@endsection
