@extends('layouts.main-board')
@section('title', 'Détails article')
@section('content')

<div class="d-flex align-items-center justify-content-between py-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">{{ $item->libelle }}</h1>
        <p class="text-muted mb-0">Détails & historique d'assignation</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('patrimoine-divers.edit', $item->id) }}" class="btn btn-outline-primary">
            <i class="bi bi-pencil me-1"></i> Modifier
        </a>
        @if($item->nombre > 0)
        <a href="{{ route('patrimoine-divers.page-assigner', $item->id) }}" class="btn btn-success">
            <i class="bi bi-person-check me-1"></i> Assigner
        </a>
        @endif
        <a href="{{ route('patrimoine-divers.index') }}" class="btn btn-outline-secondary">Retour</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted mb-3">Informations</h6>
                <dl class="row mb-0">
                    <dt class="col-6">Catégorie</dt>
                    <dd class="col-6">{{ ucfirst($item->categorie ?? '—') }}</dd>
                    <dt class="col-6">Couleur</dt>
                    <dd class="col-6">{{ $item->couleur ?? '—' }}</dd>
                    <dt class="col-6">Quantité</dt>
                    <dd class="col-6">
                        <span class="badge {{ $item->nombre <= 0 ? 'bg-danger' : 'bg-success' }}">{{ $item->nombre }}</span>
                    </dd>
                    <dt class="col-6">État</dt>
                    <dd class="col-6">{{ ucfirst($item->etat) }}</dd>
                    <dt class="col-6">Statut</dt>
                    <dd class="col-6">
                        <span class="badge {{ $item->statut === 'épuisé' ? 'bg-danger' : ($item->statut === 'partiellement assigné' ? 'bg-warning text-dark' : 'bg-success') }}">
                            {{ ucfirst($item->statut) }}
                        </span>
                    </dd>
                    <dt class="col-6">Acquisition</dt>
                    <dd class="col-6">{{ $item->date_acquisition ?? '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>

{{-- Assignations actives --}}
@php $actives = $item->assignments->whereNull('returned_at'); @endphp
@if($actives->count() > 0)
<h5 class="fw-semibold mb-3">Assignations en cours</h5>
<div class="table-responsive shadow-sm rounded mb-4">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-dark">
            <tr>
                <th>Utilisateur</th>
                <th>Quantité</th>
                <th>Assigné le</th>
                <th>Assigné par</th>
                <th class="text-center">Retour</th>
            </tr>
        </thead>
        <tbody>
            @foreach($actives as $a)
            <tr>
                <td>{{ $a->user->nom ?? '—' }} {{ $a->user->prenom ?? '' }}</td>
                <td>{{ $a->quantite }}</td>
                <td>{{ \Carbon\Carbon::parse($a->assigned_at)->format('d/m/Y') }}</td>
                <td>{{ $a->assignedBy->nom ?? '—' }}</td>
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal"
                            data-bs-target="#retourModal{{ $a->id }}">
                        <i class="bi bi-arrow-return-left"></i> Retourner
                    </button>

                    {{-- Modal retour --}}
                    <div class="modal fade" id="retourModal{{ $a->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Retour de {{ $item->libelle }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('patrimoine-divers.retourner', $a->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Commentaire (optionnel)</label>
                                            <textarea name="commentaire_retour" class="form-control" rows="3"></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-warning">Confirmer le retour</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

{{-- Historique --}}
@php $historique = $item->assignments->whereNotNull('returned_at'); @endphp
@if($historique->count() > 0)
<h5 class="fw-semibold mb-3">Historique des retours</h5>
<div class="table-responsive shadow-sm rounded">
    <table class="table table-striped align-middle mb-0">
        <thead class="table-secondary">
            <tr>
                <th>Utilisateur</th>
                <th>Quantité</th>
                <th>Assigné le</th>
                <th>Retourné le</th>
                <th>Commentaire</th>
            </tr>
        </thead>
        <tbody>
            @foreach($historique as $a)
            <tr>
                <td>{{ $a->user->nom ?? '—' }} {{ $a->user->prenom ?? '' }}</td>
                <td>{{ $a->quantite }}</td>
                <td>{{ \Carbon\Carbon::parse($a->assigned_at)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($a->returned_at)->format('d/m/Y') }}</td>
                <td>{{ $a->commentaire_retour ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@endsection
