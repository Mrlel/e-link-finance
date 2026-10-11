@extends('layouts.main-board')
@section('title', 'Patrimoine Divers')
@section('content')
@php
    $couleurHex = [
        'Noir' => '#212529', 'Cyan' => '#0dcaf0', 'Magenta' => '#d63384', 'Jaune' => '#ffc107',
        'Blanc' => '#ffffff', 'Rouge' => '#dc3545', 'Bleu' => '#0d6efd', 'Vert' => '#198754', 'Gris' => '#adb5bd',
    ];
@endphp

{{-- Select2 CSS --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        background-color: #f9fafb !important;
        border: 1px solid #dee2e6 !important;
        border-radius: 5px !important;
        height: 40px !important;
        padding: 5px 12px !important;
        display: flex; align-items: center;
        font-size: 15px;
    }
    .select2-selection__rendered { line-height: 28px !important; color: #374151 !important; }
    .select2-selection__arrow { height: 100% !important; right: 10px !important; }
    .select2-container { width: 100% !important; }
    .select2-results__option--highlighted { background: #3b82f6 !important; color: white !important; }
</style>

{{-- En-tête --}}
<div class="d-flex flex-column flex-md-row align-items-center justify-content-between py-4">
    <header>
        <h1 class="h3 fw-bold text-dark mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-box-seam"></i> Gestion du Patrimoine Divers
        </h1>
        <p class="text-muted mb-0">Fournitures & Consommables • Stock disponible</p>
    </header>
    <div class="d-flex align-items-center gap-2 mt-3 mt-md-0">
        <a href="{{ route('patrimoine-divers.create') }}" class="btn btn-success d-flex align-items-center gap-2 px-3 py-2">
            <i class="bi bi-plus-circle fs-5"></i> Ajouter un article
        </a>
        <a href="{{ route('patrimoine-divers.historique') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
            <i class="bi bi-clock-history fs-5"></i> Historique
        </a>
    </div>
</div>


{{-- Barre de filtre / recherche --}}
<form method="GET" action="{{ route('patrimoine-divers.index') }}" class="card shadow-sm mb-4">
    <div class="card-body py-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small text-muted mb-1">Recherche</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Nom de l'article…"
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1">Catégorie</label>
                <select name="categorie" class="form-select">
                    <option value="">Toutes</option>
                    @foreach(['fournitures' => 'Fournitures', 'consommables' => 'Consommables', 'autre' => 'Autre'] as $val => $label)
                        <option value="{{ $val }}" {{ request('categorie') == $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1">Couleur</label>
                <select name="couleur" class="form-select">
                    <option value="">Toutes</option>
                    @foreach(\App\Models\PatrimoineDivers::COULEURS as $c)
                        <option value="{{ $c }}" {{ request('couleur') == $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1">Statut</label>
                <select name="statut" class="form-select">
                    <option value="">Tous</option>
                    @foreach(['en stock' => 'En stock', 'partiellement assigné' => 'Part. assigné', 'épuisé' => 'Épuisé'] as $val => $label)
                        <option value="{{ $val }}" {{ request('statut') == $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1">État</label>
                <select name="etat" class="form-select">
                    <option value="">Tous</option>
                    @foreach(['neuf' => 'Neuf', 'bon' => 'Bon', 'use' => 'Usé'] as $val => $label)
                        <option value="{{ $val }}" {{ request('etat') == $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                <a href="{{ route('patrimoine-divers.index') }}" class="btn btn-outline-secondary w-100">Réinitialiser</a>
            </div>
        </div>
    </div>
</form>

{{-- Tableau --}}
@if($items->count() > 0)
<div class="table-responsive shadow-sm rounded">
    <table class="table table-striped table-hover align-middle mb-0">
        <thead class="table-dark">
            <tr>
                <th>Libellé</th>
                <th>Catégorie</th>
                <th>Couleur</th>
                <th>Quantité</th>
                <th>État</th>
                <th>Statut</th>
                <th>Date Acquisition</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item->libelle }}</td>
                <td>{{ ucfirst($item->categorie ?? '—') }}</td>
                <td>
                    @if($item->couleur)
                        <span class="d-inline-flex align-items-center gap-2">
                            <span class="rounded-circle border flex-shrink-0" style="width:12px; height:12px; background:{{ $couleurHex[$item->couleur] ?? '#dee2e6' }};"></span>
                            {{ $item->couleur }}
                        </span>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>
                    <span class="badge {{ $item->nombre <= 0 ? 'bg-danger' : ($item->nombre <= 5 ? 'bg-warning text-dark' : 'bg-success') }}">
                        {{ $item->nombre }}
                    </span>
                </td>
                <td>{{ ucfirst($item->etat) }}</td>
                <td>
                    <span class="badge {{ $item->statut === 'épuisé' ? 'bg-danger' : ($item->statut === 'partiellement assigné' ? 'bg-warning text-dark' : 'bg-success') }}">
                        {{ ucfirst($item->statut) }}
                    </span>
                </td>
                <td>{{ $item->date_acquisition ?? '—' }}</td>
                <td class="text-center">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('patrimoine-divers.edit', $item->id) }}">
                                    <i class="bi bi-pencil me-2 text-primary"></i> Modifier
                                </a>
                            </li>
                            @if($item->nombre > 0)
                            <li>
                                <button class="dropdown-item d-flex align-items-center gap-2"
                                        data-bs-toggle="modal" data-bs-target="#assignModal"
                                        data-item-id="{{ $item->id }}"
                                        data-item-libelle="{{ $item->libelle }}"
                                        data-item-stock="{{ $item->nombre }}">
                                    <i class="bi bi-person-check text-success"></i> Assigner
                                </button>
                            </li>
                            @endif
                            <li>
                                <button class="dropdown-item d-flex align-items-center gap-2"
                                        data-bs-toggle="modal" data-bs-target="#reapproModal"
                                        data-item-id="{{ $item->id }}"
                                        data-item-libelle="{{ $item->libelle }}">
                                    <i class="bi bi-arrow-repeat text-info"></i> Réapprovisionner
                                </button>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('patrimoine-divers.destroy', $item->id) }}" method="POST"
                                      onsubmit="return confirm('Supprimer cet article ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-trash me-2"></i> Supprimer
                                    </button>
                                </form>
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
<div class="text-center py-5">
    <i class="bi bi-inbox fs-1 text-muted"></i>
    <p class="text-muted mt-2">Aucun article trouvé</p>
    <a href="{{ route('patrimoine-divers.create') }}" class="btn btn-primary mt-2">Ajouter un article</a>
</div>
@endif

{{-- ===================== MODAL ASSIGNATION ===================== --}}
<div class="modal fade" id="assignModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-check me-2"></i>Assigner un article</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="assignForm" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <p class="text-muted mb-3">Article : <strong id="assignLibelle"></strong></p>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Utilisateur <span class="text-danger">*</span></label>
                        <select id="assignUserId" name="user_id" class="form-select" required>
                            <option value=""></option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->nom }} {{ $user->prenom }} — {{ $user->matricule }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Quantité <span class="text-danger">*</span></label>
                        <input type="number" name="quantite" id="assignQuantite" class="form-control"
                               value="1" min="1" required>
                        <div class="form-text">Stock disponible : <strong id="assignStock"></strong></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i> Confirmer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===================== MODAL RÉAPPROVISIONNEMENT ===================== --}}
<div class="modal fade" id="reapproModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-arrow-repeat me-2"></i>Réapprovisionner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="reapproForm" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <p class="text-muted mb-3">Article : <strong id="reapproLibelle"></strong></p>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Quantité à ajouter <span class="text-danger">*</span></label>
                        <input type="number" name="quantite_ajout" class="form-control" value="1" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Commentaire</label>
                        <input type="text" name="commentaire" class="form-control"
                               placeholder="Ex: Livraison du 17/03/2026…">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-info text-white">
                        <i class="bi bi-plus-circle me-1"></i> Ajouter au stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- jQuery + Select2 --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(function () {

    // ── Select2 sur le champ utilisateur du modal assignation ──
    $('#assignUserId').select2({
        dropdownParent: $('#assignModal'),
        placeholder: '— Rechercher un utilisateur —',
        allowClear: true,
    });

    // ── Modal Assignation : injecter les données de la ligne ──
    $('#assignModal').on('show.bs.modal', function (e) {
        const btn = e.relatedTarget;
        const id      = btn.getAttribute('data-item-id');
        const libelle = btn.getAttribute('data-item-libelle');
        const stock   = btn.getAttribute('data-item-stock');

        $('#assignLibelle').text(libelle);
        $('#assignStock').text(stock);
        $('#assignQuantite').attr('max', stock);
        $('#assignForm').attr('action', '/patrimoine-divers/' + id + '/assigner');

        // reset select2
        $('#assignUserId').val(null).trigger('change');
    });

    // ── Modal Réapprovisionnement : injecter les données ──
    $('#reapproModal').on('show.bs.modal', function (e) {
        const btn = e.relatedTarget;
        const id      = btn.getAttribute('data-item-id');
        const libelle = btn.getAttribute('data-item-libelle');

        $('#reapproLibelle').text(libelle);
        $('#reapproForm').attr('action', '/patrimoine-divers/' + id + '/reapprovisionner');
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

@endsection
