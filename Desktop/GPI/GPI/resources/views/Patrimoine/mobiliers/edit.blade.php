@extends('layouts.main-board')
@section('title', 'Modifier un mobilier')
@section('content')

<div class="container-fluid py-4">
    <div class="row mb-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('mobiliers.index') }}" class="text-decoration-none">Mobilier</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('mobiliers.show', $mobilier->id) }}" class="text-decoration-none">{{ $mobilier->designation }}</a></li>
                    <li class="breadcrumb-item active">Modifier</li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold d-flex align-items-center gap-2">
                <i class="bi bi-pencil-square text-primary"></i> Modifier le mobilier
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
                    <form action="{{ route('mobiliers.update', $mobilier->id) }}" method="POST">
                        @csrf @method('PUT')

                        <h6 class="fw-bold text-muted text-uppercase mb-3 border-bottom pb-2">Identification</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Désignation <span class="text-danger">*</span></label>
                                <input type="text" name="designation"
                                       class="form-control @error('designation') is-invalid @enderror"
                                       value="{{ old('designation', $mobilier->designation) }}" required>
                                @error('designation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">N° Inventaire</label>
                                <input type="text" name="num_inventaire" class="form-control"
                                       value="{{ old('num_inventaire', $mobilier->num_inventaire) }}">
                            </div>
                         
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Marque</label>
                                <input type="text" name="marque" class="form-control"
                                       value="{{ old('marque', $mobilier->marque) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Référence</label>
                                <input type="text" name="reference" class="form-control"
                                       value="{{ old('reference', $mobilier->reference) }}">
                            </div>
                        </div>

                        <h6 class="fw-bold text-muted text-uppercase mb-3 border-bottom pb-2">État & Acquisition</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">État <span class="text-danger">*</span></label>
                                <select name="etat" class="form-select" required>
                                    @foreach(['neuf'=>'Neuf','bon'=>'Bon','moyen'=>'Moyen','mauvais'=>'Mauvais','hors service'=>'Hors service'] as $v=>$l)
                                        <option value="{{ $v }}" {{ old('etat', $mobilier->etat) == $v ? 'selected' : '' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Mode d'acquisition <span class="text-danger">*</span></label>
                                <select name="mode_acquisition" class="form-select" required>
                                    @foreach(['budget etat'=>'Budget État','don'=>'Don','autre'=>'Autre'] as $v=>$l)
                                        <option value="{{ $v }}" {{ old('mode_acquisition', $mobilier->mode_acquisition) == $v ? 'selected' : '' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Date d'acquisition</label>
                                <input type="date" name="date_acquisition" class="form-control"
                                       value="{{ old('date_acquisition', $mobilier->date_acquisition?->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Date de fin de durée de vie</label>
                                <input type="date" name="date_fin_vie" class="form-control"
                                       value="{{ old('date_fin_vie', $mobilier->date_fin_vie?->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Observations</label>
                                <textarea name="observations" class="form-control" rows="2">{{ old('observations', $mobilier->observations) }}</textarea>
                            </div>
                        </div>

                        <hr class="opacity-25">
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('mobiliers.show', $mobilier->id) }}" class="btn btn-link text-muted text-decoration-none">Annuler</a>
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
