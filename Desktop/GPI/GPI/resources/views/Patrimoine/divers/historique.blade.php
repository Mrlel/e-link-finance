@extends('layouts.main-board')
@section('title', 'Historique Patrimoine Divers')
@section('content')

<div class="d-flex align-items-center justify-content-between py-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-clock-history"></i> Historique des assignations
        </h1>
        <p class="text-muted mb-0">Fournitures & Consommables</p>
    </div>
    <a href="{{ route('patrimoine-divers.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Retour au stock
    </a>
</div>

@if($assignments->count() > 0)
<div class="table-responsive shadow-sm rounded">
    <table class="table table-striped table-hover align-middle mb-0">
        <thead class="table-dark">
            <tr>
                <th>Article</th>
                <th>Utilisateur</th>
                <th>Quantité</th>
                <th>Assigné le</th>
                <th>Assigné par</th>
                <th>Retourné le</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assignments as $a)
            <tr>
                <td>{{ $a->patrimoineDivers->libelle ?? '—' }}</td>
                <td>{{ $a->user->nom ?? '—' }} {{ $a->user->prenom ?? '' }}</td>
                <td>{{ $a->quantite }}</td>
                <td>{{ \Carbon\Carbon::parse($a->assigned_at)->format('d/m/Y') }}</td>
                <td>{{ $a->assignedBy->nom ?? '—' }}</td>
                <td>{{ $a->returned_at ? \Carbon\Carbon::parse($a->returned_at)->format('d/m/Y') : '—' }}</td>
                <td>
                    @if($a->returned_at)
                        <span class="badge bg-secondary">Retourné</span>
                    @else
                        <span class="badge bg-success">En cours</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="text-center py-5">
    <i class="bi bi-inbox fs-1 text-muted"></i>
    <p class="text-muted mt-2">Aucune assignation enregistrée</p>
</div>
@endif

@endsection
