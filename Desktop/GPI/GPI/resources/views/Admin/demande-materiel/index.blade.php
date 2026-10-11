@extends('layouts.main-board')

@section('content')

@include('layouts.message')
@include('layouts.demande-materiel')

<div class="card shadow-sm border-0 mt-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold d-flex align-items-center">
            <i class="bi bi-list-check text-primary me-2"></i>
            Demandes de Matériel
        </h5>
    </div>

    <div class="card-body table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Utilisateur</th>
                    <th>Type de matériel</th>
                    <th>Priorité</th>
                    <th>Description</th>
                    <th>Date / Heure</th>
                    <th>Statut</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
            @foreach($demandeMateriels as $demandeMateriel)
                <tr>
                    <!-- Utilisateur -->
                    <td class="fw-semibold">{{ $demandeMateriel->user->nom }}</td>

                    <!-- Type -->
                    <td>{{ $demandeMateriel->typ_mat }}</td>

                    <!-- Priorité -->
                    <td>
                        @if($demandeMateriel->priorite_dem == 'haute')
                            <span class="text-danger d-flex align-items-center gap-2">
                                Haute <ion-icon name="arrow-up-outline"></ion-icon>
                            </span>
                        @elseif($demandeMateriel->priorite_dem == 'moyenne')
                            <span class="text-warning d-flex align-items-center gap-2">
                                Moyenne <ion-icon name="remove-outline"></ion-icon>
                            </span>
                        @else
                            <span class="text-success d-flex align-items-center gap-2">
                                Basse <ion-icon name="arrow-down-outline"></ion-icon>
                            </span>
                        @endif
                    </td>

                    <!-- Description -->
                    <td>{{ Str::limit($demandeMateriel->desc_demande, 30) }}</td>

                    <!-- Date / Heure -->
                    <td>
                        {{ $demandeMateriel->created_at->format('d/m/Y') }}<br>
                        <small class="text-muted">{{ $demandeMateriel->created_at->format('H:i') }}</small>
                    </td>

                    <!-- Statut -->
                    <td>
                        @if($demandeMateriel->statut_dem == 'approuvée')
                            <span class="badge bg-success-subtle text-success">Approuvée</span>
                        @elseif($demandeMateriel->statut_dem == 'rejetée')
                            <span class="badge bg-danger-subtle text-danger">Rejetée</span>
                        @elseif($demandeMateriel->statut_dem == 'traitée')
                            <span class="badge bg-info-subtle text-info">Traitée</span>
                        @else
                            <span class="badge bg-warning-subtle text-warning">En attente</span>
                        @endif
                    </td>

                    <!-- Actions -->
                    <td class="text-center">

                        <!-- Gestionnaire parc → Traiter -->
                        @if(auth()->user()->role == 'gestionnaire_parc' && $demandeMateriel->statut_dem == 'approuvée')
                            <a href="{{ route('demande-materiel.show', $demandeMateriel->id) }}" 
                                class="btn btn-primary btn-sm d-flex align-items-center justify-content-center gap-1 mb-2">
                                <i class="bi bi-pencil-square"></i> Traiter
                            </a>
                        @endif


                        <!-- Sous-directeur → Modal Transmission -->
                        @if(auth()->user()->role == 'sous_directeur' && $demandeMateriel->statut_dem == 'en attente chef')
                            <button class="btn btn-success btn-sm d-flex align-items-center gap-2"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalSousDir{{ $demandeMateriel->id }}">
                                <i class="bi bi-send"></i> Transmettre
                            </button>

                            <!-- Modal sous-directeur -->
                            <div class="modal fade" id="modalSousDir{{ $demandeMateriel->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content shadow-sm">
                                        
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">Transmission de la demande</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="row g-3">

                                                <!-- Chef de service -->
                                                <div class="col-md-6">
                                                    <div class="card bg-light border-0">
                                                        <div class="card-body text-center">
                                                            <img src="/gest.png" width="50" class="rounded-circle mb-3">
                                                            <p class="small text-muted">Chef de Service Patrimoine</p>

                                                            <form action="{{ route('chef_de_service.demande-materiel.approuver', $demandeMateriel->id) }}" method="POST">
                                                                @csrf @method('PUT')
                                                                <button class="btn btn-outline-success w-100">
                                                                    <i class="bi bi-send"></i> Envoyer
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Directeur -->
                                                <div class="col-md-6">
                                                    <div class="card bg-light border-0">
                                                        <div class="card-body text-center">
                                                            <img src="/boss.png" width="50" class="rounded-circle mb-3">
                                                            <p class="small text-muted">Directeur</p>

                                                            <form action="{{ route('gestionnaire_parc.demande-materiel.approuver', $demandeMateriel->id) }}" method="POST">
                                                                @csrf @method('PUT')
                                                                <button class="btn btn-outline-success w-100">
                                                                    <i class="bi bi-send-check"></i> Envoyer
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endif


                        <!-- Gestionnaire Parc → Approbation -->
                        @if(auth()->user()->role == 'gestionnaire_parc' && $demandeMateriel->statut_dem == 'en attente GestParc')
                            <form action="{{ route('gestionnaire_parc.demande-materiel.approuver', $demandeMateriel->id) }}" 
                                  method="POST" class="mt-1">
                                @csrf @method('PUT')
                                <button class="btn btn-success btn-sm d-flex align-items-center gap-2">
                                    Envoyer au Directeur <i class="bi bi-arrow-right"></i>
                                </button>
                            </form>
                        @endif


                        <!-- Chef de service → Transmettre -->
                        @if(auth()->user()->role == 'chef_de_service' && $demandeMateriel->statut_dem == 'en attente chef')
                            <form action="{{ route('chef_de_service.demande-materiel.approuver', $demandeMateriel->id) }}" method="POST">
                                @csrf @method('PUT')
                                <button class="btn btn-success btn-sm d-flex align-items-center gap-2 mt-1">
                                    <i class="bi bi-send"></i> Transmettre
                                </button>
                            </form>
                        @endif


                        <!-- Admin -->
                        @if(auth()->user()->role == 'admin' && $demandeMateriel->statut_dem == 'en attente admin')

                            <!-- Approuver -->
                            <form action="{{ route('admin.demande-materiel.approuver', $demandeMateriel->id) }}"
                                  method="POST" class="d-inline">
                                @csrf @method('PUT')
                                <button class="btn btn-success btn-sm d-flex align-items-center gap-2">
                                    Accepter <i class="bi bi-check-circle"></i>
                                </button>
                            </form>

                            <!-- Rejeter (modal) -->
                            <button class="btn btn-danger btn-sm d-flex align-items-center gap-2 mt-2"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalReject{{ $demandeMateriel->id }}">
                                Refuser <i class="bi bi-x-circle"></i>
                            </button>

                            <!-- Modal rejet -->
                            <div class="modal fade" id="modalReject{{ $demandeMateriel->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg">

                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title fw-bold">Motif du rejet</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <form action="{{ route('admin.demande-materiel.rejeter', $demandeMateriel->id) }}" method="POST">
                                                @csrf @method('PUT')

                                                <label class="fw-semibold">Commentaire (optionnel)</label>
                                                <textarea name="commentaire" class="form-control mt-1 mb-3" rows="4" placeholder="Expliquez la raison..."></textarea>

                                                <button class="btn btn-danger w-100">Confirmer le rejet</button>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        @endif

                    </td>
                </tr>
            @endforeach
            </tbody>

        </table>
    </div>
</div>

@endsection
