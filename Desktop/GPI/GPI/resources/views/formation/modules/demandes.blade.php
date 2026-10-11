@extends('layouts.main-board')
@section('title', 'Formation — Demandes')
@section('content')
@php $currentSlug = 'demandes'; @endphp

<div class="py-4 formation-page">

    <style>
        .formation-page{
            --primary-color:#6f42c1;
            --primary-light:rgba(111,66,193,0.10);
            --primary-border:rgba(111,66,193,0.18);
            --text-dark:#1f2937;
        }

        .formation-page .module-hero{
            background: linear-gradient(135deg,#ffffff 0%, #f6f2ff 100%);
            border:1px solid #ebe5ff;
            border-radius:10px;
            padding:24px;
            margin-bottom:28px;
        }

        .formation-page .module-icon{
            width:60px;
            height:60px;
            border-radius:10px;
            background:var(--primary-light);
            color:var(--primary-color);
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:1.5rem;
            flex-shrink:0;
        }

        .formation-page .module-title{
            font-size:1.9rem;
            font-weight:700;
            color:var(--text-dark);
            margin-bottom:4px;
        }

        .formation-page .module-subtitle{
            color:#6b7280;
            margin-bottom:0;
        }

        .formation-page .module-card{
            border:none;
            border-radius:10px;
            overflow:hidden;
            box-shadow:0 2px 12px rgba(15,23,42,0.06);
            margin-bottom:24px;
        }

        .formation-page .module-card-header{
            background:var(--primary-color);
            color:white;
            padding:16px 20px;
            font-weight:600;
            font-size:1rem;
            display:flex;
            align-items:center;
            gap:10px;
        }

        .formation-page .module-card-body{
            padding:24px;
            background:white;
        }

        .formation-page .soft-card{
            border:1px solid #ece8f8;
            border-radius:10px;
            background:#faf8ff;
            padding:18px;
            height:100%;
            transition:0.2s ease;
        }

        .formation-page .soft-card:hover{
            transform:translateY(-2px);
            box-shadow:0 6px 18px rgba(111,66,193,0.08);
        }

        .formation-page .status-badge{
            padding:8px 14px;
            border-radius:8px;
            font-weight:600;
            font-size:.82rem;
        }

        .formation-page .section-title{
            font-size:1rem;
            font-weight:700;
            color:#111827;
            margin-bottom:14px;
        }

        .formation-page .workflow-step{
            min-width:150px;
            border-radius:10px;
            padding:16px;
            border:1px solid #ece8f8;
            background:#faf8ff;
        }

        .formation-page .workflow-step i{
            font-size:1.6rem;
            margin-bottom:10px;
            color:var(--primary-color);
        }

        .formation-page .table{
            border-color:#f0edf7;
        }

        .formation-page .table thead{
            background:#f6f2ff;
        }

        .formation-page .table thead th{
            border:none;
            color:#4b5563;
            font-weight:600;
            font-size:.92rem;
        }

        .formation-page .table td{
            vertical-align:middle;
            border-color:#f3f0fa;
        }

        .formation-page .capture-box{
            border:1px dashed #d6c9f7;
            border-radius:10px;
            background:#faf8ff;
            padding:32px 20px;
            text-align:center;
        }

        .formation-page .capture-box i{
            font-size:2.5rem;
            color:#b7a3ea;
        }

        .formation-page .capture-box p{
            margin-top:12px;
            margin-bottom:0;
            color:#7c8595;
            font-size:.88rem;
        }

        .formation-page .custom-alert{
            border:none;
            border-left:4px solid var(--primary-color);
            border-radius:10px;
            background:#f6f2ff;
            padding:16px;
        }

        .formation-page ol li,
        .formation-page ul li{
            margin-bottom:10px;
        }

        .formation-page .arrow-icon{
            color:#b0b7c3;
            font-size:1.4rem;
        }
    </style>

    @include('formation.partials.nav')

    {{-- HERO --}}
    <div class="module-hero d-flex align-items-center gap-3">
        <div class="module-icon">
            <i class="bi bi-envelope-fill"></i>
        </div>

        <div>
            <h1 class="module-title">Demandes</h1>
            <p class="module-subtitle">
                Soumettre, suivre et gérer les demandes de matériel et de maintenance.
            </p>
        </div>
    </div>

    {{-- WORKFLOW --}}
    <div class="module-card">
        <div class="module-card-header">
            <i class="bi bi-diagram-3"></i>
            Circuit d'approbation
        </div>

        <div class="module-card-body">

            <p class="text-muted mb-4">
                Chaque demande suit un processus de validation structuré afin d'assurer un meilleur suivi.
            </p>

            <div class="d-flex flex-wrap justify-content-center align-items-center gap-2">

                <div class="workflow-step text-center">
                    <i class="bi bi-person"></i>
                    <div class="fw-semibold">Utilisateur</div>
                    <small class="text-muted">Soumet la demande</small>
                </div>

                <i class="bi bi-arrow-right arrow-icon"></i>

                <div class="workflow-step text-center">
                    <i class="bi bi-person-badge"></i>
                    <div class="fw-semibold">Chef de service</div>
                    <small class="text-muted">Approuve ou rejette</small>
                </div>

                <i class="bi bi-arrow-right arrow-icon"></i>

                <div class="workflow-step text-center">
                    <i class="bi bi-person-gear"></i>
                    <div class="fw-semibold">Admin</div>
                    <small class="text-muted">Validation finale</small>
                </div>

                <i class="bi bi-arrow-right arrow-icon"></i>

                <div class="workflow-step text-center">
                    <i class="bi bi-check-circle"></i>
                    <div class="fw-semibold">Traitement</div>
                    <small class="text-muted">Exécution</small>
                </div>

            </div>

            <div class="capture-box mt-4">
                <i class="bi bi-image"></i>
                <p>[ Capture d'écran : Circuit d'approbation ]</p>
            </div>

        </div>
    </div>

    {{-- DEMANDE MATERIEL --}}
    <div class="module-card">
        <div class="module-card-header">
            <i class="bi bi-1-circle"></i>
            Demande de matériel
        </div>

        <div class="module-card-body">

            <div class="row g-4">

                <div class="col-lg-7">
                    <h6 class="section-title">Créer une demande</h6>

                    <ol class="ps-3">
                        <li>Accédez à <strong>Demandes → Demandes Matériels</strong>.</li>
                        <li>
                            Cliquez sur
                            <span class="badge text-white px-3 py-2" style="background:#6f42c1;">
                                <i class="bi bi-plus-circle me-1"></i>Nouvelle demande
                            </span>
                        </li>
                        <li>
                            Renseignez :
                            <ul class="mt-2">
                                <li>Le type de matériel souhaité</li>
                                <li>La quantité</li>
                                <li>La justification</li>
                                <li>Le niveau de priorité</li>
                            </ul>
                        </li>
                        <li>Soumettez la demande pour validation.</li>
                    </ol>
                </div>

                <div class="col-lg-5">
                    <div class="soft-card">
                        <h6 class="fw-bold mb-3">Statuts possibles</h6>

                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-warning text-dark status-badge">En attente</span>
                            <span class="badge bg-info text-dark status-badge">Approuvée chef</span>
                            <span class="badge bg-primary status-badge">Approuvée admin</span>
                            <span class="badge bg-success status-badge">Traitée</span>
                            <span class="badge bg-danger status-badge">Rejetée</span>
                            <span class="badge bg-secondary status-badge">Annulée</span>
                        </div>

                        <div class="custom-alert mt-4">
                            <div class="d-flex gap-2">
                                <i class="bi bi-info-circle-fill text-primary mt-1"></i>
                                <div class="small">
                                    Les demandes urgentes sont mises en priorité dans le tableau de traitement.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="capture-box mt-4">
                <i class="bi bi-image"></i>
                <p>[ Capture d'écran : Formulaire demande matériel ]</p>
            </div>

        </div>
    </div>

    {{-- MAINTENANCE --}}
    <div class="module-card">
        <div class="module-card-header">
            <i class="bi bi-2-circle"></i>
            Demande de maintenance
        </div>

        <div class="module-card-body">

            <div class="row g-4">

                <div class="col-lg-6">

                    <h6 class="section-title">Soumettre une demande</h6>

                    <ol class="ps-3">
                        <li>Accédez à <strong>Demandes → Demandes Maintenances</strong>.</li>
                        <li>
                            Cliquez sur
                            <span class="badge text-white px-3 py-2" style="background:#6f42c1;">
                                <i class="bi bi-plus-circle me-1"></i>Nouvelle demande
                            </span>
                        </li>
                        <li>
                            Complétez les informations :
                            <ul class="mt-2">
                                <li>Équipement concerné</li>
                                <li>Description du problème</li>
                                <li>Type de maintenance</li>
                                <li>Priorité</li>
                            </ul>
                        </li>
                        <li>Envoyez la demande.</li>
                    </ol>

                </div>

                <div class="col-lg-6">

                    <div class="soft-card">
                        <h6 class="fw-bold mb-3">Types de maintenance</h6>

                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span class="badge bg-success">Préventive</span></td>
                                        <td>Prévenir les pannes</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-primary">Corrective</span></td>
                                        <td>Réparer une panne</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge text-white" style="background:#6f42c1;">Curative</span></td>
                                        <td>Résoudre les problèmes récurrents</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-warning text-dark">Palliative</span></td>
                                        <td>Solution temporaire</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>

            </div>

            <div class="capture-box mt-4">
                <i class="bi bi-image"></i>
                <p>[ Capture d'écran : Formulaire demande maintenance ]</p>
            </div>

        </div>
    </div>

    {{-- MES DEMANDES --}}
    <div class="module-card">
        <div class="module-card-header">
            <i class="bi bi-3-circle"></i>
            Suivre mes demandes
        </div>

        <div class="module-card-body">

            <div class="row g-3">

                <div class="col-md-4">
                    <div class="soft-card text-center">
                        <i class="bi bi-clock-history fs-2 mb-3" style="color:#6f42c1;"></i>
                        <h6 class="fw-bold">Suivi en temps réel</h6>
                        <p class="small text-muted mb-0">
                            Consultez les évolutions et validations de vos demandes.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="soft-card text-center">
                        <i class="bi bi-x-circle fs-2 mb-3 text-danger"></i>
                        <h6 class="fw-bold">Annulation</h6>
                        <p class="small text-muted mb-0">
                            Une demande en attente peut être annulée à tout moment.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="soft-card text-center">
                        <i class="bi bi-bell fs-2 mb-3 text-warning"></i>
                        <h6 class="fw-bold">Notifications</h6>
                        <p class="small text-muted mb-0">
                            Recevez une alerte à chaque changement de statut.
                        </p>
                    </div>
                </div>

            </div>

            <div class="capture-box mt-4">
                <i class="bi bi-image"></i>
                <p>[ Capture d'écran : Mes demandes en cours ]</p>
            </div>

        </div>
    </div>

    {{-- TRANSFERT --}}
    <div class="module-card">
        <div class="module-card-header">
            <i class="bi bi-4-circle"></i>
            Transférer une demande
        </div>

        <div class="module-card-body">

            <div class="custom-alert mb-4">
                <div class="d-flex gap-2">
                    <i class="bi bi-arrow-left-right text-primary mt-1"></i>
                    <div>
                        Les administrateurs peuvent transférer une demande vers une autre direction si une expertise spécifique est nécessaire.
                    </div>
                </div>
            </div>

            <ol class="ps-3">
                <li>Ouvrez la demande concernée.</li>
                <li>
                    Cliquez sur
                    <span class="badge bg-secondary px-3 py-2">
                        <i class="bi bi-arrow-right me-1"></i>Transférer
                    </span>
                </li>
                <li>Sélectionnez la direction destinataire.</li>
                <li>Ajoutez le motif du transfert.</li>
                <li>Validez pour notifier la direction cible.</li>
            </ol>

            <div class="capture-box mt-4">
                <i class="bi bi-image"></i>
                <p>[ Capture d'écran : Transfert de demande ]</p>
            </div>

        </div>
    </div>

    @include('formation.partials.module-footer', [
        'prev' => ['slug'=>'inventaire','titre'=>'Inventaire du parc'],
        'next' => ['slug'=>'activites','titre'=>'Activités & historique']
    ])

</div>
@endsection