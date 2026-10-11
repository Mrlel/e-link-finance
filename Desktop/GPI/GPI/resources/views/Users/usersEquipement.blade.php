@extends('layouts.user-section')

@section('content')
@include('layouts.message')

<div class="userEquipement">

    @if($equipements->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body py-5">
                <div class="empty-state fade-in text-center">
                    <div class="bg-light rounded-circle mx-auto mb-4 d-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                        <i class="fas fa-desktop fs-1 text-primary opacity-75"></i>
                    </div>
                    <h3 class="mb-3">Aucun équipement attribué</h3>
                    <p class="text-muted mb-4">Vous n'avez reçu aucun équipement pour le moment. En cas de besoin,<br> veuillez faire une demande de matériel.</p>
                    <a href="#" class="btn btn-primary px-4 py-2">
                        <i class="fas fa-plus-circle me-2"></i>Demander un équipement
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="table-container fade-in">
            <div class="card border-0">
                <div class="card-header bg-white border-0">
                    <h1 class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-2" style="font-size: 2rem;">
                            <i class="fas fa-list-ul me-2 text-primary"></i> Mes équipements informatiques
                        </h5>
                    </>
                </div>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th width="5%" class="py-3">Reception</th>
                                <th width="15%" class="py-3">Désignation</th>
                                <th width="10%" class="py-3">Catégorie</th>
                                <th width="10%" class="py-3">Marque</th>
                                <th width="10%" class="py-3">Modèle</th>
                                <th width="10%" class="py-3">Capacité</th>
                                <th width="10%" class="py-3">RAM (Go)</th>
                                <th width="15%" class="py-3">Numéro de série</th>
                                <th width="20%" class="py-3 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($equipements as $equipement)
                                <tr class="equipment-row">
                                    <td>
                                        @php
                                            $assignment = $equipement->assignments->first();
                                        @endphp

                                        @if($assignment && $assignment->confirmed)
                                            <span class="text-success"><ion-icon name="checkmark-done"></ion-icon></span>
                                        @else
                                            <span class="text-danger"><ion-icon name="ban-outline"></ion-icon></span>
                                        @endif
                                    </td>
                                    <td class="fw-medium text-primary">{{ $equipement->des_equipement ?? 'non défini'}}</td>
                                    <td>{{ $equipement->categorie ?? 'non défini'}}</td>
                                    <td>{{ $equipement->marque ?? 'non défini'}}</td>
                                    <td>{{ $equipement->modele ?? 'non défini'}}</td>
                                    <td>{{ $equipement->capacite ?? 'non défini' }}</td>
                                    <td>{{ $equipement->ram ?? 'non défini' }}</td>
                                    <td>
                                        <code class="bg-light p-1 rounded">{{ $equipement->numero_serie ?? 'non défini'}}</code>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-end">
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modal{{ $equipement->id }}" title="Signaler un problème">
                                                <i class="fas fa-exclamation-circle me-1 text-danger"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#exempleModal{{ $equipement->id }}" title="Confirmer la réception">
                                                <i class="fas fa-check me-1 text-success"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $equipement->id }}" title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal détails de l'équipement -->
                                <div class="modal fade" id="detailsModal{{ $equipement->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-info-circle me-2"></i>Détails de l'équipement
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <div class="text-center mb-4">
                                                    <h4 class="mb-0">{{ $equipement->des_equipement }}</h4>
                                                    <p class="text-muted mb-0">{{ $equipement->categorie }}</p>
                                                </div>
                                                
                                                <div class="row g-3">
                                                    <div class="col-6">
                                                        <div class="bg-light rounded p-3">
                                                            <p class="text-muted mb-1 small">Marque</p>
                                                            <p class="mb-0 fw-medium">{{ $equipement->marque ?? 'Non défini' }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="bg-light rounded p-3">
                                                            <p class="text-muted mb-1 small">Modèle</p>
                                                            <p class="mb-0 fw-medium">{{ $equipement->modele ?? 'Non défini' }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="bg-light rounded p-3">
                                                            <p class="text-muted mb-1 small">Capacité (Go)</p>
                                                            <p class="mb-0 fw-medium">{{ $equipement->capacite ?? 'Non défini' }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="bg-light rounded p-3">
                                                            <p class="text-muted mb-1 small">RAM (Go)</p>
                                                            <p class="mb-0 fw-medium">{{ $equipement->ram ?? 'Non défini' }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="bg-light rounded p-3">
                                                            <p class="text-muted mb-1 small">Numéro de série</p>
                                                            <p class="mb-0 fw-medium">{{ $equipement->numero_serie ?? 'Non défini' }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="bg-light rounded p-3">
                                                            <p class="text-muted mb-1 small">Date d'attribution</p>
                                                            <p class="mb-0 fw-medium">{{ isset($equipement->date_attribution) ? date('d/m/Y', strtotime($equipement->date_attribution)) : 'Non défini' }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="bg-light rounded p-3">
                                                            <p class="text-muted mb-1 small">État</p>
                                                            <p class="mb-0">
                                                                @if(isset($equipement->est_confirme) && $equipement->est_confirme)
                                                                    <span class="badge bg-success">Confirmé</span>
                                                                @else
                                                                    <span class="badge bg-warning">En attente</span>
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="button" class="btn bg-secondary-subtle" data-bs-dismiss="modal">Fermer</button>
                                                <button type="button" class="btn bg-danger-subtle text-danger" data-bs-toggle="modal" data-bs-target="#modal{{ $equipement->id }}" data-bs-dismiss="modal">
                                                    <i class="fas fa-exclamation-circle me-2"></i>Signaler un problème
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal confirmation - Version modernisée -->
                                <div class="modal fade" id="exempleModal{{ $equipement->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow-lg">
                                            <!-- En-tête avec icône et titre -->
                                            <div class="modal-header bg-success text-white border-0 rounded-top">
                                                <div class="d-flex align-items-center">
                                                    <h5 class="modal-title mb-0"><i class="fas fa-check-circle me-2"></i> Confirmation de réception</h5>
                                                </div>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                        
                                            <!-- Corps du modal -->
                                            <div class="modal-body py-4 px-4">
                                                <div class="d-flex flex-column align-items-center text-center mb-4">
                                                    <div class="bg-success-subtle p-4 rounded-circle mb-3">
                                                        <i class="fas fa-desktop text-success fs-2"></i>
                                                    </div>
                                                    <p class="mb-3">Voulez-vous confirmer que vous avez bien reçu l'équipement :</p>
                                                    <h4 class="text-dark mb-2 fw-bold border p-4 bg-light rounded">{{ $equipement->des_equipement }}</h4>
                                                </div>

                                                <form action="{{ route('equipement.confirmer', $equipement->id) }}" method="POST" id="confirmationForm{{ $equipement->id }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group align-text-center mb-3">
                                                        <div class="form-check bg-light p-3 rounded d-flex align-items-center">
                                                            <input class="form-check-input border-2" type="checkbox" name="confirmed" id="confirmed{{ $equipement->id }}" value="1" required>
                                                            <label class="form-check-label ms-2" for="confirmed{{ $equipement->id }}">
                                                                <span class="fw-medium cursor-pointer">Oui, je confirme avoir reçu cet équipement</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    
                                                </form>
                                            </div>
                                        
                                            <!-- Pied de page -->
                                            <div class="modal-footer border-0 d-flex justify-content-between">
                                                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                                                    <i class="fas fa-times me-2"></i>Annuler
                                                </button>
                                                <button type="submit" form="confirmationForm{{ $equipement->id }}" class="btn btn-success px-4">
                                                    <i class="fas fa-check me-2"></i>Confirmer
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal pour chaque équipement - Signaler un problème -->
                                <div class="modal fade" id="modal{{ $equipement->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title d-flex align-items-center">
                                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                                    Signaler un problème - {{ $equipement->des_equipement }}
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <form class="ui form" method="POST" action="/demandeMaintenance/traitement">
                                                    @csrf

                                                    <!-- Informations utilisateur (cachées) -->
                                                    <div class="hidden-fields" style="display: none;">
                                                        <input type="text" name="nom_dmd" value="{{ Auth::user()->nom }}" readonly>
                                                        <input type="text" name="contact_dmd" value="{{ Auth::user()->contact }}" readonly>
                                                        <input type="email" name="email_dmd" value="{{ Auth::user()->email }}" readonly>
                                                    </div>

                                                    <div class="row mb-4" style="display: none;">
                                                        <div class="col-md-4">
                                                            <div class="field">
                                                                <label class="form-label fw-medium">Équipement concerné</label>
                                                                <input type="text" value="{{ $equipement->des_equipement }}" class="form-control bg-light" readonly>
                                                                <input type="hidden" name="equipement_id" value="{{ $equipement->id }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="field">
                                                                <label class="form-label fw-medium">Type du matériel</label>
                                                                <input type="text" name="type_mat" value="{{ $equipement->categorie }}" class="form-control bg-light" readonly>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="field">
                                                                <label class="form-label fw-medium">Numéro de série</label>
                                                                <input type="text" name="num_serie" value="{{ $equipement->numero_serie }}" class="form-control bg-light" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="field">
                                                                <label class="form-label fw-medium">Marque</label>
                                                                <input type="text" name="marque_mat" value="{{ $equipement->marque }}" class="form-control bg-light" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="field">
                                                                <label class="form-label fw-medium">Modèle</label>
                                                                <input type="text" name="modele_mat" value="{{ $equipement->modele }}" class="form-control bg-light" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                
                                                    <!-- Informations équipement en vue -->
                                                    <div class="card bg-light mb-4">
                                                        <div class="card-body">
                                                            <h6 class="card-title mb-3">
                                                                <i class="fas fa-laptop me-2 text-primary"></i>Informations sur l'équipement
                                                            </h6>
                                                            <div class="row">
                                                                <div class="col-md-6 mb-2">
                                                                    <span class="text-muted">Désignation :</span>
                                                                    <span class="fw-medium ms-2">{{ $equipement->des_equipement }}</span>
                                                                </div>
                                                                <div class="col-md-6 mb-2">
                                                                    <span class="text-muted">Numéro de série :</span>
                                                                    <span class="fw-medium ms-2">{{ $equipement->numero_serie }}</span>
                                                                </div>
                                                                <div class="col-md-6 mb-2">
                                                                    <span class="text-muted">Marque :</span>
                                                                    <span class="fw-medium ms-2">{{ $equipement->marque }}</span>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <span class="text-muted">Modèle :</span>
                                                                    <span class="fw-medium ms-2">{{ $equipement->modele }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Section : Détails du problème -->
                                                    <h5 class="mb-3 pb-2 border-bottom">
                                                        <i class="fas fa-exclamation-triangle me-2 text-danger"></i>
                                                        Détails du problème
                                                    </h5>
                                                    
                                                    <div class="row mb-3">
                                                       
                                                        <!---<div class="col-md-6 mb-3">
                                                            <div class="field @error('type_mat') error @enderror">
                                                                <label class="form-label fw-medium">Type de matériel</label>
                                                                <select class="form-select" name="type_mat">
                                                                    <option value="">Sélectionnez un type</option>
                                                                    <option value="ordinateur" {{ $equipement->categorie == 'Ordinateur' ? 'selected' : '' }}>Ordinateur</option>
                                                                    <option value="imprimante" {{ $equipement->categorie == 'Imprimante' ? 'selected' : '' }}>Imprimante</option>
                                                                    <option value="scanner" {{ $equipement->categorie == 'Scanner' ? 'selected' : '' }}>Scanner</option>
                                                                    <option value="autre" {{ !in_array($equipement->categorie, ['Ordinateur', 'Imprimante', 'Scanner']) ? 'selected' : '' }}>Autre</option>
                                                                </select>
                                                                @error('type_mat')
                                                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>-->
                                                        <div class="col-md-6 mb-3">
                                                            <div class="field">
                                                                <label class="form-label fw-medium">Priorité</label>
                                                                <select class="form-select" name="priorite_dmtc" required>
                                                                    <option value="">Sélectionnez une priorité</option>
                                                                    <option value="faible">Faible</option>
                                                                    <option value="moyen">Moyen</option>
                                                                    <option value="urgent">Urgent</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <!--<div class="col-md-6 mb-3">
                                                            <div class="field">
                                                                <label class="form-label fw-medium">Date de signalement</label>
                                                                <input type="date" name="date_signalement" class="form-control" value="{{ date('Y-m-d') }}" required>
                                                            </div>
                                                        </div>-->
                                                    </div>
                                                    
                                                    <div class="field mb-3">
                                                        <label class="form-label fw-medium">Description du problème</label>
                                                        <textarea name="desc_prblem" class="form-control" rows="4" placeholder="Décrivez précisément le problème rencontré..." required></textarea>
                                                    </div>
                                                    <div class="modal-footer border-0 mt-4 px-0">
                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                            <i class="fas fa-times me-2"></i>Annuler
                                                        </button>
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="fas fa-paper-plane me-2"></i>Envoyer le signalement
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
       
    @endif
</div>

<style>
/* Styles personnalisés */
.fade-in {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    padding-bottom: 25px;
    padding-left: 20px;
}

.timeline-item:before {
    content: '';
    position: absolute;
    left: -3px;
    top: 30px;
    bottom: 0;
    width: 2px;
    background-color: #e9ecef;
}

.timeline-item:last-child:before {
    display: none;
}

.timeline-indicator {
    position: absolute;
    left: -12px;
    top: 0;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    z-index: 1;
}

.timeline-content {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
}

.btn-action {
    font-size: 12px;
    padding: 5px 12px;
    border-radius: 4px;
}

.bg-light-blue {
    background-color: #1976D2;
}

.btn-light-blue {
    background-color: #1976D2;
    color: white;
}

.btn-light-blue:hover {
    background-color: #1565C0;
    color: white;
}

table {
    width: 100%;
    border-collapse: collapse;
}

table th {
    background-color: #f8f9fa;
    font-weight: 600;
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #e9ecef;
}

table td {
    padding: 12px 15px;
    border-bottom: 1px solid #e9ecef;
}

table tbody tr:hover {
    background-color: #f8f9fa;
}

.empty-state {
    padding: 60px 20px;
    text-align: center;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialiser les tooltips Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    
    // Initialiser les modals Bootstrap
    var modals = document.querySelectorAll('.modal');
    modals.forEach(function (modal) {
        new bootstrap.Modal(modal);
    });
    
    // Toggle sidebar
    document.getElementById('sidebar-toggle').addEventListener('click', function () {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('expanded');
    });
    
    // Fonction de recherche d'équipements
    const searchEquipment = document.getElementById('searchEquipment');
    if (searchEquipment) {
        searchEquipment.addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const equipmentRows = document.querySelectorAll('.equipment-row');
            
            equipmentRows.forEach(row => {
                const content = row.textContent.toLowerCase();
                if (content.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
    
    // Filtre par catégorie
    const filterCategory = document.getElementById('filterCategory');
    if (filterCategory) {
        filterCategory.addEventListener('change', function() {
            const categoryValue = this.value.toLowerCase();
            const equipmentRows = document.querySelectorAll('.equipment-row');
            
            equipmentRows.forEach(row => {
                if (categoryValue === '') {
                    row.style.display = '';
                    return;
                }
                
                const categoryCell = row.querySelector('td:nth-child(2)');
                const categoryText = categoryCell.textContent.toLowerCase();
                
                if (categoryText.includes(categoryValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
    
    // Filtre par statut
    const filterStatus = document.getElementById('filterStatus');
    if (filterStatus) {
        filterStatus.addEventListener('change', function() {
            const statusValue = this.value.toLowerCase();
            const equipmentRows = document.querySelectorAll('.equipment-row');
            
            equipmentRows.forEach(row => {
                if (statusValue === '') {
                    row.style.display = '';
                    return;
                }
                
                const statusText = row.querySelector('td:nth-child(8)').textContent.toLowerCase();
                
                if (statusValue === 'confirmed' && statusText.includes('confirmé')) {
                    row.style.display = '';
                } else if (statusValue === 'pending' && statusText.includes('attente')) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});
</script>
@endsection