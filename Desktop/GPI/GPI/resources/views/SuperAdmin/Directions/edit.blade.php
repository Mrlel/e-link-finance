@extends('layouts.superDashboard')

@section('content')
<div class="py-4">
  <div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('directions.list-direction') }}" class="btn btn-sm btn-light border">
      <i class="fas fa-arrow-left me-1"></i> Retour
    </a>
    <div>
      <h1 class="fw-bold font-serif mb-0">Modifier la direction</h1>
      <p class="small text-muted mb-0">{{ $direction->nom_direction }}</p>
    </div>
  </div>

  <div class="row justify-content-center">
    <div class="col-12 col-lg-8">
      <div class="card border-0 shadow-sm" style="border-radius:12px; overflow:hidden;">
        <div class="d-flex" style="height:5px;">
          <div class="flex-fill" style="background:#FF7900;"></div>
          <div class="flex-fill bg-white"></div>
          <div class="flex-fill" style="background:#009E60;"></div>
        </div>

        <div class="card-body p-4">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-semibold mb-0 text-success">
              <i class="fas fa-building me-2"></i>Informations de la direction
            </h5>
            @if($direction->statut === 'active')
              <span class="badge bg-success">Active</span>
            @else
              <span class="badge bg-secondary">Inactive</span>
            @endif
          </div>

          @if($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form action="{{ route('directions.update', $direction->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-medium">Nom de la direction <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nom_direction') is-invalid @enderror"
                       name="nom_direction" value="{{ old('nom_direction', $direction->nom_direction) }}" required>
                @error('nom_direction') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label fw-medium">Code / Sigle <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('code_direction') is-invalid @enderror"
                       name="code_direction" value="{{ old('code_direction', $direction->code_direction) }}" required>
                @error('code_direction') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label fw-medium">Type</label>
                <select name="type" class="form-select">
                  <option value="">— Sélectionner —</option>
                  <option value="centrale"     {{ old('type', $direction->type) == 'centrale'     ? 'selected' : '' }}>Centrale</option>
                  <option value="déconcentrée" {{ old('type', $direction->type) == 'déconcentrée' ? 'selected' : '' }}>Déconcentrée</option>
                  <option value="partenaire"   {{ old('type', $direction->type) == 'partenaire'   ? 'selected' : '' }}>Partenaire</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-medium">Statut</label>
                <select name="statut" class="form-select">
                  <option value="active"   {{ old('statut', $direction->statut) == 'active'   ? 'selected' : '' }}>Active</option>
                  <option value="inactive" {{ old('statut', $direction->statut) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label fw-medium">Email de contact</label>
                <input type="email" class="form-control @error('email_contact') is-invalid @enderror"
                       name="email_contact" value="{{ old('email_contact', $direction->email_contact) }}">
                @error('email_contact') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label fw-medium">Téléphone</label>
                <input type="tel" class="form-control @error('telephone') is-invalid @enderror"
                       name="telephone" value="{{ old('telephone', $direction->telephone) }}">
                @error('telephone') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label fw-medium">Responsable</label>
                <input type="text" class="form-control @error('responsable') is-invalid @enderror"
                       name="responsable" value="{{ old('responsable', $direction->responsable) }}">
                @error('responsable') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label fw-medium">Adresse</label>
                <input type="text" class="form-control @error('adresse') is-invalid @enderror"
                       name="adresse" value="{{ old('adresse', $direction->adresse) }}">
                @error('adresse') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label fw-medium">Site web</label>
                <input type="url" class="form-control @error('web_url') is-invalid @enderror"
                       name="web_url" value="{{ old('web_url', $direction->web_url) }}">
                @error('web_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label fw-medium">Logo</label>
                <input type="file" class="form-control @error('logo') is-invalid @enderror"
                       name="logo" accept="image/*">
                @if($direction->logo)
                  <div class="mt-2 d-flex align-items-center gap-2">
                    <img src="{{ asset('storage/' . $direction->logo) }}" alt="Logo actuel"
                         class="rounded border" style="height:40px; width:auto;">
                    <span class="text-muted small">Logo actuel</span>
                  </div>
                @endif
                @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
              <a href="{{ route('directions.list-direction') }}" class="btn btn-secondary">Annuler</a>
              <button type="submit" class="btn btn-success">
                <i class="fas fa-save me-1"></i> Enregistrer les modifications
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
