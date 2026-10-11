@extends('layouts.superDashboard')

@section('content')
<div class="py-4">
  <div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('directions.list-direction') }}" class="btn btn-sm btn-light border">
      <i class="fas fa-arrow-left me-1"></i> Retour
    </a>
    <div>
      <h1 class="fw-bold font-serif mb-0">Assigner un administrateur</h1>
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
          <h5 class="fw-semibold mb-4 text-success">
            <i class="fas fa-user-plus me-2"></i>Choisir un administrateur
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

          @if($admins->isEmpty())
            <div class="text-center py-4 text-muted">
              <i class="fas fa-user-slash fa-2x mb-3 opacity-25"></i>
              <p class="mb-0">Aucun administrateur disponible — tous les comptes « Administrateur » sont déjà rattachés à une direction.</p>
            </div>
          @else
            <form action="{{ route('directions.store-assign-admin', $direction->id) }}" method="POST">
              @csrf
              <div class="mb-3">
                <label for="admin_id" class="form-label fw-medium">Administrateur <span class="text-danger">*</span></label>
                <select name="admin_id" id="admin_id" class="form-select @error('admin_id') is-invalid @enderror" required>
                  <option value="">-- Sélectionner un administrateur --</option>
                  @foreach($admins as $admin)
                    <option value="{{ $admin->id }}">{{ $admin->nom }} {{ $admin->prenom }} ({{ $admin->email }})</option>
                  @endforeach
                </select>
                @error('admin_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <div class="form-text">Seuls les comptes avec le rôle « Administrateur » et sans direction déjà assignée apparaissent ici.</div>
              </div>

              <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                <a href="{{ route('directions.list-direction') }}" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-success">
                  <i class="fas fa-check me-1"></i> Assigner
                </button>
              </div>
            </form>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
