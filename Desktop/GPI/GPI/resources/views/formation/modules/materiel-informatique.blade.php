@extends('layouts.main-board')

@section('title', 'Formation — Matériel informatique')

@section('content')

@php $currentSlug = 'materiel-informatique'; @endphp

<style>
    :root{
        --wm-warning: #f4b400;
        --wm-warning-soft: rgba(244,180,0,.12);
        --wm-border: #ececec;
        --wm-text: #1f2937;
        --wm-muted: #6b7280;
        --wm-card: #ffffff;
        --wm-bg: #f8fafc;
    }

    .formation-wrapper{
        max-width: 1180px;
        margin: auto;
    }

    .hero-section{
        background: linear-gradient(135deg, #fff8e7 0%, #ffffff 100%);
        border: 1px solid #f3e2a5;
        border-radius: 28px;
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .hero-section::after{
        content: "";
        position: absolute;
        width: 240px;
        height: 240px;
        background: rgba(244,180,0,.08);
        border-radius: 50%;
        top: -120px;
        right: -60px;
    }

    .hero-icon{
        width: 72px;
        height: 72px;
        border-radius: 22px;
        background: var(--wm-warning-soft);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .hero-icon i{
        font-size: 2rem;
        color: var(--wm-warning);
    }

    .hero-title{
        font-size: 2rem;
        font-weight: 800;
        color: var(--wm-text);
        margin-bottom: .4rem;
    }

    .hero-text{
        color: var(--wm-muted);
        max-width: 700px;
        line-height: 1.7;
        margin-bottom: 0;
    }

    .step-card{
        border: none;
        border-radius: 24px;
        overflow: hidden;
        margin-bottom: 2rem;
        background: var(--wm-card);
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
    }

    .step-header{
        padding: 1.1rem 1.4rem;
        background: #fff;
        border-bottom: 1px solid var(--wm-border);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .step-number{
        width: 42px;
        height: 42px;
        border-radius: 14px;
        background: var(--wm-warning-soft);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--wm-warning);
        font-weight: 700;
        flex-shrink: 0;
    }

    .step-title{
        font-size: 1.05rem;
        font-weight: 700;
        margin-bottom: 0;
        color: var(--wm-text);
    }

    .step-body{
        padding: 1.5rem;
    }

    .soft-card{
        background: #f9fafb;
        border: 1px solid #edf0f2;
        border-radius: 18px;
        padding: 1.2rem;
        height: 100%;
    }

    .soft-card h6{
        font-weight: 700;
        margin-bottom: 1rem;
        color: #111827;
    }

    .soft-card ul{
        padding-left: 1rem;
        margin-bottom: 0;
    }

    .soft-card li{
        margin-bottom: .65rem;
        color: var(--wm-muted);
        line-height: 1.5;
    }

    .soft-card li:last-child{
        margin-bottom: 0;
    }

    .info-box{
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 18px;
        padding: 1rem 1.1rem;
        display: flex;
        gap: .9rem;
        margin-top: 1.3rem;
    }

    .info-box i{
        color: #2563eb;
        font-size: 1.1rem;
        margin-top: 2px;
    }

    .info-box p{
        margin-bottom: 0;
        color: #1e3a8a;
        line-height: 1.6;
    }

    .screen-placeholder{
        border: 2px dashed #d1d5db;
        border-radius: 20px;
        background: #fafafa;
        padding: 3rem 1rem;
        text-align: center;
        margin-top: 1.5rem;
    }

    .screen-placeholder i{
        font-size: 3rem;
        color: #c4c4c4;
    }

    .screen-placeholder p{
        margin-top: .8rem;
        margin-bottom: 0;
        color: #9ca3af;
        font-size: .92rem;
    }

    .modern-list{
        padding-left: 1rem;
        margin-bottom: 0;
    }

    .modern-list li{
        margin-bottom: 1rem;
        color: #4b5563;
        line-height: 1.7;
    }

    .modern-list li:last-child{
        margin-bottom: 0;
    }

    .feature-box{
        border-radius: 22px;
        padding: 1.5rem;
        height: 100%;
        background: #fff;
        border: 1px solid #edf0f2;
        transition: .25s ease;
    }

    .feature-box:hover{
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0,0,0,.06);
    }

    .feature-icon{
        width: 60px;
        height: 60px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .feature-box h6{
        font-weight: 700;
        margin-bottom: .6rem;
    }

    .feature-box p{
        margin-bottom: 0;
        color: var(--wm-muted);
        line-height: 1.6;
        font-size: .94rem;
    }

    .maintenance .feature-icon{
        background: rgba(14,165,233,.12);
        color: #0284c7;
    }

    .reforme .feature-icon{
        background: rgba(239,68,68,.12);
        color: #dc2626;
    }

    .transfert .feature-icon{
        background: rgba(59,130,246,.12);
        color: #2563eb;
    }

    .badge-modern{
        padding: .45rem .75rem;
        border-radius: 999px;
        font-weight: 600;
        font-size: .75rem;
    }

    @media (max-width: 768px){

        .hero-section{
            padding: 1.5rem;
        }

        .hero-title{
            font-size: 1.5rem;
        }

        .step-body{
            padding: 1.2rem;
        }
    }
</style>

<div class="formation-wrapper py-4">

    @include('formation.partials.nav')

    {{-- HERO --}}
    <div class="hero-section">
        <div class="d-flex align-items-start gap-3">
            <div class="hero-icon">
                <i class="bi bi-pc-display-horizontal"></i>
            </div>

            <div>
                <h1 class="hero-title">Matériel informatique</h1>

                <p class="hero-text">
                    Apprenez à gérer efficacement le cycle de vie des équipements informatiques :
                    ajout au stock, affectation aux utilisateurs, retours, maintenance,
                    transferts et suivi des alertes de fin de vie.
                </p>
            </div>
        </div>
    </div>

    {{-- 1 --}}
    <div class="step-card">

        <div class="step-header">
            <div class="step-number">01</div>
            <h2 class="step-title">Ajouter un équipement</h2>
        </div>

        <div class="step-body">

            <p class="text-muted mb-4">
                Accédez à <strong>Patrimoine → Matériel Informatique</strong>
                puis cliquez sur le bouton
                <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                    <i class="bi bi-plus-circle me-1"></i> Ajouter
                </span>
            </p>

            <div class="row g-4">

                <div class="col-md-6">
                    <div class="soft-card">
                        <h6>Informations générales</h6>

                        <ul>
                            <li><strong>Désignation</strong> — nom descriptif de l’équipement</li>
                            <li><strong>Catégorie</strong> — ordinateur, imprimante, switch…</li>
                            <li><strong>Nature</strong> — informatique, réseau, multimédia…</li>
                            <li><strong>Marque / Modèle</strong></li>
                            <li><strong>Numéro de série</strong> — unique et obligatoire</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="soft-card">
                        <h6>Inventaire & durée de vie</h6>

                        <ul>
                            <li><strong>Date d’acquisition</strong></li>
                            <li><strong>Date de fin de vie</strong> avec alerte automatique</li>
                            <li><strong>Mode d’acquisition</strong> — État, Bailleur, Autre</li>
                            <li><strong>État</strong> — bon, moyen ou hors service</li>
                            <li><strong>Spécifications</strong> — RAM, disque, OS, processeur…</li>
                        </ul>
                    </div>
                </div>

            </div>

            <div class="info-box">
                <i class="bi bi-info-circle-fill"></i>

                <p>
                    Un <strong>QR Code</strong> est automatiquement généré après l’enregistrement
                    afin de faciliter l’identification et l’inventaire du matériel.
                </p>
            </div>

            <div class="screen-placeholder">
                 <img src="/formation/add_equipement.jpeg"
                 height="860"
                     alt="Ajouter un equipement">
            </div>

        </div>
    </div>

    {{-- 2 --}}
    <div class="step-card">

        <div class="step-header">
            <div class="step-number">02</div>
            <h2 class="step-title">Affecter un équipement</h2>
        </div>

        <div class="step-body">

            <ol class="modern-list">
                <li>Depuis le stock, cliquez sur le bouton <span class="badge bg-success rounded-pill px-3 py-2"><i class="bi bi-person-check me-1"></i>Affecter</span>.</li>

                <li>Sélectionnez l’utilisateur via la recherche par nom ou matricule.</li>

                <li>Confirmez l’opération d’affectation.</li>

                <li>
                    L’équipement passe automatiquement au statut
                    <span class="badge bg-primary rounded-pill px-3 py-2">En service</span>
                    et l’utilisateur reçoit une notification.
                </li>
            </ol>

            <div class="screen-placeholder">
                <img src="/formation/attrib-equipement.png"
                height="360"
                     alt="Actions utilisateur">
            </div>

        </div>
    </div>

    {{-- 3 --}}
    <div class="step-card">

        <div class="step-header">
            <div class="step-number">03</div>
            <h2 class="step-title">Retourner un équipement</h2>
        </div>

        <div class="step-body">

            <ol class="modern-list">
                <li>Accédez à la fiche de l’équipement ou à l’historique des affectations.</li>

                <li>
                    Cliquez sur
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                        <i class="bi bi-arrow-return-left me-1"></i>Retourner
                    </span>
                </li>

                <li>Renseignez l’état de retour ainsi qu’un commentaire optionnel.</li>

                <li>
                    L’équipement repasse automatiquement au statut
                    <span class="badge bg-success rounded-pill px-3 py-2">En stock</span>
                </li>
            </ol>

            <div class="screen-placeholder">
                 <img src="/formation/retrait-equipement.png"
                      height="360"
                     alt="Actions utilisateur">
            </div>

        </div>
    </div>

    {{-- 4 --}}
    <div class="step-card">

        <div class="step-header">
            <div class="step-number">04</div>
            <h2 class="step-title">Gestion des sorties</h2>
        </div>

        <div class="step-body">

            <p class="text-muted mb-4">
                Une sortie permet de tracer le départ temporaire ou définitif d’un équipement.
            </p>

            <div class="row g-4 mb-4">

                <div class="col-md-4">
                    <div class="feature-box maintenance">
                        <div class="feature-icon">
                            <i class="bi bi-tools fs-4"></i>
                        </div>

                        <h6>Maintenance externe</h6>

                        <p>
                            L’équipement est envoyé chez un prestataire externe.
                            Le retour du matériel est attendu après intervention.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-box reforme">
                        <div class="feature-icon">
                            <i class="bi bi-trash fs-4"></i>
                        </div>

                        <h6>Réforme / Enlèvement</h6>

                        <p>
                            Retrait définitif du parc informatique avec archivage
                            de l’historique du matériel.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-box transfert">
                        <div class="feature-icon">
                            <i class="bi bi-box-arrow-right fs-4"></i>
                        </div>

                        <h6>Transfert</h6>

                        <p>
                            Déplacement du matériel vers une autre direction
                            ou un autre service.
                        </p>
                    </div>
                </div>

            </div>

            <h6 class="fw-bold mb-3">Procédure :</h6>

            <ol class="modern-list">
                <li>Ouvrez la fiche du matériel concerné.</li>

                <li>
                    Cliquez sur
                    <span class="badge bg-danger rounded-pill px-3 py-2">
                        <i class="bi bi-box-arrow-right me-1"></i>Enregistrer une sortie
                    </span>
                </li>

                <li>Choisissez le type de sortie puis renseignez les informations nécessaires.</li>

                <li>Validez pour mettre à jour automatiquement le statut du matériel.</li>
            </ol>

            <div class="screen-placeholder">
                <img src="/formation/sortie-equipement.jpeg"
                     height="560"
                     alt="formulaire de sortie d'un equipement">
            </div>

        </div>
    </div>

    {{-- 5 --}}
    <div class="step-card">

        <div class="step-header">
            <div class="step-number">05</div>
            <h2 class="step-title">Alertes de fin de durée de vie</h2>
        </div>

        <div class="step-body">

            <p class="text-muted">
                Le système surveille automatiquement les équipements dont la date
                de fin de vie approche.
            </p>

            <div class="d-flex flex-wrap gap-2 my-4">

                <span class="badge bg-success badge-modern">Plus de 30 jours</span>

                <span class="badge bg-warning text-dark badge-modern">
                    Moins de 30 jours
                </span>

                <span class="badge bg-danger badge-modern">
                    Durée dépassée
                </span>

            </div>

            <ul class="modern-list">
                <li>Une notification est envoyée 30 jours avant expiration.</li>

                <li>Les badges permettent d’identifier rapidement les équipements critiques.</li>

                <li>La date de fin de vie peut être modifiée depuis la fiche équipement.</li>
            </ul>

            <div class="screen-placeholder">
                <i class="bi bi-image"></i>
                <p>[ Capture d’écran : Badge fin de vie ]</p>
            </div>

        </div>
    </div>

    @include('formation.partials.module-footer', [
        'prev' => ['slug'=>'utilisateurs','titre'=>'Gestion des utilisateurs'],
        'next' => ['slug'=>'parc-auto','titre'=>'Parc automobile']
    ])

</div>

@endsection