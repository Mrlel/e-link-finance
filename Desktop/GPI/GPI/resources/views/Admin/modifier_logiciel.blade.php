@extends('layouts.main-board')
@section('title', 'Modifier un logiciel')
@section('content')

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('list_logiciel') }}" class="text-decoration-none">Logiciels</a>
                    </li>
                    <li class="breadcrumb-item active">Modifier {{ $licences->designation_licence }}</li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold text-dark d-flex align-items-center gap-2">
                <i class="bi bi-pencil-square text-primary"></i> Modifier le logiciel
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
                    <form action="{{ url('update_logiciel/' . $licences->id) }}" method="POST">
                        @csrf

                        {{-- Identification --}}
                        <h6 class="fw-bold text-muted text-uppercase mb-3 border-bottom pb-2">
                            <i class="bi bi-card-text me-1"></i> Identification
                        </h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Désignation <span class="text-danger">*</span></label>
                                <input type="text" name="designation_licence"
                                       class="form-control @error('designation_licence') is-invalid @enderror"
                                       value="{{ old('designation_licence', $licences->designation_licence) }}" required>
                                @error('designation_licence')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Type de logiciel</label>
                                <select name="type_licence" class="form-select">
                                    <option value="" {{ !$licences->type_licence ? 'selected' : '' }}>— Choisir —</option>
                                    @foreach(['Système d\'exploitation','Application bureautique','Logiciel de développement','Antivirus','Autre'] as $type)
                                        <option value="{{ $type }}" {{ old('type_licence', $licences->type_licence) == $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Clé de licence</label>
                                <input type="text" name="cle_licence" class="form-control"
                                       value="{{ old('cle_licence', $licences->cle_licence) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Date d'expiration</label>
                                <input type="date" name="date_expiration" class="form-control"
                                       value="{{ old('date_expiration', $licences->date_expiration) }}">
                            </div>
                        </div>

                        {{-- Environnement technique --}}
                        <h6 class="fw-bold text-muted text-uppercase mb-3 border-bottom pb-2">
                            <i class="bi bi-hdd-stack me-1"></i> Environnement technique
                        </h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Environnement</label>
                                <select name="environnement" class="form-select">
                                    <option value="" {{ !$licences->environnement ? 'selected' : '' }}>— Choisir —</option>
                                    @foreach(['Windows','Linux','MacOS','Multi-plateforme'] as $env)
                                        <option value="{{ $env }}" {{ old('environnement', $licences->environnement) == $env ? 'selected' : '' }}>{{ $env }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Langage / Version</label>
                                <input type="text" name="langage_version" class="form-control"
                                       value="{{ old('langage_version', $licences->langage_version) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">SGBD et version</label>
                                <input type="text" name="sgbd_version" class="form-control"
                                       value="{{ old('sgbd_version', $licences->sgbd_version) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Base de données</label>
                                <select name="base_donnees" class="form-select">
                                    <option value="" {{ !$licences->base_donnees ? 'selected' : '' }}>— Aucune —</option>
                                    @foreach(['MySQL','PostgreSQL','Oracle'] as $bdd)
                                        <option value="{{ $bdd }}" {{ old('base_donnees', $licences->base_donnees) == $bdd ? 'selected' : '' }}>{{ $bdd }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Libellé <span class="text-danger">*</span></label>
                                <textarea name="libelle_licence" class="form-control @error('libelle_licence') is-invalid @enderror"
                                          rows="3" required>{{ old('libelle_licence', $licences->libelle_licence) }}</textarea>
                                @error('libelle_licence')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <hr class="my-4 opacity-25">
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('list_logiciel') }}" class="btn btn-link text-muted text-decoration-none">Annuler</a>
                            <button type="submit" class="btn btn-success px-4">
                                <i class="bi bi-check-lg me-2"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4 d-none d-lg-block">
            <div class="card border-0 bg-primary bg-opacity-10">
                <div class="card-body">
                    <h6 class="fw-bold text-primary"><i class="bi bi-info-circle me-2"></i>Rappel</h6>
                    <p class="small mb-0">
                        Les fichiers joints (base de données, application) ne sont pas modifiables depuis cette page.
                        Supprimez et recréez le logiciel si vous devez les remplacer.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
