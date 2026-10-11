@extends('layouts.main-board')
@section('title', 'Sorties équipements')
@section('content')

<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between py-4 gap-3">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-box-arrow-right text-danger"></i> Sorties d'équipements
        </h1>
        <p class="text-muted mb-0">Maintenance externe • Réformes • Transferts</p>
    </div>
    <a href="{{ route('materiels.stock') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Retour au stock
    </a>
</div>

@include('layouts.message')

{{-- Filtres --}}
<form method="GET" action="{{ route('sorties-equipements.index') }}" class="card shadow-sm mb-4">
    <div class="card-body py-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small text-muted mb-1">Recherche</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control"
                           placeholder="Désignation, N° série…" value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1">Type</label>
                <select name="type_sortie" class="form-select">
                    <option value="">Tous</option>
                    <option value="maintenance_externe" {{ request('type_sortie') == 'maintenance_externe' ? 'selected' : '' }}>Maintenance externe</option>
                    <option value="reforme"             {{ request('type_sortie') == 'reforme'             ? 'selected' : '' }}>Réforme</option>
                    <option value="transfert"           {{ request('type_sortie') == 'transfert'           ? 'selected' : '' }}>Transfert</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1">Statut</label>
                <select name="statut" class="form-select">
                    <option value="">Tous</option>
                    <option value="en_cours"  {{ request('statut') == 'en_cours'  ? 'selected' : '' }}>En cours</option>
                    <option value="retourne"  {{ request('statut') == 'retourne'  ? 'selected' : '' }}>Retourné</option>
                    <option value="definitif" {{ request('statut') == 'definitif' ? 'selected' : '' }}>Définitif</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                <a href="{{ route('sorties-equipements.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </div>
    </div>
</form>

@if($sorties->count() > 0)
<div class="table-responsive shadow-sm rounded">
    <table class="table table-striped table-hover align-middle mb-0">
        <thead class="table-dark">
            <tr>
                <th>Équipement</th>
                <th>Type de sortie</th>
                <th>Motif</th>
                <th>Prestataire</th>
                <th>Date sortie</th>
                <th>Retour prévu</th>
                <th>Statut</th>
                <th>Demandeur</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sorties as $s)
            @php
                $statutColor = match($s->statut) {
                    'retourne'  => 'bg-success',
                    'definitif' => 'bg-secondary',
                    default     => 'bg-warning text-dark',
                };
                $typeColor = match($s->type_sortie) {
                    'maintenance_externe' => 'bg-info text-dark',
                    'reforme'             => 'bg-danger',
                    default               => 'bg-primary',
                };
            @endphp
            <tr>
                <td>
                    <a href="{{ route('equipement.details', $s->equipement_id) }}" class="fw-semibold text-decoration-none">
                        {{ $s->equipement->des_equipement ?? '—' }}
                    </a>
                    <div class="small text-muted">{{ $s->equipement->numero_serie ?? '' }}</div>
                </td>
                <td><span class="badge {{ $typeColor }}">{{ $s->type_label }}</span></td>
                <td class="text-truncate" style="max-width:180px;" title="{{ $s->motif }}">{{ $s->motif }}</td>
                <td>{{ $s->prestataire ?? '—' }}</td>
                <td>{{ $s->date_sortie->format('d/m/Y') }}</td>
                <td>
                    @if($s->date_retour_prevue)
                        @php $enRetard = $s->statut === 'en_cours' && $s->date_retour_prevue->isPast(); @endphp
                        <span class="{{ $enRetard ? 'text-danger fw-semibold' : '' }}">
                            {{ $s->date_retour_prevue->format('d/m/Y') }}
                            @if($enRetard) <i class="bi bi-exclamation-triangle-fill"></i> @endif
                        </span>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td><span class="badge {{ $statutColor }}">{{ $s->statut_label }}</span></td>
                <td>{{ $s->demandeur->nom ?? '—' }}</td>
                <td class="text-center">
                    <div class="d-flex gap-1 justify-content-center">
                        <a href="{{ route('sorties-equipements.show', $s->id) }}"
                           class="btn btn-sm btn-outline-info" title="Détails">
                            <i class="bi bi-eye"></i>
                        </a>
                        @if($s->statut === 'en_cours' && $s->type_sortie === 'maintenance_externe')
                        <button class="btn btn-sm btn-outline-success" title="Enregistrer le retour"
                                data-bs-toggle="modal" data-bs-target="#retourModal"
                                data-sortie-id="{{ $s->id }}"
                                data-equipement="{{ $s->equipement->des_equipement ?? '' }}">
                            <i class="bi bi-arrow-return-left"></i>
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="text-center py-5">
    <i class="bi bi-box-arrow-right fs-1 text-muted"></i>
    <p class="text-muted mt-2">Aucune sortie enregistrée</p>
</div>
@endif

{{-- Modal retour --}}
<div class="modal fade" id="retourModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-arrow-return-left me-2"></i>Retour de l'équipement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="retourForm" method="POST" action="">
                @csrf @method('PUT')
                <div class="modal-body">
                    <p class="text-muted mb-3">Équipement : <strong id="retourEquipement"></strong></p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Date de retour effectif <span class="text-danger">*</span></label>
                        <input type="date" name="date_retour_effective" class="form-control"
                               value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Observations</label>
                        <textarea name="observations" class="form-control" rows="3"
                                  placeholder="État à la réception, travaux effectués…"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i> Confirmer le retour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('retourModal').addEventListener('show.bs.modal', function (e) {
    const btn = e.relatedTarget;
    document.getElementById('retourEquipement').textContent = btn.getAttribute('data-equipement');
    document.getElementById('retourForm').action = '/sorties-equipements/' + btn.getAttribute('data-sortie-id') + '/retour';
});
</script>

@endsection
