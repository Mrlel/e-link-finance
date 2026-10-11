@extends('layouts.main-board')
@section('title', 'Gestion des materiels en service')
@section('content')

@include('layouts.materiel-stat-card')

<style>
      /* Fin de vie inline compact */
    .fin-vie-badge { display:inline-flex; align-items:center; gap:4px; font-size:0.78rem; padding:2px 7px; border-radius:20px; font-weight:600; white-space:nowrap; }
    .fin-vie-ok      { background:#d1fae5; color:#065f46; }
    .fin-vie-soon    { background:#fef3c7; color:#92400e; }
    .fin-vie-expired { background:#fee2e2; color:#991b1b; }
</style>

@if($materielsEnService->count() > 0)
<div class="table-responsive shadow-sm rounded">
  <table class="table table-striped table-hover align-middle mb-0">
    <thead class="table-dark">
      <tr>
        <th>Designation</th>
        <th>Catégorie</th>
        <th>Marque</th>
        <th>N° Série</th>
        <th>État</th>
        <th>Fin de vie</th>
        <th class="text-center">Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($materielsEnService as $materiel)
          @php
          $joursRestants = $materiel->date_fin_vie ? now()->diffInDays($materiel->date_fin_vie, false) : null;
          $rowClass = '';
          if ($joursRestants !== null) {
              if ($joursRestants < 0)       $rowClass = 'table-danger';
              elseif ($joursRestants <= 30) $rowClass = 'table-warning';
          }
          $hasSortie = $materiel->sortieActive !== null;
      @endphp
      <tr>
        <td>{{ $materiel->des_equipement }}</td>
        <td>{{ $materiel->categorie }}</td>
        <td>{{ $materiel->marque }}</td>
        <td>{{ $materiel->numero_serie }}</td>
        <td>{{ $materiel->etat }}</td>
        <td>
            @if($joursRestants !== null)
                @if($joursRestants < 0)
                    <span class="fin-vie-badge fin-vie-expired">
                        <i class="bi bi-x-circle-fill"></i>
                        Dépassée ({{ abs($joursRestants) }}j)
                    </span>
                @elseif($joursRestants <= 30)
                    <span class="fin-vie-badge fin-vie-soon">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        {{ $joursRestants }}j restants
                    </span>
                @else
                    <span class="fin-vie-badge fin-vie-ok">
                        <i class="bi bi-check-circle-fill"></i>
                        {{ $materiel->date_fin_vie->format('d/m/Y') }}
                    </span>
                @endif
            @else
                <span class="text-muted small">—</span>
            @endif
        </td>
        <td class="text-center">
          <div class="dropdown">
            <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-three-dots-vertical"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <a class="dropdown-item" href="/update_equipemnt/{{ $materiel->id }}">
                  <i class="bi bi-pencil me-2 text-primary"></i> Modifier
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="/equipement_details/{{ $materiel->id }}">
                  <i class="bi bi-info-circle me-2 text-warning"></i> Détails
                </a>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <a class="dropdown-item text-danger"
                   href="/delete_ordinateur/{{ $materiel->id }}"
                   onclick="return confirm('Voulez-vous vraiment supprimer ce matériel ?')">
                  <i class="bi bi-trash me-2"></i> Supprimer
                </a>
              </li>
            </ul>
          </div>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@else
<div class="text-center py-4 mt-4">
  <i class="bi bi-inbox fs-1 text-muted mb-3"></i>
  <p class="text-muted">Aucun matériel en service</p>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    // .table-responsive a overflow-x:auto, ce qui force le navigateur à clipper aussi l'axe Y
    // (règle CSS : un seul axe "visible" avec l'autre "auto" fait passer les deux en "auto").
    // Le menu du bouton "..." se retrouvait donc coincé dans le conteneur (scroll au lieu du
    // survol). En stratégie "fixed", Popper positionne le menu par rapport à la fenêtre.
    document.querySelectorAll('.table-responsive [data-bs-toggle="dropdown"]').forEach(function (el) {
        new bootstrap.Dropdown(el, { popperConfig: { strategy: 'fixed' } });
    });
});
</script>

@endsection