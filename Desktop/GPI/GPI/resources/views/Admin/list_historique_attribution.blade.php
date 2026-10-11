@extends('layouts.main-board')
@section('title', 'Historique des attributions')
@section('content')

<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between py-4 gap-3">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-clock-history text-success"></i> Historique des attributions
        </h1>
        <p class="text-muted mb-0">Équipements assignés aux personnels ({{ $assignments->total() }})</p>
    </div>
    <a href="/preview-pdf" class="btn btn-outline-primary d-flex align-items-center gap-2">
        <i class="bi bi-filetype-pdf fs-5"></i> Exporter en PDF
    </a>
</div>

@include('layouts.message')

<form method="GET" action="{{ url()->current() }}" class="card shadow-sm mb-4">
    <div class="card-body py-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label small text-muted mb-1">Recherche</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input id="searchInput" name="q" type="text" class="form-control" value="{{ request('q') }}"
                           placeholder="Nom d'agent, équipement…">
                    <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
                    @if(request()->filled('q'))
                        <a href="{{ url()->current() }}" class="btn btn-outline-secondary">Réinitialiser</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</form>

@if($assignments->total() > 0)
<div class="table-responsive shadow-sm rounded">
    <table class="table table-striped table-hover align-middle mb-0">
        <thead class="table-dark">
            <tr>
                <th>Réception</th>
                <th>Agent</th>
                <th>Remetteur</th>
                <th>Équipement</th>
                <th>Date d'attribution</th>
                <th>Heure</th>
                <th>Statut</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach($assignments as $assignment)
                <tr class="searchable-row">

                    <!-- Status icon -->
                    <td>
                        @if($assignment->confirmed)
                            <i class="bi bi-check-circle-fill text-success fs-5"></i>
                        @else
                            <i class="bi bi-x-circle-fill text-warning fs-5"></i>
                        @endif
                    </td>

                    <td>{{ optional($assignment->user)->nom }}</td>
                    <td>{{ optional($assignment->assignedBy)->nom ?? 'Inconnu' }}</td>

                    <!-- Equipment -->
                    <td>
                        {{ $assignment->equipement->nom }} - {{ $assignment->equipement->marque }} <br>
                        <small class="text-muted">
                            N° Série: {{ $assignment->equipement->numero_serie }}
                        </small>
                    </td>

                    <td>{{ \Carbon\Carbon::parse($assignment->assigned_at)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($assignment->assigned_at)->format('H:i') }}</td>

                    <!-- Status badge -->
                    <td>
                        @if($assignment->returned_at )
                            <span class="badge bg-success-subtle text-success">Retourné</span>
                        @elseif (!$assignment->returned_at && $assignment->confirmed)
                            <span class="badge bg-primary-subtle text-primary">En service</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary">En attente de confirmation</span>
                        @endif
                    </td>

                    <!-- Actions -->
                    <td class="text-center">

                        @if(!$assignment->returned_at)
                        <button class="btn btn-warning btn-sm px-3"
                                data-bs-toggle="modal"
                                data-bs-target="#retraitModal{{ $assignment->id }}">
                                <i class="bi bi-arrow-counterclockwise"></i> Retirer
                        </button>
                        @else
                        <button class="btn btn-outline-success btn-sm px-3"
                                data-bs-toggle="modal"
                                data-bs-target="#causeModal{{ $assignment->id }}">
                                <i class="bi bi-info-circle"></i> Cause du retrait
                        </button>
                        @endif
                    </td>
                </tr>

                <!-- Modal: Cause du retrait -->
                <div class="modal fade" id="causeModal{{ $assignment->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content shadow-sm">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    Cause du retrait — {{ $assignment->equipement->des_equipement }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                {{ $assignment->commentaire_retour ?? 'Aucun commentaire fourni.' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal: Retrait -->
                <div class="modal fade" id="retraitModal{{ $assignment->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content shadow-sm">
                            <div class="modal-header">
                                <h5 class="modal-title">Retrait d'équipement</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <p>Confirmez-vous le retrait de :</p>

                                <ul class="mb-3">
                                    <li><strong>Équipement :</strong> {{ $assignment->equipement->des_equipement }}</li>
                                    <li><strong>Utilisateur :</strong> {{ optional($assignment->user)->nom }}</li>
                                    <li><strong>N° Série :</strong> {{ $assignment->equipement->numero_serie }}</li>
                                </ul>

                                <form action="{{ route('retourner.equipement', ['assignmentId' => $assignment->id]) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label class="form-label">Commentaire</label>
                                        <textarea name="commentaire_retour" class="form-control" rows="3"></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">État au retour</label>
                                        <select class="form-select" name="etat_retour" required>
                                            <option value="bon">Bon état</option>
                                            <option value="moyen">État moyen</option>
                                            <option value="mauvais">Mauvais état</option>
                                        </select>
                                    </div>

                                    <button class="btn btn-warning w-100">Confirmer le retrait</button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-between align-items-center mt-3">
        <p class="small text-muted mb-0">
            {{ $assignments->firstItem() }}–{{ $assignments->lastItem() }} sur {{ $assignments->total() }} attribution(s)
        </p>
        {{ $assignments->links('pagination::bootstrap-5') }}
    </div>
@else
<div class="text-center py-5">
    <i class="bi bi-clock-history fs-1 text-muted"></i>
    <p class="text-muted mt-2">Aucune attribution enregistrée pour l'instant.</p>
</div>
@endif

@endsection
