@extends('layouts.main-board')
@section('title', 'Modifier un article')
@section('content')

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('patrimoine-divers.index') }}" class="text-decoration-none">Patrimoine</a></li>
                    <li class="breadcrumb-item active">Modifier</li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold text-dark d-flex align-items-center gap-2">
                <i class="bi bi-pencil-square text-warning"></i> Modifier l'article
            </h1>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2">Mode Édition</span>
                <span class="text-muted fw-medium">{{ $item->libelle }}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 col-xl-7">
            @if($errors->any())
                <div class="alert alert-danger border-0 shadow-sm mb-4 d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-octagon-fill me-3 fs-4"></i>
                    <ul class="mb-0 small">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('patrimoine-divers.update', $item->id) }}" method="POST">
                        @csrf 
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary">Libellé de l'article <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-tag"></i></span>
                                <input type="text" name="libelle" 
                                       class="form-control bg-light border-start-0 @error('libelle') is-invalid @enderror" 
                                       value="{{ old('libelle', $item->libelle) }}" required>
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary">Quantité en stock <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-box-seam"></i></span>
                                    <input type="number" name="nombre" 
                                           class="form-control bg-light border-start-0" 
                                           value="{{ old('nombre', $item->nombre) }}" min="0" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary">Catégorie</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-collection"></i></span>
                                    <select name="categorie" class="form-select bg-light border-start-0">
                                        <option value="">— Sélectionner —</option>
                                        @foreach(['fournitures', 'consommables', 'autre'] as $cat)
                                            <option value="{{ $cat }}" {{ old('categorie', $item->categorie) == $cat ? 'selected' : '' }}>
                                                {{ ucfirst($cat) }}
                                            </option>
                                        @endforeach
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
                                            <option value="{{ $c }}" {{ old('couleur', $item->couleur) == $c ? 'selected' : '' }}>{{ $c }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('couleur')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary">État actuel <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-info-circle"></i></span>
                                    <select name="etat" class="form-select bg-light border-start-0" required>
                                        @foreach(['neuf' => 'Neuf', 'bon' => 'Bon', 'use' => 'Usé'] as $val => $label)
                                            <option value="{{ $val }}" {{ old('etat', $item->etat) == $val ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary">Date d'acquisition</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-calendar3"></i></span>
                                    <input type="date" name="date_acquisition" 
                                           class="form-control bg-light border-start-0" 
                                           value="{{ old('date_acquisition', $item->date_acquisition) }}">
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 opacity-50">

                        <div class="d-flex justify-content-end align-items-center gap-3">
                            <a href="{{ route('patrimoine-divers.index') }}" class="btn btn-link text-decoration-none text-muted fw-semibold">
                                Abandonner les modifications
                            </a>
                            <button type="submit" class="btn btn-warning px-4 shadow-sm fw-bold">
                                <i class="bi bi-arrow-clockwise me-2"></i>Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4 d-none d-lg-block">
            <div class="card border-0 shadow-sm bg-dark text-white mb-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="bi bi-clock-history me-2 text-warning"></i> Historique</h6>
                    <p class="small opacity-75 mb-0">
                        Dernière modification : <br>
                        <strong>{{ $item->updated_at ? $item->updated_at->format('d/m/Y à H:i') : 'Inconnue' }}</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection