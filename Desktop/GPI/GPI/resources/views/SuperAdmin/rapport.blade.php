@extends('layouts.superDashboard')

@section('content')
<div class="py-4">
  <div class="mb-4">
    <h1 class="fw-bold font-serif mb-1">Rapport de gestion du patrimoine</h1>
    <p class="small text-muted mb-0">Structure organisationnelle du ministère</p>
  </div>

  <!-- Filtre -->
  <form method="GET" action="{{ url()->current() }}" class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
      <div class="row g-2 align-items-end">
        <div class="col-md-6">
          <label class="form-label small text-muted mb-1">Direction</label>
          <select name="direction_id" class="form-select">
            <option value="">Toutes les directions</option>
            @foreach($directions as $direction)
              <option value="{{ $direction->id }}" @selected(request('direction_id') == $direction->id)>{{ $direction->nom_direction }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
          <button type="submit" class="btn btn-outline-primary flex-fill"><i class="fas fa-filter"></i> Filtrer</button>
          @if(request()->filled('direction_id'))
            <a href="{{ url()->current() }}" class="btn btn-outline-secondary">Réinit.</a>
          @endif
        </div>
        @if(request()->filled('direction_id'))
        <div class="col-md-3">
          <a href="{{ route('rapport.pdf.direction', ['directionId' => request('direction_id')]) }}" class="btn btn-success w-100">
            <i class="fas fa-download"></i> Télécharger PDF
          </a>
        </div>
        @endif
      </div>
    </div>
  </form>

  <!-- Stats par direction -->
  @forelse($stats as $stat)
    <div class="card border-0 shadow-sm mb-3">
      <div class="card-header bg-success text-white fw-semibold" style="border-radius:12px 12px 0 0;">
        <i class="fas fa-building me-2"></i>{{ $stat['direction'] }}
      </div>
      <div class="card-body">
        <div class="row g-4">
          <div class="col-12 col-lg-4">
            <h6 class="text-muted small text-uppercase fw-bold mb-2">Équipements</h6>
            <div class="table-responsive">
              <table class="table table-sm table-bordered text-center mb-0">
                <thead class="table-light"><tr><th>Total</th><th>Stock</th><th>Service</th><th>Maint.</th><th>H.S.</th></tr></thead>
                <tbody>
                  <tr>
                    <td>{{ $stat['equipements']['total'] }}</td>
                    <td>{{ $stat['equipements']['en_stock'] }}</td>
                    <td>{{ $stat['equipements']['en_service'] }}</td>
                    <td>{{ $stat['equipements']['en_maintenance'] }}</td>
                    <td>{{ $stat['equipements']['hors_service'] }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="col-12 col-lg-4">
            <h6 class="text-muted small text-uppercase fw-bold mb-2">Utilisateurs</h6>
            <div class="table-responsive">
              <table class="table table-sm table-bordered text-center mb-0">
                <thead class="table-light"><tr><th>Total</th><th>Équipés</th><th>Non éq.</th><th>Connectés</th></tr></thead>
                <tbody>
                  <tr>
                    <td>{{ $stat['utilisateurs']['total'] }}</td>
                    <td>{{ $stat['utilisateurs']['equipes'] }}</td>
                    <td>{{ $stat['utilisateurs']['non_equipes'] }}</td>
                    <td>{{ $stat['utilisateurs']['connectes'] }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="col-12 col-lg-4">
            <h6 class="text-muted small text-uppercase fw-bold mb-2">Logiciels</h6>
            <div class="table-responsive">
              <table class="table table-sm table-bordered text-center mb-0">
                <thead class="table-light"><tr><th>Total</th><th>Actifs</th><th>Bientôt exp.</th><th>Expirés</th></tr></thead>
                <tbody>
                  <tr>
                    <td>{{ $stat['logiciels']['total'] }}</td>
                    <td>{{ $stat['logiciels']['actifs'] }}</td>
                    <td>{{ $stat['logiciels']['bientot_expires'] }}</td>
                    <td>{{ $stat['logiciels']['expires'] }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  @empty
    <div class="text-center py-5 text-muted bg-white rounded-3 shadow-sm">
      <i class="fas fa-chart-bar fa-3x mb-3 opacity-25"></i>
      <p class="mb-0">Aucune donnée à afficher.</p>
    </div>
  @endforelse
</div>
@endsection
