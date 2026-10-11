@extends('layouts.main-board')
@section('title', 'Sorties véhicules')
@section('content')

<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between py-4 gap-3">
    <div>
        <h1 class="h3 fw-bold mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-box-arrow-right text-danger"></i> Sorties de véhicules
        </h1>
        <p class="text-muted mb-0">Maintenance externe • Réformes • Enlèvements • Transferts</p>
    </div>
    <a href="{{ route('vehicules.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Retour au parc
    </a>
</div>

@include('layouts.message')

<form method="GET" action="{{ route('sorties-vehicules.index') }}" class="card shadow-sm mb-4">
    <div class="card-body py-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small text-muted mb-1">Recherche</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control"
                           placeholder="Immatriculation, marque…" value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1">Type</label>
                <select name="type_sortie" class="form-select">
                    <option value="">Tous</option>
                    @foreach(\App\Models\SortieVehicule::$typeLabels as $val => $label)
                        <option value="{{ $val }}" {{ request('type_sortie') == $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1">Statut</label>
                <select name="statut" class="form-select">
                    <option value="">Tous</option>
                    @foreach(\App\Models\SortieVehicule::$statutLabels as $val => $label)
                        <option value="{{ $val }}" {{ request('statut') == $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                <a href="{{ route('sorties-vehicules.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </div>
    </div>
</form>

@if($sorties->count() > 0)
<div class="table-responsive shadow-sm rounded">
    <table class="table table-striped table-hover align-middle mb-0">
        <thead class="table-dark">
            <tr>
                <th>Véhicule</th>
                <th>Type</th>
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
                    'enlevement'          => 'bg-danger',
                    'reforme'             => 'bg-secondary',
                    default               => 'bg-primary',
                };
                $enRetard = $s->statut === 'en_cours' && $s->date_retour_prevue && $s->date_retour_prevue->isPast();
            @endphp
            <tr>
                <td>
                    @if($s->vehicule)
                    <a href="{{ route('vehicules.show', $s->vehicule->id) }}" class="fw-semibold text-decoration-none">
                        {{ $s->vehicule->immatriculation }}
                    </a>
                    <div class="small text-muted">{{ $s->vehicule->marque }} {{ $s->vehicule->modele }}</div>
                    @else <span class="text-muted">—</span> @endif
                </td>
                <td><span class="badge {{ $typeColor }}">{{ $s->type_label }}</span></td>
                <td class="text-truncate" style="max-width:160px;" title="{{ $s->motif }}">{{ $s->motif }}</td>
                <td>{{ $s->prestataire ?? '—' }}</td>
                <td>{{ $s->date_sortie->format('d/m/Y') }}</td>
                <td>
                    @if($s->date_retour_prevue)
                        <span class="{{ $enRetard ? 'text-danger fw-semibold' : '' }}">
                            {{ $s->date_retour_prevue->format('d/m/Y') }}
                            @if($enRetard) <i class="bi bi-exclamation-triangle-fill"></i> @endif
                        </span>
                    @else <span class="text-muted">—</span> @endif
                </td>
                <td><span class="badge {{ $statutColor }}">{{ $s->statut_label }}</span></td>
                <td>{{ $s->demandeur->nom ?? '—' }}</td>
                <td class="text-center">
                    <div class="d-flex gap-1 justify-content-center">
                        <a href="{{ route('sorties-vehicules.show', $s->id) }}"
                           class="btn btn-sm btn-outline-info" title="Détails">
                            <i class="bi bi-eye"></i>
                        </a>
                        @if($s->statut === 'en_cours' && $s->type_sortie === 'maintenance_externe')
                        <button class="btn btn-sm btn-outline-success" title="Enregistrer le retour"
                                data-bs-toggle="modal" data-bs-target="#retourModal"
                                data-sortie-id="{{ $s->id }}"
                                data-vehicule="{{ $s->vehicule->immatriculation ?? '' }}">
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
                <h5 class="modal-title"><i class="bi bi-arrow-return-left me-2"></i>Retour du véhicule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="retourForm" method="POST" action="">
                @csrf @method('PUT')
                <div class="modal-body">
                    <p class="text-muted mb-3">Véhicule : <strong id="retourVehicule"></strong></p>
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
    document.getElementById('retourVehicule').textContent = btn.getAttribute('data-vehicule');
    document.getElementById('retourForm').action = '/sorties-vehicules/' + btn.getAttribute('data-sortie-id') + '/retour';
});
</script>

@endsection
