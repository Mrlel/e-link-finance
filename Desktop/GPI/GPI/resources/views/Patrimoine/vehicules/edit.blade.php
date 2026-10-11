@extends('layouts.main-board')
@section('title', 'Modifier un véhicule')
@section('content')

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('vehicules.index') }}" class="text-decoration-none">Parc Automobile</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('vehicules.show', $vehicule->id) }}" class="text-decoration-none">{{ $vehicule->immatriculation }}</a>
                    </li>
                    <li class="breadcrumb-item active">Modifier</li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold text-dark d-flex align-items-center gap-2">
                <i class="bi bi-pencil-square text-primary"></i> Modifier le véhicule
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            @if($errors->any())
                <div class="alert alert-danger border-0 shadow-sm mb-4">
                    <ul class="mb-0 small">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body p-4">
                    <form action="{{ route('vehicules.update', $vehicule->id) }}" method="POST">
                        @csrf @method('PUT')

                        <h6 class="fw-bold text-muted text-uppercase mb-3 border-bottom pb-2">
                            <i class="bi bi-card-text me-1"></i> Identification
                        </h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Immatriculation <span class="text-danger">*</span></label>
                                <input type="text" name="immatriculation"
                                       class="form-control @error('immatriculation') is-invalid @enderror"
                                       value="{{ old('immatriculation', $vehicule->immatriculation) }}" required>
                                @error('immatriculation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Numéro de châssis</label>
                                <input type="text" name="numero_chassis"
                                       class="form-control @error('numero_chassis') is-invalid @enderror"
                                       value="{{ old('numero_chassis', $vehicule->numero_chassis) }}">
                                @error('numero_chassis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Catégorie <span class="text-danger">*</span></label>
                                <select name="categorie" class="form-select" required>
                                    <option value="auto" {{ old('categorie', $vehicule->categorie) == 'auto' ? 'selected' : '' }}>Automobile</option>
                                    <option value="moto" {{ old('categorie', $vehicule->categorie) == 'moto' ? 'selected' : '' }}>Moto</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Marque</label>
                                <input type="text" name="marque" class="form-control"
                                       value="{{ old('marque', $vehicule->marque) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Modèle</label>
                                <input type="text" name="modele" class="form-control"
                                       value="{{ old('modele', $vehicule->modele) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Couleur</label>
                                <input type="text" name="couleur" class="form-control"
                                       value="{{ old('couleur', $vehicule->couleur) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Date de mise en circulation</label>
                                <input type="date" name="date_mec" class="form-control"
                                       value="{{ old('date_mec', $vehicule->date_mec?->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Lieu d'utilisation</label>
                                <input type="text" name="lieu_utilisation" class="form-control"
                                       value="{{ old('lieu_utilisation', $vehicule->lieu_utilisation) }}">
                            </div>
                        </div>

                        <h6 class="fw-bold text-muted text-uppercase mb-3 border-bottom pb-2">
                            <i class="bi bi-info-circle me-1"></i> État & Acquisition
                        </h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">État <span class="text-danger">*</span></label>
                                <select name="etat" class="form-select" required>
                                    @foreach(['NEUF','BON','MOYEN','MAUVAIS','HORS SERVICE'] as $e)
                                        <option value="{{ $e }}" {{ old('etat', $vehicule->etat) == $e ? 'selected' : '' }}>{{ $e }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Mode d'acquisition <span class="text-danger">*</span></label>
                                <select name="mode_acquisition" class="form-select" required>
                                    <option value="BUDGET ETAT" {{ old('mode_acquisition', $vehicule->mode_acquisition) == 'BUDGET ETAT' ? 'selected' : '' }}>Budget État</option>
                                    <option value="DON"         {{ old('mode_acquisition', $vehicule->mode_acquisition) == 'DON'         ? 'selected' : '' }}>Don</option>
                                </select>
                            </div>
                        </div>

                        <hr class="my-4 opacity-25">
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('vehicules.show', $vehicule->id) }}" class="btn btn-link text-muted text-decoration-none">Annuler</a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-lg me-2"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
