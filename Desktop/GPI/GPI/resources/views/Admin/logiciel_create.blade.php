@extends('layouts.main-board')
@section('title', 'Ajouter un logiciel')
@section('content')

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('list_logiciel') }}" class="text-decoration-none">Logiciels</a>
                    </li>
                    <li class="breadcrumb-item active">Ajouter un logiciel</li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold text-dark d-flex align-items-center gap-2">
                <i class="bi bi-plus-circle-dotted text-success"></i> Nouveau logiciel
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
                    <form action="/logiciel/traitement" method="POST" enctype="multipart/form-data">
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
                                       value="{{ old('designation_licence') }}" placeholder="Ex: Microsoft Office 365" required>
                                @error('designation_licence')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Type de logiciel <span class="text-danger">*</span></label>
                                <select name="type_licence" class="form-select @error('type_licence') is-invalid @enderror" required>
                                    <option value="" disabled selected>— Choisir —</option>
                                    @foreach(['Système d\'exploitation','Application bureautique','Logiciel de développement','Antivirus','Autre'] as $type)
                                        <option value="{{ $type }}" {{ old('type_licence') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('type_licence')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Clé de licence</label>
                                <input type="text" name="cle_licence" class="form-control"
                                       value="{{ old('cle_licence') }}" placeholder="XXXXX-XXXXX-XXXXX">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Date d'expiration</label>
                                <input type="date" name="date_expiration" class="form-control"
                                       value="{{ old('date_expiration') }}">
                            </div>
                        </div>

                        {{-- Environnement technique --}}
                        <h6 class="fw-bold text-muted text-uppercase mb-3 border-bottom pb-2">
                            <i class="bi bi-hdd-stack me-1"></i> Environnement technique
                        </h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Environnement <span class="text-danger">*</span></label>
                                <select name="environnement" class="form-select @error('environnement') is-invalid @enderror" required>
                                    <option value="" disabled selected>— Choisir —</option>
                                    @foreach(['Windows','Linux','MacOS','Multi-plateforme'] as $env)
                                        <option value="{{ $env }}" {{ old('environnement') == $env ? 'selected' : '' }}>{{ $env }}</option>
                                    @endforeach
                                </select>
                                @error('environnement')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Langage / Version <span class="text-danger">*</span></label>
                                <input type="text" name="langage_version"
                                       class="form-control @error('langage_version') is-invalid @enderror"
                                       value="{{ old('langage_version') }}" placeholder="Ex: PHP 8.2" required>
                                @error('langage_version')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">SGBD et version <span class="text-danger">*</span></label>
                                <input type="text" name="sgbd_version"
                                       class="form-control @error('sgbd_version') is-invalid @enderror"
                                       value="{{ old('sgbd_version') }}" placeholder="Ex: MySQL 8.0" required>
                                @error('sgbd_version')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Base de données</label>
                                <select name="base_donnees" class="form-select">
                                    <option value="" selected>— Aucune —</option>
                                    @foreach(['MySQL','PostgreSQL','Oracle'] as $bdd)
                                        <option value="{{ $bdd }}" {{ old('base_donnees') == $bdd ? 'selected' : '' }}>{{ $bdd }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Fichier base de données</label>
                                <input type="file" name="fichier_licence" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Fichier application</label>
                                <input type="file" name="fichier_app" class="form-control">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Libellé <span class="text-danger">*</span></label>
                                <textarea name="libelle_licence" class="form-control @error('libelle_licence') is-invalid @enderror"
                                          rows="3" placeholder="Description libre du logiciel…" required>{{ old('libelle_licence') }}</textarea>
                                @error('libelle_licence')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <hr class="my-4 opacity-25">
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('list_logiciel') }}" class="btn btn-link text-muted text-decoration-none">Annuler</a>
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
                        Renseignez la <strong>date d'expiration</strong> pour que ce logiciel remonte dans les alertes de licences bientôt expirées.
                        Les champs <span class="text-danger">*</span> sont obligatoires.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
