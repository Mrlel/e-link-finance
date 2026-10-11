@extends('layouts.main-board')
@section('title', 'Détails véhicule')
@section('content')

@php
    $statutColor = match($vehicule->statut) {
        'affecté'        => 'bg-primary',
        'en maintenance' => 'bg-warning text-dark',
        'hors service'   => 'bg-danger',
        default          => 'bg-success',
    };
    $etatColor = match($vehicule->etat) {
        'NEUF'         => 'bg-success',
        'BON'          => 'bg-primary',
        'MOYEN'        => 'bg-warning text-dark',
        'MAUVAIS'      => 'bg-orange text-white',
        'HORS SERVICE' => 'bg-danger',
        default        => 'bg-secondary',
    };
    $active    = $vehicule->assignments->whereNull('returned_at');
    $historique = $vehicule->assignments->whereNotNull('returned_at');
@endphp

<style>
    :root {
        --civ-orange: #FF8200;
        --civ-green: #009E60;
        --civ-white: #FFFFFF;
    }
        .select2-container--default .select2-selection--single {
        background-color: #f9fafb !important; border: 1px solid #dee2e6 !important;
        border-radius: 5px !important; height: 40px !important; padding: 5px 12px !important;
        display: flex; align-items: center; font-size: 15px;
    }
    .select2-selection__rendered { line-height: 28px !important; color: #374151 !important; }
    .select2-selection__arrow { height: 100% !important; right: 10px !important; }
    .select2-container { width: 100% !important; }
    .select2-results__option--highlighted { background: #198754 !important; color: white !important; }
    /* Accents Ivoiriens */
    .btn-civ-orange { background-color: var(--civ-orange); color: white; border: none; transition: 0.3s; }
    .btn-civ-orange:hover { background-color: #e67600; color: white; box-shadow: 0 4px 12px rgba(255, 130, 0, 0.3); }
    
    .btn-civ-green { background-color: var(--civ-green); color: white; border: none; }
    .btn-civ-green:hover { background-color: #008551; color: white; }


    
    .text-civ-green { color: var(--civ-green) !important; }
    .text-civ-orange { color: var(--civ-orange) !important; }

    /* Design des cartes */
    .info-label { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.8px; color: #888; font-weight: 700; }
    .info-value { font-size: 1.05rem; color: #2d3436; font-weight: 500; }
    .card { border: none; border-radius: 15px; overflow: hidden; }
    .badge-pill-custom { padding: 0.6em 1.2em; border-radius: 50rem; font-weight: 600; font-size: 0.85rem; }
    
    /* Header background subtle gradient */
    .header-civ {
        background: linear-gradient(90deg, rgba(255,130,0,0.05) 0%, rgba(255,255,255,1) 50%, rgba(0,158,96,0.05) 100%);
        border-radius: 15px;
        padding: 20px;
    }
</style>

{{-- En-tête avec dégradé subtil --}}
<div class="bg-light p-4 border d-flex flex-column flex-md-row align-items-md-center justify-content-between my-4 shadow-sm">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('vehicules.index') }}" class="text-civ-green fw-bold text-decoration-none">Gestion Parc</a></li>
                <li class="breadcrumb-item active text-civ-orange">{{ $vehicule->immatriculation }}</li>
            </ol>
        </nav>
        <h1 class="h2 fw-bold text-dark mb-0">
            <i class="bi bi-car-front-fill text-civ-green me-2"></i>{{ $vehicule->immatriculation }}
        </h1>
        <div class="mt-1">
            <span class="badge bg-light text-dark border">{{ $vehicule->marque }}</span>
            <span class="badge bg-light text-dark border">{{ $vehicule->modele }}</span>
        </div>
    </div>
    <div class="d-flex gap-2 mt-3 mt-md-0">
        <a href="{{ route('vehicules.index') }}" class="btn btn-outline-secondary border-0">
            <i class="bi bi-chevron-left"></i> Retour
        </a>
        <a href="{{ route('vehicules.edit', $vehicule->id) }}" class="btn btn-light shadow-sm">
            <i class="bi bi-pencil text-civ-orange me-1"></i> Modifier
        </a>
        @if($vehicule->statut === 'disponible')
        <button class="btn btn-civ-green px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#affecterModal">
            <i class="bi bi-person-plus-fill me-1"></i> Affecter le véhicule
        </button>
        @endif
    </div>
</div>


<div class="row g-4 mb-5">
    {{-- Fiche technique --}}
    <div class="col-lg-5">
        <div class="card border h-100">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="mb-0 fw-bold text-civ-orange"><i class="bi bi-clipboard-data me-2"></i>Détails du véhicule</h5>
            </div>
            <div class="card-body p-4 pt-0">
                <div class="row g-4">
                    <div class="col-6">
                        <div class="info-label">Catégorie</div>
                        <div class="info-value"><i class="bi {{ $vehicule->categorie === 'moto' ? 'bi-bicycle' : 'bi-car-front' }} text-civ-green me-1"></i> {{ ucfirst($vehicule->categorie) }}</div>
                    </div>
                    <div class="col-6">
                        <div class="info-label">Couleur</div>
                        <div class="info-value">{{ $vehicule->couleur ?? '—' }}</div>
                    </div>
                    <div class="col-12 border-top pt-3">
                        <div class="info-label">N° de Châssis</div>
                        <div class="info-value font-monospace text-uppercase">{{ $vehicule->numero_chassis ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="info-label">Mise en circulation</div>
                        <div class="info-value">{{ $vehicule->date_mec?->format('d/m/Y') ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="info-label">Mode Acquisition</div>
                        <div class="info-value">{{ $vehicule->mode_acquisition }}</div>
                    </div>
                    <div class="col-12 border-top pt-3">
                        <div class="info-label">Lieu d'utilisation</div>
                        <div class="info-value"><i class="bi bi-geo-alt text-danger"></i> {{ $vehicule->lieu_utilisation ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="info-label mb-2">État</div>
                        <span class="badge badge-pill-custom {{ $etatColor }} shadow-sm">{{ $vehicule->etat }}</span>
                    </div>
                    <div class="col-6">
                        <div class="info-label mb-2">Statut</div>
                        <span class="badge badge-pill-custom {{ $statutColor }} shadow-sm">{{ ucfirst($vehicule->statut) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Affectation active --}}
    <div class="col-lg-7">
        @if($active->count() > 0)
            <div class="card h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold text-civ-green"><i class="bi bi-person-bounding-box me-2"></i>Conducteur actuel</h5>
                </div>
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    @foreach($active as $a)
                    <div class="d-flex align-items-center gap-4 p-3 bg-light rounded-4 mb-4">
                        <div class="rounded-circle  d-flex align-items-center justify-content-center border" style="width:65px; height:65px; font-size: 1.4rem; font-weight: bold;">
                           <i class="bi bi-person"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0 text-dark">{{ $a->user->nom }} {{ $a->user->prenom }}</h4>
                            <p class="text-muted mb-0 font-monospace">{{ $a->user->matricule }}</p>
                            <span class="badge bg-civ-green bg-opacity-10 text-civ-green mt-1">
                                <i class="bi bi-calendar-event me-1"></i> Depuis le {{ \Carbon\Carbon::parse($a->assigned_at)->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="text-center p-3 border  mt-auto">
                        <p class="text-muted small">Action requise pour libérer le véhicule</p>
                        <button class="btn btn-civ-orange w-100 fw-bold py-2" data-bs-toggle="modal" data-bs-target="#retourModal{{ $a->id }}">
                            <i class="bi bi-arrow-repeat me-2"></i> Enregistrer le retour
                        </button>
                    </div>

                    {{-- Modal retour --}}
                    <div class="modal fade" id="retourModal{{ $a->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header border-0 bg-light">
                                    <h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle text-civ-orange me-2"></i>Confirmation de retour</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('vehicules.retirer', $a->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-body p-4">
                                        <label class="form-label fw-bold">Observations au retour</label>
                                        <textarea name="commentaire_retour" class="form-control border-civ-orange" rows="3" placeholder="État du véhicule, kilométrage, etc..."></textarea>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-civ-orange px-4 fw-bold">Confirmer le retrait</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="card shadow-sm h-100 d-flex align-items-center justify-content-center bg-light border-0" style="border: 2px dashed #ccc !important;">
                <div class="text-center p-5">
                    <div class="bg-white rounded-circle shadow-sm d-inline-flex p-4 mb-3">
                        <i class="bi bi-person-x text-muted" style="font-size: 2.5rem;"></i>
                    </div>
                    <h5 class="fw-bold text-muted">Véhicule non affecté</h5>
                    <p class="text-muted small">Ce véhicule est actuellement disponible dans le parc.</p>
                    <button class="btn btn-civ-green mt-2 px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#affecterModal">
                         Affecter maintenant
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Section Sorties --}}
@php
    $sortiesVehicule = $vehicule->sorties ?? collect();
    $sortieActive    = $vehicule->sortieActive;
@endphp
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-box-arrow-right me-2 text-danger"></i>Sorties du véhicule</h5>
        @if(!$sortieActive && $vehicule->statut !== 'enlevé')
        <a href="{{ route('sorties-vehicules.create', $vehicule->id) }}" class="btn btn-sm btn-outline-danger">
            <i class="bi bi-plus-circle me-1"></i> Nouvelle sortie
        </a>
        @endif
    </div>
    @if($sortieActive)
    <div class="alert alert-warning mx-3 mt-3 d-flex gap-2 align-items-center">
        <i class="bi bi-exclamation-triangle-fill"></i>
        Sortie en cours : <strong>{{ $sortieActive->type_label }}</strong>
        depuis le {{ $sortieActive->date_sortie->format('d/m/Y') }}
        <a href="{{ route('sorties-vehicules.show', $sortieActive->id) }}" class="ms-2 btn btn-sm btn-warning">Voir</a>
    </div>
    @endif
    @if($sortiesVehicule->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">Type</th>
                    <th>Motif</th>
                    <th>Date sortie</th>
                    <th>Retour prévu</th>
                    <th>Statut</th>
                    <th class="pe-4"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($sortiesVehicule->sortByDesc('date_sortie') as $s)
                @php
                    $sc = match($s->statut) { 'retourne' => 'bg-success', 'definitif' => 'bg-secondary', default => 'bg-warning text-dark' };
                    $tc = match($s->type_sortie) { 'maintenance_externe' => 'bg-info text-dark', 'enlevement' => 'bg-danger', 'reforme' => 'bg-secondary', default => 'bg-primary' };
                @endphp
                <tr>
                    <td class="ps-4"><span class="badge {{ $tc }}">{{ $s->type_label }}</span></td>
                    <td class="text-truncate" style="max-width:160px;">{{ $s->motif }}</td>
                    <td>{{ $s->date_sortie->format('d/m/Y') }}</td>
                    <td>{{ $s->date_retour_prevue?->format('d/m/Y') ?? '—' }}</td>
                    <td><span class="badge {{ $sc }}">{{ $s->statut_label }}</span></td>
                    <td class="pe-4">
                        <a href="{{ route('sorties-vehicules.show', $s->id) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="p-4 text-center text-muted">
        <i class="bi bi-box-arrow-right fs-2 mb-2 d-block"></i>
        Aucune sortie enregistrée pour ce véhicule.
    </div>
    @endif
</div>

{{-- Historique --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-clock-history me-2 text-civ-green"></i>Historique des mouvements</h5>
        <span class="badge bg-light text-civ-orange border-civ-orange border fw-bold">{{ $historique->count() }} enregistrements</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr class="text-civ-green">
                    <th class="ps-4 border-0">Utilisateur</th>
                    <th class="border-0">Période</th>
                    <th class="border-0">Par (Assignation/Retrait)</th>
                    <th class="pe-4 border-0">Observations</th>
                </tr>
            </thead>
            <tbody>
                @foreach($historique as $a)
                <tr>
                    <td class="ps-4">
                        <div class="fw-bold text-dark">{{ $a->user->nom ?? '—' }} {{ $a->user->prenom ?? '' }}</div>
                        <div class="small text-muted font-monospace">{{ $a->user->matricule }}</div>
                    </td>
                    <td>
                        <div class="small text-dark font-weight-bold">Dép : {{ \Carbon\Carbon::parse($a->assigned_at)->format('d/m/Y') }}</div>
                        <div class="small text-civ-orange">Ret : {{ \Carbon\Carbon::parse($a->returned_at)->format('d/m/Y') }}</div>
                    </td>
                    <td>
                        <div class="small"><i class="bi bi-person-check text-civ-green"></i> {{ $a->assignedBy->nom ?? '—' }}</div>
                        <div class="small"><i class="bi bi-person-x text-civ-orange"></i> {{ $a->returnedBy->nom ?? '—' }}</div>
                    </td>
                    <td class="pe-4 small italic text-muted">
                        {{ $a->commentaire_retour ?? '—' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Affectation --}}
@if($vehicule->statut === 'disponible')
@php $users = \App\Models\User::where('direction_id', Auth::user()->direction_id)->get(); @endphp
<div class="modal fade" id="affecterModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-civ-green border-0">
                <h5 class="modal-title fw-bold"><i class="bi bi-person-plus me-2"></i>Affectation du véhicule</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('vehicules.affecter', $vehicule->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Choisir un agent <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-select select2-show" required style="width: 100%">
                            <option value=""></option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ strtoupper($user->nom) }} {{ $user->prenom }} ({{ $user->matricule }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-civ-green px-4 fw-bold shadow-sm">Confirmer l'affectation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(function () {
    $('.select2-show').select2({ 
        dropdownParent: $('#affecterModal'), 
        placeholder: 'Rechercher par nom ou matricule...', 
        allowClear: true 
    });
});
</script>
@endif

@endsection