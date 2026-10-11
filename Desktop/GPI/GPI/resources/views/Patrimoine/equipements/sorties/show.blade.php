@extends('layouts.main-board')
@section('title', 'Détail sortie')
@section('content')

@php
    $statutColor = match($sortie->statut) {
        'retourne'  => 'bg-success',
        'definitif' => 'bg-secondary',
        default     => 'bg-warning text-dark',
    };
    $typeColor = match($sortie->type_sortie) {
        'maintenance_externe' => 'bg-info text-dark',
        'reforme'             => 'bg-danger',
        default               => 'bg-primary',
    };
@endphp

<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between py-4 gap-3">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item">
                    <a href="{{ route('sorties-equipements.index') }}" class="text-decoration-none">Sorties</a>
                </li>
                <li class="breadcrumb-item active">Sortie #{{ $sortie->id }}</li>
            </ol>
        </nav>
        <h1 class="h3 fw-bold text-dark mb-1">
            <i class="bi bi-box-arrow-right text-danger me-2"></i>
            Sortie #{{ $sortie->id }} — {{ $sortie->type_label }}
        </h1>
    </div>
    <div class="d-flex gap-2">
        @if($sortie->statut === 'en_cours' && $sortie->type_sortie === 'maintenance_externe')
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#retourModal">
            <i class="bi bi-arrow-return-left me-1"></i> Enregistrer le retour
        </button>
        @endif
        <a href="{{ route('sorties-equipements.index') }}" class="btn btn-outline-secondary">Retour</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="row g-4">
    {{-- Infos sortie --}}
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-dark text-white fw-semibold">
                <i class="bi bi-info-circle me-2"></i>Informations de la sortie
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5 text-muted">Type</dt>
                    <dd class="col-7"><span class="badge {{ $typeColor }}">{{ $sortie->type_label }}</span></dd>

                    <dt class="col-5 text-muted">Statut</dt>
                    <dd class="col-7"><span class="badge {{ $statutColor }}">{{ $sortie->statut_label }}</span></dd>

                    <dt class="col-5 text-muted">Date de sortie</dt>
                    <dd class="col-7">{{ $sortie->date_sortie->format('d/m/Y') }}</dd>

                    <dt class="col-5 text-muted">Retour prévu</dt>
                    <dd class="col-7">{{ $sortie->date_retour_prevue?->format('d/m/Y') ?? '—' }}</dd>

                    <dt class="col-5 text-muted">Retour effectif</dt>
                    <dd class="col-7">{{ $sortie->date_retour_effective?->format('d/m/Y') ?? '—' }}</dd>

                    <dt class="col-5 text-muted">Prestataire</dt>
                    <dd class="col-7">{{ $sortie->prestataire ?? '—' }}</dd>

                    <dt class="col-5 text-muted">Destination</dt>
                    <dd class="col-7">{{ $sortie->lieu_destination ?? '—' }}</dd>

                    <dt class="col-5 text-muted">Demandeur</dt>
                    <dd class="col-7">{{ $sortie->demandeur->nom ?? '—' }} {{ $sortie->demandeur->prenom ?? '' }}</dd>

                    <dt class="col-5 text-muted">Retour validé par</dt>
                    <dd class="col-7">{{ $sortie->retourValidePar->nom ?? '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>

    {{-- Équipement concerné --}}
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-dark text-white fw-semibold">
                <i class="bi bi-pc-display me-2"></i>Équipement concerné
            </div>
            <div class="card-body">
                @if($sortie->equipement)
                <dl class="row mb-0">
                    <dt class="col-5 text-muted">Désignation</dt>
                    <dd class="col-7">
                        <a href="{{ route('equipement.details', $sortie->equipement_id) }}" class="text-decoration-none fw-semibold">
                            {{ $sortie->equipement->des_equipement }}
                        </a>
                    </dd>
                    <dt class="col-5 text-muted">N° Série</dt>
                    <dd class="col-7"><code>{{ $sortie->equipement->numero_serie }}</code></dd>
                    <dt class="col-5 text-muted">Marque / Modèle</dt>
                    <dd class="col-7">{{ $sortie->equipement->marque }} {{ $sortie->equipement->modele }}</dd>
                    <dt class="col-5 text-muted">Statut actuel</dt>
                    <dd class="col-7">
                        <span class="badge bg-secondary">{{ $sortie->equipement->statut }}</span>
                    </dd>
                </dl>
                @else
                    <p class="text-muted">Équipement introuvable.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Motif & Observations --}}
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light fw-semibold">
                <i class="bi bi-chat-left-text me-2"></i>Motif & Observations
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>Motif :</strong> {{ $sortie->motif }}</p>
                @if($sortie->observations)
                    <p class="mb-0"><strong>Observations :</strong> {{ $sortie->observations }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal retour --}}
@if($sortie->statut === 'en_cours' && $sortie->type_sortie === 'maintenance_externe')
<div class="modal fade" id="retourModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Retour de l'équipement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('sorties-equipements.retour', $sortie->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
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
                    <button type="submit" class="btn btn-success">Confirmer le retour</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection
