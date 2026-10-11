@extends('layouts.main-board')
@section('title', 'Modifier un équipement')
@section('content')

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">
                        <a href="/stock_materiel" class="text-decoration-none">Matériel informatique</a>
                    </li>
                    <li class="breadcrumb-item active">Modifier {{ $equipement->des_equipement }}</li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold text-dark d-flex align-items-center gap-2">
                <i class="bi bi-pencil-square text-primary"></i> Modifier l'équipement
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
                    <form action="{{ route('equipement.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $equipement->id }}">

                        {{-- Identification --}}
                        <h6 class="fw-bold text-muted text-uppercase mb-3 border-bottom pb-2">
                            <i class="bi bi-card-text me-1"></i> Identification
                        </h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Désignation <span class="text-danger">*</span></label>
                                <input type="text" name="des_equipement"
                                       class="form-control @error('des_equipement') is-invalid @enderror"
                                       value="{{ old('des_equipement', $equipement->des_equipement) }}" required>
                                @error('des_equipement')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">N° Inventaire</label>
                                <input type="text" name="num_inventaire" class="form-control"
                                       value="{{ old('num_inventaire', $equipement->num_inventaire) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nature <span class="text-danger">*</span></label>
                                <select name="nature" class="form-select @error('nature') is-invalid @enderror" required>
                                    @foreach(\App\Models\Equipement::NATURES as $n)
                                        <option value="{{ $n }}" {{ old('nature', $equipement->nature) == $n ? 'selected' : '' }}>{{ ucfirst($n) }}</option>
                                    @endforeach
                                </select>
                                @error('nature')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Catégorie <span class="text-danger">*</span></label>
                                <select name="categorie" class="form-select @error('categorie') is-invalid @enderror" required>
                                    @foreach(\App\Models\Equipement::CATEGORIES as $c)
                                        <option value="{{ $c }}" {{ old('categorie', $equipement->categorie) == $c ? 'selected' : '' }}>{{ $c }}</option>
                                    @endforeach
                                </select>
                                @error('categorie')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Marque <span class="text-danger">*</span></label>
                                <input type="text" name="marque"
                                       class="form-control @error('marque') is-invalid @enderror"
                                       value="{{ old('marque', $equipement->marque) }}" required>
                                @error('marque')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Modèle <span class="text-danger">*</span></label>
                                <input type="text" name="modele"
                                       class="form-control @error('modele') is-invalid @enderror"
                                       value="{{ old('modele', $equipement->modele) }}" required>
                                @error('modele')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">N° de série <span class="text-danger">*</span></label>
                                <input type="text" name="numero_serie"
                                       class="form-control @error('numero_serie') is-invalid @enderror"
                                       value="{{ old('numero_serie', $equipement->numero_serie) }}" required>
                                @error('numero_serie')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Adresse MAC</label>
                                <input type="text" name="adresse_mac" id="adresse_mac" class="form-control"
                                       value="{{ old('adresse_mac', $equipement->adresse_mac) }}">
                            </div>
                        </div>

                        {{-- État & Acquisition --}}
                        <h6 class="fw-bold text-muted text-uppercase mb-3 border-bottom pb-2">
                            <i class="bi bi-info-circle me-1"></i> État & Acquisition
                        </h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">État <span class="text-danger">*</span></label>
                                <select name="etat" class="form-select @error('etat') is-invalid @enderror" required>
                                    @foreach(\App\Models\Equipement::ETATS as $e)
                                        <option value="{{ $e }}" {{ old('etat', $equipement->etat) == $e ? 'selected' : '' }}>{{ ucfirst($e) }}</option>
                                    @endforeach
                                </select>
                                @error('etat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Mode d'acquisition <span class="text-danger">*</span></label>
                                <select name="source_acquisition" class="form-select @error('source_acquisition') is-invalid @enderror" required>
                                    @foreach(\App\Models\Equipement::SOURCES_ACQUISITION as $s)
                                        <option value="{{ $s }}" {{ old('source_acquisition', $equipement->source_acquisition) == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                    @endforeach
                                </select>
                                @error('source_acquisition')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Date d'acquisition <span class="text-danger">*</span></label>
                                <input type="date" name="date_acquis"
                                       class="form-control @error('date_acquis') is-invalid @enderror"
                                       value="{{ old('date_acquis', $equipement->date_acquis?->format('Y-m-d')) }}" required>
                                @error('date_acquis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Date de fin de vie
                                    <span class="text-muted fw-normal small">(optionnel)</span>
                                </label>
                                <input type="date" name="date_fin_vie" class="form-control"
                                       value="{{ old('date_fin_vie', $equipement->date_fin_vie?->format('Y-m-d')) }}">
                            </div>
                        </div>

                        {{-- Spécifications techniques --}}
                        <h6 class="fw-bold text-muted text-uppercase mb-3 border-bottom pb-2">
                            <i class="bi bi-cpu me-1"></i> Spécifications techniques
                        </h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Système d'exploitation</label>
                                <input type="text" name="systeme" class="form-control"
                                       value="{{ old('systeme', $equipement->systeme) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Processeur</label>
                                <input type="text" name="processeur" class="form-control"
                                       value="{{ old('processeur', $equipement->processeur) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Disque dur (Go)</label>
                                <input type="number" name="capacite" class="form-control"
                                       value="{{ old('capacite', $equipement->capacite) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">RAM (Go)</label>
                                <input type="number" name="ram" class="form-control"
                                       value="{{ old('ram', $equipement->ram) }}">
                            </div>
                        </div>

                        <hr class="my-4 opacity-25">
                        <div class="d-flex justify-content-end gap-3">
                            <a href="/stock_materiel" class="btn btn-link text-muted text-decoration-none">Annuler</a>
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
                        Le numéro de série doit rester unique. Le statut et l'affectation de l'équipement se gèrent depuis les pages dédiées, pas ici.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const mac = document.getElementById('adresse_mac');
    if (mac) {
        mac.addEventListener('input', function () {
            let value = this.value.toUpperCase().replace(/[^0-9A-F]/g, '');
            if (value.length > 2) value = value.match(/.{1,2}/g).join(':');
            if (value.length > 17) value = value.substring(0, 17);
            this.value = value;
        });
    }
});
</script>

@endsection
