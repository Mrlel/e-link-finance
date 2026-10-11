@extends('layouts.main-board')
@section('title', 'Ajouter un mobilier')
@section('content')

<div class="container-fluid py-4">
    <div class="row mb-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('mobiliers.index') }}" class="text-decoration-none">Mobilier</a></li>
                    <li class="breadcrumb-item active">Ajouter</li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold d-flex align-items-center gap-2">
                <i class="bi bi-plus-circle-dotted text-success"></i> Nouveau mobilier
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            @if($errors->any())
                <div class="alert alert-danger border-0 shadow-sm mb-4">
                    <ul class="mb-0 small">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body p-4">
                    <form action="{{ route('mobiliers.store') }}" method="POST">
                        @csrf

                        <h6 class="fw-bold text-muted text-uppercase mb-3 border-bottom pb-2">
                            <i class="bi bi-card-text me-1"></i> Identification
                        </h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Désignation <span class="text-danger">*</span></label>
                                <input type="text" name="designation"
                                       class="form-control @error('designation') is-invalid @enderror"
                                       value="{{ old('designation') }}"
                                       placeholder="Ex: Bureau directeur, Chaise ergonomique…" required>
                                @error('designation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">N° Inventaire</label>
                                <input type="text" name="num_inventaire" class="form-control"
                                       value="{{ old('num_inventaire') }}" placeholder="MOB-0001">
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Marque</label>
                                <input type="text" name="marque" class="form-control"
                                       value="{{ old('marque') }}" placeholder="Ex: Steelcase, IKEA…">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Référence</label>
                                <input type="text" name="reference" class="form-control"
                                       value="{{ old('reference') }}" placeholder="Réf. fabricant">
                            </div>
                        </div>

                        <h6 class="fw-bold text-muted text-uppercase mb-3 border-bottom pb-2">
                            <i class="bi bi-info-circle me-1"></i> État & Acquisition
                        </h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">État <span class="text-danger">*</span></label>
                                <select name="etat" class="form-select @error('etat') is-invalid @enderror" required>
                                    <option value="" disabled selected>— Choisir —</option>
                                    @foreach(['neuf'=>'Neuf','bon'=>'Bon','moyen'=>'Moyen','mauvais'=>'Mauvais','hors service'=>'Hors service'] as $v=>$l)
                                        <option value="{{ $v }}" {{ old('etat') == $v ? 'selected' : '' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                                @error('etat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Mode d'acquisition <span class="text-danger">*</span></label>
                                <select name="mode_acquisition" class="form-select" required>
                                    <option value="budget etat" {{ old('mode_acquisition') == 'budget etat' ? 'selected' : '' }}>Budget État</option>
                                    <option value="don"         {{ old('mode_acquisition') == 'don'         ? 'selected' : '' }}>Don</option>
                                    <option value="autre"       {{ old('mode_acquisition') == 'autre'       ? 'selected' : '' }}>Autre</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Date d'acquisition</label>
                                <input type="date" name="date_acquisition" class="form-control"
                                       value="{{ old('date_acquisition') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Date de fin de durée de vie
                                    <span class="text-muted fw-normal small">(optionnel)</span>
                                </label>
                                <input type="date" name="date_fin_vie" class="form-control"
                                       value="{{ old('date_fin_vie') }}">
                                <div class="form-text">Alerte envoyée 30 jours avant.</div>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Observations</label>
                                <textarea name="observations" class="form-control" rows="2"
                                          placeholder="Informations complémentaires…">{{ old('observations') }}</textarea>
                            </div>
                        </div>

                        <hr class="opacity-25">
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('mobiliers.index') }}" class="btn btn-link text-muted text-decoration-none">Annuler</a>
                            <button type="submit" class="btn btn-success px-4">
                                <i class="bi bi-check-lg me-2"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4 d-none d-lg-block">
            <div class="card border-0 bg-success bg-opacity-10">
                <div class="card-body">
                    <h6 class="fw-bold text-success"><i class="bi bi-lightbulb me-2"></i>Aide</h6>
                    <p class="small mb-0">
                        Renseignez la <strong>date de fin de durée de vie</strong> pour recevoir une alerte automatique 30 jours avant l'échéance.
                        Les champs <span class="text-danger">*</span> sont obligatoires.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
