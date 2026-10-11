@extends('layouts.main-board')
@section('title', 'Nouvelle sortie véhicule')
@section('content')

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('vehicules.index') }}" class="text-decoration-none">Parc Automobile</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vehicules.show', $vehicule->id) }}" class="text-decoration-none">{{ $vehicule->immatriculation }}</a></li>
                    <li class="breadcrumb-item active">Nouvelle sortie</li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold d-flex align-items-center gap-2">
                <i class="bi bi-box-arrow-right text-danger"></i> Enregistrer une sortie
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            @if($errors->any())
                <div class="alert alert-danger border-0 shadow-sm mb-4">
                    <ul class="mb-0 small">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            {{-- Récap véhicule --}}
            <div class="card border-0 bg-light mb-4">
                <div class="card-body py-3 d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-dark d-flex align-items-center justify-content-center"
                         style="width:44px;height:44px;">
                        <i class="bi bi-car-front-fill text-white fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-semibold">{{ $vehicule->immatriculation }}</div>
                        <div class="small text-muted">
                            {{ $vehicule->marque }} {{ $vehicule->modele }} •
                            <span class="badge bg-secondary">{{ $vehicule->statut }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('sorties-vehicules.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="vehicule_id" value="{{ $vehicule->id }}">

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Type de sortie <span class="text-danger">*</span></label>
                            <select name="type_sortie" id="typeSortie"
                                    class="form-select @error('type_sortie') is-invalid @enderror" required>
                                <option value="" disabled selected>— Choisir —</option>
                                <option value="maintenance_externe" {{ old('type_sortie') == 'maintenance_externe' ? 'selected' : '' }}>
                                    Maintenance externe (retour prévu)
                                </option>
                                <option value="reforme" {{ old('type_sortie') == 'reforme' ? 'selected' : '' }}>
                                    Réforme
                                </option>
                                <option value="enlevement" {{ old('type_sortie') == 'enlevement' ? 'selected' : '' }}>
                                    Enlèvement définitif
                                </option>
                                <option value="transfert" {{ old('type_sortie') == 'transfert' ? 'selected' : '' }}>
                                    Transfert
                                </option>
                            </select>
                            @error('type_sortie')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Motif <span class="text-danger">*</span></label>
                            <textarea name="motif" rows="3"
                                      class="form-control @error('motif') is-invalid @enderror"
                                      placeholder="Décrivez la raison de la sortie…" required>{{ old('motif') }}</textarea>
                            @error('motif')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Date de sortie <span class="text-danger">*</span></label>
                                <input type="date" name="date_sortie"
                                       class="form-control @error('date_sortie') is-invalid @enderror"
                                       value="{{ old('date_sortie', now()->format('Y-m-d')) }}" required>
                                @error('date_sortie')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6" id="retourPrevuBlock">
                                <label class="form-label fw-semibold">Date de retour prévue</label>
                                <input type="date" name="date_retour_prevue" class="form-control"
                                       value="{{ old('date_retour_prevue') }}">
                                <div class="form-text">Pour maintenance externe uniquement</div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Prestataire / Technicien</label>
                                <input type="text" name="prestataire" class="form-control"
                                       value="{{ old('prestataire') }}"
                                       placeholder="Nom de la société ou du technicien">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Lieu / Destination</label>
                                <input type="text" name="lieu_destination" class="form-control"
                                       value="{{ old('lieu_destination') }}"
                                       placeholder="Adresse ou direction destinataire">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Observations</label>
                            <textarea name="observations" rows="2" class="form-control"
                                      placeholder="Informations complémentaires…">{{ old('observations') }}</textarea>
                        </div>

                        <hr class="opacity-25">
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('vehicules.show', $vehicule->id) }}"
                               class="btn btn-link text-muted text-decoration-none">Annuler</a>
                            <button type="submit" class="btn btn-danger px-4">
                                <i class="bi bi-box-arrow-right me-2"></i> Enregistrer la sortie
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5 d-none d-lg-block">
            <div class="card border-0 border-start border-4 border-warning shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold text-warning"><i class="bi bi-exclamation-triangle me-2"></i>Important</h6>
                    <ul class="small mb-0 ps-3">
                        <li class="mb-2"><strong>Maintenance externe</strong> : le véhicule passe en statut "en maintenance". Un retour sera attendu.</li>
                        <li class="mb-2"><strong>Réforme</strong> : le véhicule passe en "hors service".</li>
                        <li class="mb-2"><strong>Enlèvement</strong> : le véhicule est définitivement retiré du parc actif et marqué "enlevé".</li>
                        <li><strong>Transfert</strong> : le véhicule quitte temporairement la direction.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const select = document.getElementById('typeSortie');
    const retourBlock = document.getElementById('retourPrevuBlock');
    function toggle() {
        const show = select.value === 'maintenance_externe';
        retourBlock.style.opacity = show ? '1' : '0.4';
        retourBlock.querySelector('input').disabled = !show;
    }
    select.addEventListener('change', toggle);
    toggle();
})();
</script>

@endsection
