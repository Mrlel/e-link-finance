@extends('layouts.main-board')
@section('title', 'Détails mobilier')
@section('content')

@php
    $statutColor = match($mobilier->statut) {
        'affecté'    => 'bg-primary',
        'en réforme' => 'bg-secondary',
        default      => 'bg-success',
    };
    $etatColor = match($mobilier->etat) {
        'neuf'        => 'bg-success',
        'bon'         => 'bg-primary',
        'moyen'       => 'bg-warning text-dark',
        'mauvais'     => 'bg-danger',
        'hors service'=> 'bg-dark',
        default       => 'bg-secondary',
    };
    $active     = $mobilier->assignments->whereNull('returned_at');
    $historique = $mobilier->assignments->whereNotNull('returned_at');
    $joursRestants = $mobilier->date_fin_vie ? now()->diffInDays($mobilier->date_fin_vie, false) : null;
@endphp

{{-- En-tête --}}
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between py-4 gap-3">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('mobiliers.index') }}" class="text-decoration-none">Mobilier</a></li>
                <li class="breadcrumb-item active">{{ $mobilier->designation }}</li>
            </ol>
        </nav>
        <h1 class="h3 fw-bold mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-lamp text-success"></i> {{ $mobilier->designation }}
        </h1>
        <p class="text-muted mb-0">{{ \App\Models\Mobilier::$categories[$mobilier->categorie] ?? $mobilier->categorie }}</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('mobiliers.edit', $mobilier->id) }}" class="btn btn-outline-primary">
            <i class="bi bi-pencil me-1"></i> Modifier
        </a>
        @if($mobilier->statut === 'en stock')
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#affecterModal">
            <i class="bi bi-person-check me-1"></i> Affecter
        </button>
        @endif
        @if($mobilier->statut !== 'en réforme')
        <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#sortieModal">
            <i class="bi bi-box-arrow-right me-1"></i> Sortie / Réforme
        </button>
        @endif
        <a href="{{ route('mobiliers.index') }}" class="btn btn-outline-secondary">Retour</a>
    </div>
</div>

@include('layouts.message')

{{-- Alertes --}}
@if($joursRestants !== null)
    @php
        $alertClass = $joursRestants < 0 ? 'danger' : ($joursRestants <= 30 ? 'warning' : 'success');
        $alertIcon  = $joursRestants < 0 ? 'bi-x-circle-fill' : ($joursRestants <= 30 ? 'bi-exclamation-triangle-fill' : 'bi-check-circle-fill');
    @endphp
    <div class="alert alert-{{ $alertClass }} d-flex gap-2 align-items-center mb-4">
        <i class="bi {{ $alertIcon }}"></i>
        @if($joursRestants < 0)
            Fin de durée de vie dépassée depuis {{ abs($joursRestants) }} jour(s) — {{ $mobilier->date_fin_vie->format('d/m/Y') }}
        @elseif($joursRestants <= 30)
            Fin de durée de vie dans <strong>{{ $joursRestants }} jour(s)</strong> — {{ $mobilier->date_fin_vie->format('d/m/Y') }}
        @else
            Fin de durée de vie : {{ $mobilier->date_fin_vie->format('d/m/Y') }} ({{ $joursRestants }} jours restants)
        @endif
    </div>
@endif

<div class="row g-4 mb-4">
    {{-- Fiche --}}
    <div class="col-md-5">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-dark text-white fw-semibold">
                <i class="bi bi-info-circle me-2"></i>Informations
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5 text-muted">N° Inventaire</dt>
                    <dd class="col-7">{{ $mobilier->num_inventaire ?? '—' }}</dd>

                    <dt class="col-5 text-muted">Catégorie</dt>
                    <dd class="col-7">{{ \App\Models\Mobilier::$categories[$mobilier->categorie] ?? $mobilier->categorie }}</dd>

                    <dt class="col-5 text-muted">Marque</dt>
                    <dd class="col-7">{{ $mobilier->marque ?? '—' }}</dd>

                    <dt class="col-5 text-muted">Référence</dt>
                    <dd class="col-7">{{ $mobilier->reference ?? '—' }}</dd>

                    <dt class="col-5 text-muted">État</dt>
                    <dd class="col-7"><span class="badge {{ $etatColor }}">{{ ucfirst($mobilier->etat) }}</span></dd>

                    <dt class="col-5 text-muted">Statut</dt>
                    <dd class="col-7"><span class="badge {{ $statutColor }}">{{ ucfirst($mobilier->statut) }}</span></dd>

                    <dt class="col-5 text-muted">Acquisition</dt>
                    <dd class="col-7">{{ $mobilier->date_acquisition?->format('d/m/Y') ?? '—' }}</dd>

                    <dt class="col-5 text-muted">Mode</dt>
                    <dd class="col-7">{{ ucfirst($mobilier->mode_acquisition) }}</dd>

                    <dt class="col-5 text-muted">Fin de vie</dt>
                    <dd class="col-7">{{ $mobilier->date_fin_vie?->format('d/m/Y') ?? '—' }}</dd>

                    @if($mobilier->observations)
                    <dt class="col-5 text-muted">Observations</dt>
                    <dd class="col-7">{{ $mobilier->observations }}</dd>
                    @endif
                </dl>
            </div>
        </div>
    </div>

    {{-- Affectation active --}}
    @if($active->count() > 0)
    <div class="col-md-7">
        <div class="card shadow-sm h-100 border-primary">
            <div class="card-header bg-primary text-white fw-semibold">
                <i class="bi bi-person-fill me-2"></i>Affectation en cours
            </div>
            <div class="card-body">
                @foreach($active as $a)
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                         style="width:48px;height:48px;">
                        <i class="bi bi-person-fill text-primary fs-4"></i>
                    </div>
                    <div>
                        <div class="fw-semibold">{{ $a->user->nom ?? '—' }} {{ $a->user->prenom ?? '' }}</div>
                        <div class="small text-muted">{{ $a->user->matricule ?? '' }}</div>
                        <div class="small text-muted">
                            Affecté le {{ \Carbon\Carbon::parse($a->assigned_at)->format('d/m/Y') }}
                            par {{ $a->assignedBy->nom ?? '—' }}
                        </div>
                        @if($a->motif_affectation)
                        <div class="small text-muted">Motif : {{ $a->motif_affectation }}</div>
                        @endif
                    </div>
                </div>
                <button class="btn btn-outline-warning btn-sm"
                        data-bs-toggle="modal" data-bs-target="#retourModal{{ $a->id }}">
                    <i class="bi bi-arrow-return-left me-1"></i> Retirer le mobilier
                </button>

                {{-- Modal retour --}}
                <div class="modal fade" id="retourModal{{ $a->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Retrait — {{ $mobilier->designation }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('mobiliers.retirer', $a->id) }}" method="POST">
                                @csrf @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Commentaire (optionnel)</label>
                                        <textarea name="commentaire_retour" class="form-control" rows="3"
                                                  placeholder="Motif du retrait…"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <button type="submit" class="btn btn-warning">Confirmer le retrait</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Historique affectations --}}
@if($historique->count() > 0)
<h5 class="fw-semibold mb-3">Historique des affectations</h5>
<div class="table-responsive shadow-sm rounded mb-4">
    <table class="table table-striped align-middle mb-0">
        <thead class="table-secondary">
            <tr>
                <th>Utilisateur</th>
                <th>Affecté le</th>
                <th>Affecté par</th>
                <th>Retiré le</th>
                <th>Commentaire</th>
            </tr>
        </thead>
        <tbody>
            @foreach($historique->sortByDesc('assigned_at') as $a)
            <tr>
                <td>{{ $a->user->nom ?? '—' }} {{ $a->user->prenom ?? '' }}</td>
                <td>{{ \Carbon\Carbon::parse($a->assigned_at)->format('d/m/Y') }}</td>
                <td>{{ $a->assignedBy->nom ?? '—' }}</td>
                <td>{{ \Carbon\Carbon::parse($a->returned_at)->format('d/m/Y') }}</td>
                <td>{{ $a->commentaire_retour ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

{{-- Sorties --}}
@if($mobilier->sorties->count() > 0)
<h5 class="fw-semibold mb-3">Sorties enregistrées</h5>
<div class="table-responsive shadow-sm rounded mb-4">
    <table class="table table-striped align-middle mb-0">
        <thead class="table-secondary">
            <tr><th>Type</th><th>Motif</th><th>Date</th><th>Demandeur</th><th>Observations</th></tr>
        </thead>
        <tbody>
            @foreach($mobilier->sorties->sortByDesc('date_sortie') as $s)
            <tr>
                <td><span class="badge bg-danger">{{ $s->type_label }}</span></td>
                <td>{{ $s->motif }}</td>
                <td>{{ $s->date_sortie->format('d/m/Y') }}</td>
                <td>{{ $s->demandeur->nom ?? '—' }}</td>
                <td>{{ $s->observations ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

{{-- Modal affectation --}}
@if($mobilier->statut === 'en stock')
@php $users = \App\Models\User::where('direction_id', Auth::user()->direction_id)->get(); @endphp
<div class="modal fade" id="affecterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-person-plus me-2 text-success"></i>Affecter le mobilier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('mobiliers.affecter', $mobilier->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark">Bénéficiaire ou utilisateur <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-select select2-show" required style="width: 100%">
                            <option value=""></option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->nom }} {{ $u->prenom }} — {{ $u->matricule }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold text-dark">Motif d'affectation</label>
                        <input type="text" name="motif_affectation" class="form-control"
                               placeholder="Ex: Remplacement, Aménagement nouveau bureau…">
                    </div>
                </div>
                <div class="modal-footer bg-light border-top">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success fw-medium"><i class="bi bi-check-circle me-1"></i> Valider l'affectation</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
$(function () {
    $('.select2-show').select2({ dropdownParent: $('#affecterModal'), placeholder: '— Rechercher un agent —', allowClear: true });
});
</script>
@endif


{{-- Modal sortie --}}
@if($mobilier->statut !== 'en réforme')
<div class="modal fade" id="sortieModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-box-arrow-right me-2 text-danger"></i>Sortie / Réforme</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('mobiliers.sortie', $mobilier->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Type de sortie <span class="text-danger">*</span></label>
                        <select name="type_sortie" class="form-select" required>
                            <option value="reforme">Réforme</option>
                            <option value="enlevement"> Enlèvement définitif</option>
                            <option value="transfert">Transfert</option>
                            <option value="perte">Perte</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Motif <span class="text-danger">*</span></label>
                        <textarea name="motif" class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Date de sortie <span class="text-danger">*</span></label>
                        <input type="date" name="date_sortie" class="form-control"
                               value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Observations</label>
                        <textarea name="observations" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger"><i class="bi bi-check-circle me-1"></i> Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection
