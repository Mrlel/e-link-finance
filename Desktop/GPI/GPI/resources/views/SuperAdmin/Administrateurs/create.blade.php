@extends('layouts.superDashboard')

@section('content')
<div class="py-4">
  <div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('directions.list-admin') }}" class="btn btn-sm btn-light border">
      <i class="fas fa-arrow-left me-1"></i> Retour
    </a>
    <div>
      <h1 class="fw-bold font-serif mb-0">Nouvel utilisateur</h1>
      <p class="small text-muted mb-0">Enregistrement d'un compte utilisateur</p>
    </div>
  </div>

  <div class="row justify-content-center">
    <div class="col-12 col-lg-8">
      <div class="card border-0 shadow-sm" style="border-radius:12px; overflow:hidden;">
        <!-- Bande tricolore -->
        <div class="d-flex" style="height:5px;">
          <div class="flex-fill" style="background:#FF7900;"></div>
          <div class="flex-fill bg-white"></div>
          <div class="flex-fill" style="background:#009E60;"></div>
        </div>

        <div class="card-body p-4">
          <h5 class="fw-semibold mb-4 text-success">
            <i class="fas fa-user-plus me-2"></i>Informations de l'utilisateur
          </h5>

          @if($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form action="{{ route('directions.store-admin') }}" method="POST">
            @csrf

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-medium">Nom <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nom') is-invalid @enderror"
                       name="nom" value="{{ old('nom') }}" placeholder="Nom de famille" required>
                @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label fw-medium">Prénom</label>
                <input type="text" class="form-control @error('prenom') is-invalid @enderror"
                       name="prenom" value="{{ old('prenom') }}" placeholder="Prénom">
                @error('prenom') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label fw-medium">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                       name="email" value="{{ old('email') }}" placeholder="exemple@email.com" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label fw-medium">Matricule <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('matricule') is-invalid @enderror"
                       name="matricule" value="{{ old('matricule') }}" placeholder="N° matricule" required>
                @error('matricule') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label fw-medium">Contact</label>
                <input type="tel" class="form-control @error('contact') is-invalid @enderror"
                       name="contact" value="{{ old('contact') }}" placeholder="+225 XX XX XX XX">
                @error('contact') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label fw-medium">Emploi</label>
                <input type="text" class="form-control @error('emploie') is-invalid @enderror"
                       name="emploie" value="{{ old('emploie') }}" placeholder="Poste occupé">
                @error('emploie') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label fw-medium">Grade</label>
                <input type="text" class="form-control @error('grade') is-invalid @enderror"
                       name="grade" value="{{ old('grade') }}" placeholder="Grade">
                @error('grade') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label fw-medium">Rôle <span class="text-danger">*</span></label>
                <select name="role" class="form-select @error('role') is-invalid @enderror">
                  <option value="user"              {{ old('role') == 'user'              ? 'selected' : '' }}>Agent</option>
                  <option value="chef_de_service"   {{ old('role') == 'chef_de_service'   ? 'selected' : '' }}>Chef de service</option>
                  <option value="sous_directeur"    {{ old('role') == 'sous_directeur'    ? 'selected' : '' }}>Sous-directeur</option>
                  <option value="directeur"         {{ old('role') == 'directeur'         ? 'selected' : '' }}>Directeur</option>
                  <option value="gestionnaire_parc" {{ old('role') == 'gestionnaire_parc' ? 'selected' : '' }}>Gestionnaire de parc</option>
                  <option value="technicien"        {{ old('role') == 'technicien'        ? 'selected' : '' }}>Technicien Maintenance</option>
                  <option value="admin"             {{ old('role') == 'admin'             ? 'selected' : '' }}>Administrateur</option>
                  <option value="point_focal"       {{ old('role') == 'point_focal'       ? 'selected' : '' }}>Point focal</option>
                  <option value="service_gestionnaire" {{ old('role') == 'service_gestionnaire' ? 'selected' : '' }}>Service gestionnaire</option>
                </select>
                @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="col-12">
                <label class="form-label fw-medium">Direction <span class="text-danger">*</span></label>
                <select name="direction_id" class="form-select @error('direction_id') is-invalid @enderror" required>
                  <option value="">-- Sélectionner une direction --</option>
                  @foreach($directions as $direction)
                    <option value="{{ $direction->id }}" {{ old('direction_id') == $direction->id ? 'selected' : '' }}>
                      {{ $direction->nom_direction }}
                    </option>
                  @endforeach
                </select>
                @error('direction_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            <div class="alert alert-info mt-3 mb-0 py-2 small">
              <i class="fas fa-info-circle me-1"></i>
              Le mot de passe par défaut sera <strong>12345678</strong>. L'utilisateur devra le changer à sa première connexion.
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
              <a href="{{ route('directions.list-admin') }}" class="btn btn-secondary">Annuler</a>
              <button type="submit" class="btn btn-success">
                <i class="fas fa-save me-1"></i> Enregistrer
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
