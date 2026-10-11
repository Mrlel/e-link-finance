@extends('layouts.user-section')
@section('content')

<!-- Hero Section -->
<div class="card shadow-sm mb-4 border-0">
    <div class="card-body d-flex flex-column flex-md-row align-items-center">
        <div class="w-100 w-md-75 mb-4 mb-md-0">
            <h2 class="text-warning fw-bold mb-3">
                <i class="fas fa-book me-2"></i> Guide d'utilisation de l'application
            </h2>
            <p class="text-success mb-4">Ce guide vous aide à naviguer dans l'application.</p>
            <button class="btn btn-success">
                <i class="fas fa-play me-2"></i> Voir la vidéo de démonstration
            </button>
        </div>

        <div class="w-100 w-md-25 text-center">
            <img src="https://cdn-icons-png.flaticon.com/512/2933/2933245.png"
                 class="img-fluid animate__animated animate__bounce"
                 style="max-height: 130px;">
        </div>
    </div>
</div>

<!-- Overview Section -->
<div class="card shadow-sm mb-4 border-0">
    <div class="card-header bg-warning text-white d-flex align-items-center">
        <i class="fas fa-globe me-2 fs-4"></i>
        <h5 class="mb-0">Vue d'ensemble du tableau de bord</h5>
    </div>

    <div class="card-body">
        <p class="text-muted">Votre tableau de bord contient :</p>

        <ul class="list-unstyled">
            <li class="d-flex">
                <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                <div>
                    <strong>Cartes statistiques</strong> :
                    <ul class="mt-2">
                        <li class="d-flex mb-2">
                            <i class="fas fa-laptop text-success me-2 mt-1"></i>
                            <span><strong class="text-success">Mes équipements</strong> : Liste des équipements attribués</span>
                        </li>
                        <li class="d-flex mb-2">
                            <i class="fas fa-tools text-danger me-2 mt-1"></i>
                            <span><strong class="text-danger">Demandes de maintenance</strong> : Total des demandes</span>
                        </li>
                        <li class="d-flex">
                            <i class="fas fa-box text-secondary me-2 mt-1"></i>
                            <span><strong class="text-secondary">Demandes de matériel</strong> : Total</span>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>

        <div class="row g-3 mt-4">
            <div class="col-md-4">
                <div class="p-3 bg-success bg-opacity-10 rounded border border-success">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-success small mb-1">Mes équipements</p>
                            <h3 class="fw-bold text-success">12</h3>
                        </div>
                        <i class="fas fa-computer fs-3 text-success"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="p-3 bg-danger bg-opacity-10 rounded border border-danger">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-danger small mb-1">Demandes maintenance</p>
                            <h3 class="fw-bold text-danger">3</h3>
                        </div>
                        <i class="fas fa-cog fs-3 text-danger"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="p-3 bg-secondary bg-opacity-10 rounded border border-secondary">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-secondary small mb-1">Demandes matériel</p>
                            <h3 class="fw-bold text-secondary">2</h3>
                        </div>
                        <i class="fas fa-list fs-3 text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Demandes de Matériel -->
<div class="card shadow-sm mb-4 border-0">
    <div class="card-header bg-success text-white d-flex align-items-center">
        <i class="fas fa-boxes me-2 fs-4"></i>
        <h5 class="mb-0">Demandes de matériel</h5>
    </div>

    <div class="card-body">
        <h5 class="text-muted mb-3">
            <i class="fas fa-question-circle text-primary me-2"></i>
            Comment faire une demande ?
        </h5>

        <ol class="ps-3">
            <li class="mb-3">
                Cliquez sur :
                <div class="mt-2 text-center">
                    <button class="btn btn-success">
                        <i class="fas fa-plus-circle me-2"></i> Nouvelle demande de matériel
                    </button>
                </div>
            </li>

            <li>
                Remplissez le formulaire :
                <ul class="mt-2">
                    <li class="d-flex mb-2">
                        <i class="fas fa-list text-primary me-2"></i>
                        Type de matériel
                    </li>
                    <li class="d-flex mb-2">
                        <i class="fas fa-exclamation text-primary me-2"></i>
                        Priorité de la demande
                    </li>
                    <li class="d-flex">
                        <i class="fas fa-align-left text-primary me-2"></i>
                        Description détaillée
                    </li>
                </ul>
            </li>
        </ol>

        <h5 class="mt-4 text-muted mb-3">
            <i class="fas fa-search text-primary me-2"></i>
            Suivi des demandes
        </h5>

        <div class="row g-3">
            <div class="col-md-3">
                <div class="p-3 bg-warning bg-opacity-10 border border-warning rounded">
                    <span class="badge bg-warning text-dark">En attente</span>
                    <p class="small mt-2 text-muted">Demande en cours de traitement</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="p-3 bg-success bg-opacity-10 border border-success rounded">
                    <span class="badge bg-success">Traité</span>
                    <p class="small mt-2 text-muted">Validée</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="p-3 bg-danger bg-opacity-10 border border-danger rounded">
                    <span class="badge bg-danger">Rejeté</span>
                    <p class="small mt-2 text-muted">Non approuvée</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Demandes de Maintenance -->
<div class="card shadow-sm mb-4 border-0">
    <div class="card-header bg-warning text-white d-flex align-items-center">
        <i class="fas fa-tools me-2 fs-4"></i>
        <h5 class="mb-0">Demandes de maintenance</h5>
    </div>

    <div class="card-body">
        <h5 class="text-muted mb-3">
            <i class="fas fa-list text-success me-2"></i>
            Types de maintenance
        </h5>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Quand l'utiliser ?</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="badge bg-success">Préventive</span></td>
                        <td>Prévenir les pannes</td>
                        <td>En cas d’usure</td>
                    </tr>
                    <tr>
                        <td><span class="badge bg-primary">Corrective</span></td>
                        <td>Réparer une panne</td>
                        <td>Quand l’équipement fonctionne mal</td>
                    </tr>
                    <tr>
                        <td><span class="badge bg-purple bg-opacity-50 text-dark">Curative</span></td>
                        <td>Réparer un problème récurrent</td>
                        <td>Pannes répétitives</td>
                    </tr>
                    <tr>
                        <td><span class="badge bg-warning text-dark">Palliative</span></td>
                        <td>Solution temporaire</td>
                        <td>Réparation impossible immédiatement</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h5 class="text-muted mt-4 mb-3">
            <i class="fas fa-question-circle text-success me-2"></i>
            Faire une demande ?
        </h5>

        <ol class="ps-3">
            <li class="mb-2">
                Cliquez sur le bouton <i class="fas fa-info-circle text-danger"></i>
            </li>
            <li class="mb-2">Vos informations sont pré-remplies</li>
            <li>
                Décrivez le problème et choisissez :
                <ul class="mt-2">
                    <li><i class="fas fa-comment-alt text-success me-2"></i>Description</li>
                    <li><i class="fas fa-tools text-success me-2"></i>Type de maintenance</li>
                    <li><i class="fas fa-exclamation-triangle text-success me-2"></i>Priorité</li>
                </ul>
            </li>
        </ol>
    </div>
</div>


@endsection
