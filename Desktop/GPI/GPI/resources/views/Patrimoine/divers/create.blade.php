@extends('layouts.main-board')
@section('title', 'Ajouter un article')
@section('content')

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('patrimoine-divers.index') }}" class="text-decoration-none">Patrimoine</a></li>
                    <li class="breadcrumb-item active">Ajouter un article</li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold text-dark d-flex align-items-center gap-2">
                <i class="bi bi-plus-circle-dotted text-primary"></i> Nouvel Article
            </h1>
            <p class="text-muted small">Enregistrez les fournitures, consommables et patrimoines divers dans l'inventaire.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 col-xl-7">
            @if($errors->any())
                <div class="alert alert-danger border-0 shadow-sm mb-4 d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
                    <ul class="mb-0 small">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('patrimoine-divers.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary">Libellé de l'article <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-tag"></i></span>
                                <input type="text" name="libelle" 
                                       class="form-control bg-light border-start-0 @error('libelle') is-invalid @enderror" 
                                       value="{{ old('libelle') }}"
                                       placeholder="Ex: Cartouche HP 305, Cahier 200 pages…" required>
                            </div>
                            @error('libelle')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary">Quantité <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-box-seam"></i></span>
                                    <input type="number" name="nombre" 
                                           class="form-control bg-light border-start-0 @error('nombre') is-invalid @enderror" 
                                           value="{{ old('nombre', 1) }}" min="0" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary">Catégorie</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-collection"></i></span>
                                    <select name="categorie" class="form-select bg-light border-start-0">
                                        <option value="" selected disabled>— Sélectionner —</option>
                                        <option value="fournitures" {{ old('categorie') == 'fournitures' ? 'selected' : '' }}>Fournitures</option>
                                        <option value="consommables" {{ old('categorie') == 'consommables' ? 'selected' : '' }}>Consommables</option>
                                        <option value="autre" {{ old('categorie') == 'autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary">Couleur</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-palette"></i></span>
                                    <select name="couleur" class="form-select bg-light border-start-0 @error('couleur') is-invalid @enderror">
                                        <option value="">— Non applicable —</option>
                                        @foreach(\App\Models\PatrimoineDivers::COULEURS as $c)
                                            <option value="{{ $c }}" {{ old('couleur') == $c ? 'selected' : '' }}>{{ $c }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('couleur')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                <div class="form-text">Utile pour les cartouches d'encre, par exemple.</div>
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary">État du bien <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-info-circle"></i></span>
                                    <select name="etat" class="form-select bg-light border-start-0" required>
                                        <option value="neuf" {{ old('etat', 'neuf') == 'neuf' ? 'selected' : '' }}>Neuf</option>
                                        <option value="bon" {{ old('etat') == 'bon' ? 'selected' : '' }}>Bon</option>
                                        <option value="use" {{ old('etat') == 'use' ? 'selected' : '' }}>Usé</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary">Date d'acquisition</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-calendar3"></i></span>
                                    <input type="date" name="date_acquisition" 
                                           class="form-control bg-light border-start-0" 
                                           value="{{ old('date_acquisition') }}">
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 opacity-50">

                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('patrimoine-divers.index') }}" class="btn btn-link text-decoration-none text-muted fw-semibold">
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                <i class="bi bi-check-lg me-2"></i>Enregistrer l'article
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 d-none d-lg-block">
            <div class="card border-0 bg-primary bg-opacity-10 text-primary-emphasis">
                <div class="card-body">
                    <h6 class="fw-bold"><i class="bi bi-lightbulb me-2"></i> Aide rapide</h6>
                    <p class="small mb-0">Assurez-vous que la quantité est correcte pour la gestion automatique des stocks. Les champs marqués d'une astérisque (<span class="text-danger">*</span>) sont obligatoires.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection