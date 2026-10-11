@extends('layouts.main-board')
@section('title', 'Gestion des materiels en stock')
@section('content')

{{-- Select2 CSS --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    .select2-container--default .select2-selection--single {
        background-color: #f9fafb !important;
        border: 1px solid #dee2e6 !important;
        border-radius: 5px!important;
        height: 40px !important;
        padding: 10px 18px !important;
        display: flex;
        align-items: center;
        font-size: 15px;
        transition: all .2s ease-in-out;
    }
    .select2-container--default .select2-selection--single:hover {
        border-color: #3b82f6 !important;
        box-shadow: 0 4px 10px rgba(59,130,246,0.15);
    }
    .select2-selection__rendered { line-height: 30px !important; color: #374151 !important; font-weight: 500; }
    .select2-selection__arrow { height: 100% !important; right: 15px !important; }
    .select2-container--default .select2-search--dropdown .select2-search__field {
        padding: 10px; border-radius: 5px; border: 1px solid #d1d5db;
    }
    .select2-container--default .select2-results > .select2-results__options { max-height: 220px !important; padding: 5px; }
    .select2-results__option { padding: 10px 12px; border-radius: 8px; margin-bottom: 4px; font-size: 14px; transition: background .15s ease; }
    .select2-results__option--highlighted { background: #3b82f6 !important; color: white !important; }
    .select2-container { width: 100% !important; }
    /* Fin de vie inline compact */
    .fin-vie-badge { display:inline-flex; align-items:center; gap:4px; font-size:0.78rem; padding:2px 7px; border-radius:20px; font-weight:600; white-space:nowrap; }
    .fin-vie-ok      { background:#d1fae5; color:#065f46; }
    .fin-vie-soon    { background:#fef3c7; color:#92400e; }
    .fin-vie-expired { background:#fee2e2; color:#991b1b; }
</style>

@include('layouts.materiel-stat-card')

<form method="GET" action="{{ url()->current() }}" class="card shadow-sm mb-4">
    <div class="card-body py-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small text-muted mb-1">Recherche</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                           placeholder="Désignation, marque, n° série, catégorie…">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Catégorie</label>
                <select name="categorie" class="form-select">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" @selected(request('categorie') === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">État</label>
                <select name="etat" class="form-select">
                    <option value="">Tous les états</option>
                    @foreach($etats as $et)
                        <option value="{{ $et }}" @selected(request('etat') === $et)>{{ ucfirst($et) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-outline-primary flex-fill"><i class="bi bi-search"></i> Filtrer</button>
                @if(request()->filled('q') || request()->filled('categorie') || request()->filled('etat'))
                    <a href="{{ url()->current() }}" class="btn btn-outline-secondary">Réinitialiser</a>
                @endif
            </div>
        </div>
    </div>
</form>

@if($materielsEnStockPage->total() > 0)
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
      @foreach ($materielsEnStockPage as $materiel)
      @php
          $joursRestants = $materiel->date_fin_vie ? now()->diffInDays($materiel->date_fin_vie, false) : null;
          $rowClass = '';
          if ($joursRestants !== null) {
              if ($joursRestants < 0)       $rowClass = 'table-danger';
              elseif ($joursRestants <= 30) $rowClass = 'table-warning';
          }
          $hasSortie = $materiel->sortieActive !== null;
      @endphp
      <tr class="{{ $rowClass }}">
        <td>
            {{ $materiel->des_equipement }}
            @if($hasSortie)
                <span class="badge bg-danger ms-1" title="Sortie en cours : {{ $materiel->sortieActive->type_label }}">
                    <i class="bi bi-box-arrow-right me-1"></i>{{ $materiel->sortieActive->type_label }}
                </span>
            @endif
        </td>
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
            <button class="btn btn-sm btn-light dropdown-toggle"
                    type="button"
                    id="dropdownMenuButtonMateriel{{ $materiel->id }}"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">
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
              <li>
                <button class="dropdown-item d-flex align-items-center py-2 px-3 rounded-2"
                        data-bs-toggle="modal"
                        data-bs-target="#assignEquipementModal"
                        data-equipement-id="{{ $materiel->id }}">
                  <i class="bi bi-person-workspace me-2"></i> Assigner cet équipement
                </button>
              </li>
              <li><hr class="dropdown-divider"></li>
              @if(!$hasSortie)
              <li>
                <a href="{{ route('sorties-equipements.create', $materiel->id) }}"
                   class="dropdown-item text-danger">
                  <i class="bi bi-box-arrow-right me-1"></i> Enregistrer une sortie
                </a>
              </li>
              @else
              <li>
                <a href="{{ route('sorties-equipements.show', $materiel->sortieActive->id) }}"
                   class="dropdown-item text-warning">
                  <i class="bi bi-eye me-1"></i> Voir la sortie en cours
                </a>
              </li>
              @endif
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
<div class="d-flex justify-content-between align-items-center mt-3">
    <p class="small text-muted mb-0">
        {{ $materielsEnStockPage->firstItem() }}–{{ $materielsEnStockPage->lastItem() }} sur {{ $materielsEnStockPage->total() }} matériel(s)
    </p>
    {{ $materielsEnStockPage->links('pagination::bootstrap-5') }}
</div>
@else
<div class="text-center py-4 mt-4">
  <i class="bi bi-inbox fs-1 text-muted mb-3"></i>
  <p class="text-muted">Aucun matériel en stock</p>
</div>
@endif


{{-- jQuery + Select2 --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {

    /* -------------------------
       1️⃣ Modal Assign → récup ID
    ------------------------- */
    const assignModal = document.getElementById('assignEquipementModal');

    assignModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const equipementId = button.getAttribute('data-equipement-id');
        assignModal.querySelector('#equipement_id').value = equipementId;
    });


    /* -------------------------
       2️⃣ Select2 AJAX
    ------------------------- */
    $('#user_id').select2({
        dropdownParent: $('#assignEquipementModal'),
        placeholder: "-- Choisissez un utilisateur --",
        allowClear: true,
        ajax: {
            url: "{{ route('users.search') }}",  // 🔥 route Laravel pour AJAX
            dataType: 'json',
            delay: 250,
            data: params => ({ q: params.term }),
            processResults: function (data) {
                return {
                    results: data.map(user => ({
                        id: user.id,
                        text: `${user.nom} ${user.prenom} - ${user.matricule}`
                    }))
                };
            }
        }
    });

    // .table-responsive a overflow-x:auto, ce qui force le navigateur à clipper aussi l'axe Y
    // (règle CSS : un seul axe "visible" avec l'autre "auto" fait passer les deux en "auto").
    // Le menu du bouton "..." se retrouvait donc coincé dans le conteneur (scroll au lieu du
    // survol). En stratégie "fixed", Popper positionne le menu par rapport à la fenêtre.
    document.querySelectorAll('.table-responsive [data-bs-toggle="dropdown"]').forEach(function (el) {
        new bootstrap.Dropdown(el, { popperConfig: { strategy: 'fixed' } });
    });

});
</script>

{{-- =====================
      MODAL ASSIGNATION
====================== --}}
<div class="modal fade" id="assignEquipementModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content mt-5">
            <div class="modal-header">
                <h5 class="modal-title">Attribuer un Matériel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form method="POST" action="{{ route('assign.equipement') }}">
                    @csrf
                    <input type="hidden" name="equipement_id" id="equipement_id">

                    <div class="mb-3">
                        <select class="form-select" id="user_id" name="user_id" required></select>
                    </div>

                    <button type="submit" class="fluid ui medium primary button" style="margin-top: 20px; text-align: center;">
                        Attribuer <span uk-icon="check"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
