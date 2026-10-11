@extends('layouts.main-board')

@section('content')
@include('layouts.message')

<div class="container-fluid">

   <header class="my-4">
  <h1 class="h3 fw-bold text-dark mb-1 d-flex align-items-center">
    Mes demandes en cours 
  </h1>
  <p class="text-muted mb-0">
    Gestion des demandes de materiel et maintenance • informatiques
  </p>
</header>

    <div class="row g-4 mb-4">
  <!-- Demandes de matériels -->
  <div class="col-12 col-sm-6 col-lg-6">
    <div class="card border-0 shadow-sm p-5 position-relative stat-card text-decoration-none text-dark">
      
      <div class="position-absolute top-0 start-0 end-0"
           style="height:5px; background:linear-gradient(135deg,#28a745,#20c997);"></div>

      <div class="d-flex justify-content-between align-items-start">
        <div>
          <h4 class="fw-bold text-muted mb-2">Demandes de matériels</h4>
          <div class="d-flex align-items-baseline gap-2">
            <span class="fs-1 fw-bold text-dark">{{ $demande_materiels->count() }}</span>
            <span class="small text-muted">Soumises</span>
          </div>
        </div>

        <div class="d-flex align-items-center justify-content-center rounded p-3"
             style="background:linear-gradient(135deg,#28a745,#20c997); width:64px; height:64px;">
          <i class="bi bi-list-task fs-3 text-white"></i>
        </div>
      </div>

      <div class="position-absolute bottom-0 end-0 opacity-25" style="width:100px; height:100px;">
        <i class="bi bi-list-task" style="font-size:100px;"></i>
      </div>
</div>
  </div>

  <!-- Demandes de maintenance -->
  <div class="col-12 col-sm-6 col-lg-6">
    <div class="card border-0 shadow-sm p-5 position-relative stat-card text-decoration-none text-dark">
      
      <div class="position-absolute top-0 start-0 end-0"
           style="height:5px; background:linear-gradient(90deg,#ffc107,#fd7e14);"></div>

      <div class="d-flex justify-content-between align-items-start">
        <div>
          <h4 class="fw-bold text-muted mb-2">Demandes de maintenance</h4>
          <div class="d-flex align-items-baseline gap-2">
            <span class="fs-1 fw-bold text-dark">{{ $demande_maintenances->count() }}</span>
            <span class="small text-muted">Ouvertes</span>
          </div>
        </div>

        <div class="d-flex align-items-center justify-content-center rounded p-3"
             style="background:linear-gradient(90deg,#ffc107,#fd7e14); width:64px; height:64px;">
          <i class="bi bi-gear fs-3 text-white"></i>
        </div>
      </div>

      <div class="position-absolute bottom-0 end-0 opacity-25" style="width:100px; height:100px;">
        <i class="bi bi-gear" style="font-size:100px;"></i>
      </div>
</div>
  </div>
</div>


    <!-- Onglets Bootstrap -->
    <ul class="nav nav-tabs mb-4" id="demandeTabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" id="materiel-tab" data-bs-toggle="tab"
                    data-bs-target="#materiel-content" type="button">
                <i class="bi bi-box-seam me-2"></i> Historique demandes matériel ({{ $demande_materiels->count() }})
            </button>
        </li>

        <li class="nav-item">
            <button class="nav-link" id="maintenance-tab" data-bs-toggle="tab"
                    data-bs-target="#maintenance-content" type="button">
                <i class="bi bi-gear me-2"></i> Historique demandes maintenance ({{ $demande_maintenances->count() }})
            </button>
        </li>
    </ul>

    <!-- Contenu des Tabs -->
    <div class="tab-content">

        <!-- TAB MATÉRIEL -->
        <div class="tab-pane fade show active" id="materiel-content">

            <button class="btn btn-success mb-4" data-bs-toggle="modal" data-bs-target="#modalDemandeMateriel">
                <i class="bi bi-plus-circle me-2"></i> Nouvelle demande
            </button>

            @if($demande_materiels->count())
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Priorité</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($demande_materiels as $demande)
                    <tr>
                        <td>{{ $demande->created_at->format('d/m/Y') }}</td>
                        <td>{{ $demande->typ_mat }}</td>
                        <td>{{ Str::limit($demande->desc_demande, 35) }}</td>
                        <td>{{ $demande->priorite_dem }}</td>

                        <td>
                            @if(str_contains($demande->statut_dem, 'attente'))
                                <span class="badge bg-warning text-dark">En attente</span>
                            @elseif($demande->statut_dem === 'traitée')
                                <span class="badge bg-success">Traitée</span>
                            @elseif($demande->statut_dem === 'approuvée')
                                <span class="badge bg-success">Approuvée</span>
                            @else
                                <span class="badge bg-danger">Rejetée</span>
                            @endif
                        </td>

                        <td>
                            @if($demande->statut_dem === 'rejetée')
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#detailMateriel{{ $demande->id }}">
                                    Détails
                                </button>
                            @elseif($demande->statut_dem === 'traitée')
                                <i class="bi bi-check-circle text-success fs-5"></i>
                            @else
                                <form method="POST" action="{{ route('demande-materiel.cancel', $demande->id) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Voulez-vous vraiment annuler cette demande ?')">Annuler</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>

            @else
                <div class="text-center py-4">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                    <p class="text-muted">Aucune demande trouvée</p>
                </div>
            @endif

        </div>

        <!-- TAB MAINTENANCE -->
        <div class="tab-pane fade" id="maintenance-content">

            @if($demande_maintenances->count())

            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Équipement</th>
                        <th>N° Série</th>
                        <th>Marque</th>
                        <th>Priorité</th>
                        <th>Statut</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($demande_maintenances as $maintenance)
                    <tr>
                        <td>{{ $maintenance->created_at->format('d/m/Y') }}</td>
                        <td>{{ $maintenance->equipement->categorie }}</td>
                        <td>{{ $maintenance->equipement->numero_serie }}</td>
                        <td>{{ $maintenance->equipement->marque }}</td>
                        <td>{{ $maintenance->priorite_dmtc }}</td>

                        <td>
                            @if(str_contains($maintenance->statut_dmtc, 'attente'))
                                <span class="badge bg-warning text-dark">En attente</span>
                            @elseif(str_contains($maintenance->statut_dmtc, 'rej'))
                                <span class="badge bg-danger">Rejetée</span>
                            @else
                                <span class="badge bg-success">Approuvée</span>
                            @endif
                        </td>

                        <td>{{ Str::limit($maintenance->desc_prblem, 30) }}</td>

                        <td class="d-flex align-item-center gap-2">
                            <form method="POST" action="{{ route('demande-maintenance.cancel', $maintenance->id) }}">
                                @csrf
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Voulez-vous vraiment annuler cette demande ?')">
                                    <i class="bi bi-x-circle"></i>
                                      Annuler
                                </button>
                            </form>

                            <button class="btn btn-sm btn-outline-success" 
                                    data-bs-toggle="modal"
                                    data-bs-target="#detailMaintenance{{ $maintenance->id }}">
                                <i class="bi bi-info-circle"></i> Détails
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                    <p class="text-muted">Aucune demande de maintenance</p>
                </div>
            @endif

        </div>

    </div>

</div>




<!-- =============================== -->
<!--      MODALs BOOSTRAP        -->
<!-- =============================== -->

    @foreach($demande_materiels as $demande)
<div class="modal fade" id="detailMateriel{{ $demande->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg rounded-3 border-0">

            <div class="modal-header bg-primary text-white rounded-top">
                <h5 class="modal-title">
                    <i class="bi bi-info-circle me-2"></i>
                    Détails de la demande d'équipement
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <ul class="list-group list-group-flush">

                    <li class="list-group-item d-flex justify-content-between">
                        <span><strong>Statut :</strong> {{ $demande->statut_dem }}</span>
                        <span><strong>Date :</strong> {{ $demande->created_at->format('d/m/Y H:i') }}</span>
                    </li>

                    <li class="list-group-item">
                        <strong>Commentaire :</strong>
                        <p class="mt-1 text-danger">
                            {{ $demande->commentaire ?? 'Aucun commentaire' }}
                        </p>
                    </li>

                </ul>
            </div>

        </div>
    </div>
</div>
@endforeach


@foreach($demande_maintenances as $maintenance)
<div class="modal fade" id="detailMaintenance{{ $maintenance->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-3">

            <div class="modal-header bg-warning text-dark rounded-top">
                <h5 class="modal-title">
                    <i class="bi bi-tools me-2"></i>
                    Détails de la maintenance
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-2"><strong>Type de matériel :</strong> {{ $maintenance->type_mat }}</div>
                <div class="mb-2"><strong>Marque :</strong> {{ $maintenance->marque_mat }}</div>
                <div class="mb-2"><strong>Modèle :</strong> {{ $maintenance->modele_mat }}</div>
                <div class="mb-2"><strong>N° Série :</strong> {{ $maintenance->num_serie }}</div>

                <div class="mb-2">
                    <strong>Description du problème :</strong><br>
                    <span class="text-danger">{{ $maintenance->desc_prblem }}</span>
                </div>

                <div class="mb-2"><strong>Priorité :</strong> {{ $maintenance->priorite_dmtc }}</div>
                <div class="mb-2"><strong>Statut :</strong> {{ $maintenance->statut_dmtc }}</div>
                <div><strong>Date de demande :</strong> {{ $maintenance->created_at->format('d/m/Y H:i') }}</div>

            </div>

        </div>
    </div>
</div>
@endforeach


<div class="modal fade" id="modalDemandeMateriel" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-3">

            <div class="modal-header bg-success text-white rounded-top">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i> Nouvelle demande de matériel
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form action="/demandeMateriel/traitement" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Type de matériel</label>
                        <select class="form-select" name="typ_mat" required>
                            <option value="">Choisir...</option>
                            <option value="Ordinateur portable">Ordinateur portable</option>
                            <option value="Ordinateur All-in-one">Ordinateur All-in-one</option>
                            <option value="unite centrale">Unité Central (DESKTOP)</option>
                            <option value="Imprimante">Imprimante</option>
                            <option value="Scanner">Scanner</option>
                            <option value="Routeur">Routeur</option>
                            <option value="Switch">Switch</option>
                            <option value="ecran">Ecran</option>
                            <option value="souris">Souris</option>
                            <option value="clavier">Clavier</option>
                            <option value="Onduleur">Onduleur</option>
                            <option value="Téléphone IP">Téléphone IP</option>
                            <option value="Accessoire">Accessoire</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Priorité</label>
                        <select class="form-select" name="priorite_dem" required>
                            <option value="">Choisir...</option>
                            <option value="urgent">Urgente</option>
                            <option value="moyenne">Moyenne</option>
                            <option value="faible">Faible</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="desc_demande" rows="4" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-send me-2"></i> Envoyer la demande
                    </button>

                </form>

            </div>

        </div>
    </div>
</div>

<!-- =============================== -->
<!--      MODAL BOOSTRAP        -->
<!-- =============================== -->

@push('scripts')
<script>
    // Aucun JS custom nécessaire, Bootstrap gère tout
</script>
@endpush

@endsection
