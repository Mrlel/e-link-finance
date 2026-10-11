@extends('layouts.main-board')
@section('title', 'Gestion des materiels en maintenance')
@section('content')
@include('layouts.materiel-stat-card')

@if($materielsEnMaintenance->count() > 0)
<div class="table-responsive shadow-sm rounded">
  <table class="table table-striped table-hover align-middle mb-0">
    <thead class="table-dark">
      <tr>
        <th>Designation</th>
        <th>Catégorie</th>
        <th>Marque</th>
        <th>Num série</th>
        <th>État</th>
        <th>Date Acquisition</th>
        <th class="text-center">Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($materielsEnMaintenance as $materiels)
      <tr>
        <td>{{ $materiels->des_equipement }}</td>
        <td>{{ $materiels->categorie }}</td>
        <td>{{ $materiels->marque }}</td>
        <td>{{ $materiels->numero_serie }}</td>
        <td>{{ $materiels->etat }}</td>
        <td>{{ $materiels->date_acquis ?? 'Non spécifié' }}</td>
        <td class="text-center">
          <div class="dropdown">
            <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-three-dots-vertical"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <a class="dropdown-item" href="/update_equipemnt/{{ $materiels->id }}">
                  <i class="bi bi-pencil me-2 text-primary"></i> Modifier
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="/equipement_details/{{ $materiels->id }}">
                  <i class="bi bi-info-circle me-2 text-warning"></i> Détails
                </a>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <a class="dropdown-item text-danger"
                   href="/delete_ordinateur/{{ $materiels->id }}"
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
<div class="text-center py-3 mt-4">
  <i class="bi bi-inbox fs-1 text-muted mb-3"></i>
  <p class="text-muted">Aucun matériel en maintenance</p>
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