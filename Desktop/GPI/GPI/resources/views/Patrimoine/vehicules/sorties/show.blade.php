@extends('layouts.main-board')
@section('title', 'Détail sortie véhicule')
@section('content')

@php
    $statutColor = match($sortie->statut) {
        'retourne'  => 'bg-success',
        'definitif' => 'bg-secondary',
        default     => 'bg-warning text-dark',
    };
    $typeColor = match($sortie->type_sortie) {
        'maintenance_externe' => 'bg-info text-dark',
        'enlevement'          => 'bg-danger',
        'reforme'             => 'bg-secondary',
        default               => 'bg-primary',
    };
@endphp

<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between py-4 gap-3">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('sorties-vehicules.index') }}" class="text-decoration-none">Sorties véhicules</a></li>
                <li class="breadcrumb-item active">Sortie #{{ $sortie->id }}</li>
            </ol>
        </nav>
        <h1 class="h3 fw-bold mb-1">
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
        <a href="{{ route('sorties-vehicules.index') }}" class="btn btn-outline-secondary">Retour</a>
    </div>
</div>

@include('layouts.message')

<div class="row g-4">
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

    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-dark text-white fw-semibold">
                <i class="bi bi-car-front me-2"></i>Véhicule concerné
            </div>
            <div class="card-body">
                @if($sortie->vehicule)
                <dl class="row mb-0">
                    <dt class="col-5 text-muted">Immatriculation</dt>
                    <dd class="col-7">
                        <a href="{{ route('vehicules.show', $sortie->vehicule->id) }}" class="fw-semibold text-decoration-none">
                            {{ $sortie->vehicule->immatriculation }}
                        </a>
                    </dd>
                    <dt class="col-5 text-muted">Marque / Modèle</dt>
                    <dd class="col-7">{{ $sortie->vehicule->marque }} {{ $sortie->vehicule->modele }}</dd>
                    <dt class="col-5 text-muted">Catégorie</dt>
                    <dd class="col-7">{{ ucfirst($sortie->vehicule->categorie) }}</dd>
                    <dt class="col-5 text-muted">Statut actuel</dt>
                    <dd class="col-7"><span class="badge bg-secondary">{{ $sortie->vehicule->statut }}</span></dd>
                </dl>
                @else
                    <p class="text-muted">Véhicule introuvable.</p>
                @endif
            </div>
        </div>
    </div>

    @if($sortie->motif || $sortie->observations)
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light fw-semibold">
                <i class="bi bi-chat-left-text me-2"></i>Motif & Observations
            </div>
            <div class="card-body">
                @if($sortie->motif)
                <p class="mb-2"><strong>Motif :</strong> {{ $sortie->motif }}</p>
                @endif
                @if($sortie->observations)
                <p class="mb-0"><strong>Observations :</strong> {{ $sortie->observations }}</p>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

@if($sortie->statut === 'en_cours' && $sortie->type_sortie === 'maintenance_externe')
<div class="modal fade" id="retourModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Retour du véhicule {{ $sortie->vehicule->immatriculation ?? '' }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('sorties-vehicules.retour', $sortie->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Date de retour effectif <span class="text-danger">*</span></label>
                        <input type="date" name="date_retour_effective" class="form-control"
                               value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Observations</label>
                        <textarea name="observations" class="form-control" rows="3"></textarea>
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
