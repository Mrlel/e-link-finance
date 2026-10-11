@extends('layouts.main-board')

@section('title', 'Formation — Logiciels & licences')

@section('content')

@php $currentSlug = 'logiciels'; @endphp

<style>
    :root{
        --soft-purple:#6f42c1;
        --soft-purple-light:rgba(111,66,193,.10);
        --soft-purple-border:#e9d8fd;
        --soft-text:#111827;
        --soft-muted:#6b7280;
        --soft-border:#e5e7eb;
    }

    .formation-wrapper{
        max-width:1180px;
        margin:auto;
    }

    /* HERO */

    .hero-section{
        position:relative;
        overflow:hidden;
        background:linear-gradient(135deg,#f5f3ff 0%,#ffffff 100%);
        border:1px solid #ddd6fe;
        border-radius:18px;
        padding:2rem;
        margin-bottom:2rem;
    }

    .hero-section::after{
        content:"";
        position:absolute;
        width:240px;
        height:240px;
        border-radius:50%;
        background:rgba(111,66,193,.08);
        top:-120px;
        right:-70px;
    }

    .hero-icon{
        width:68px;
        height:68px;
        border-radius:16px;
        background:var(--soft-purple-light);
        display:flex;
        align-items:center;
        justify-content:center;
        flex-shrink:0;
    }

    .hero-icon i{
        font-size:1.9rem;
        color:var(--soft-purple);
    }

    .hero-title{
        font-size:2rem;
        font-weight:800;
        color:var(--soft-text);
        margin-bottom:.35rem;
    }

    .hero-text{
        color:var(--soft-muted);
        line-height:1.7;
        max-width:720px;
        margin-bottom:0;
    }

    /* MODULE */

    .module-card{
        background:#fff;
        border:none;
        border-radius:16px;
        overflow:hidden;
        margin-bottom:1.8rem;
        box-shadow:0 8px 24px rgba(15,23,42,.05);
    }

    .module-header{
        display:flex;
        align-items:center;
        gap:1rem;
        padding:1rem 1.4rem;
        border-bottom:1px solid var(--soft-border);
        background:#fff;
    }

    .module-number{
        width:40px;
        height:40px;
        border-radius:12px;
        background:var(--soft-purple-light);
        color:var(--soft-purple);
        display:flex;
        align-items:center;
        justify-content:center;
        font-weight:700;
        flex-shrink:0;
    }

    .module-title{
        margin-bottom:0;
        font-size:1.05rem;
        font-weight:700;
        color:var(--soft-text);
    }

    .module-body{
        padding:1.5rem;
    }

    /* INFO BOX */

    .info-box{
        background:#fafafa;
        border:1px solid #edf0f2;
        border-radius:14px;
        padding:1.3rem;
        height:100%;
    }

    .info-box h6{
        font-weight:700;
        margin-bottom:1rem;
    }

    .info-box ul{
        padding-left:1rem;
        margin-bottom:0;
    }

    .info-box li{
        margin-bottom:.75rem;
        color:#4b5563;
        line-height:1.6;
    }

    .info-box li:last-child{
        margin-bottom:0;
    }

    /* ALERT */

    .warning-box{
        background:#fff7ed;
        border:1px solid #fed7aa;
        border-radius:12px;
        padding:.9rem 1rem;
        margin-top:1rem;
        color:#9a3412;
        font-size:.92rem;
    }

    /* LIST */

    .modern-list{
        padding-left:1rem;
        margin-bottom:0;
    }

    .modern-list li{
        margin-bottom:1rem;
        color:#4b5563;
        line-height:1.7;
    }

    .modern-list li:last-child{
        margin-bottom:0;
    }

    /* STATUS */

    .status-grid{
        display:flex;
        flex-wrap:wrap;
        gap:1rem;
        margin:1.5rem 0;
    }

    .status-card{
        padding:1rem 1.2rem;
        border-radius:14px;
        border:1px solid #edf0f2;
        background:#fff;
        min-width:180px;
        display:flex;
        align-items:center;
        gap:.8rem;
    }

    .status-dot{
        width:12px;
        height:12px;
        border-radius:50%;
        flex-shrink:0;
    }

    .status-card span{
        font-size:.92rem;
        color:#374151;
        font-weight:500;
    }

    /* IMPORT BOX */

    .feature-box{
        background:#fafafa;
        border:1px solid #edf0f2;
        border-radius:14px;
        padding:1.4rem;
        height:100%;
    }

    .feature-box h6{
        font-weight:700;
        margin-bottom:.8rem;
        color:#111827;
    }

    .feature-box p{
        margin-bottom:0;
        color:#6b7280;
        line-height:1.7;
        font-size:.93rem;
    }

    /* IMAGE */

    .image-placeholder{
        border:2px dashed #d1d5db;
        border-radius:14px;
        background:#fafafa;
        padding:3rem 1rem;
        text-align:center;
        margin-top:1.5rem;
    }

    .image-placeholder i{
        font-size:3rem;
        color:#c4c4c4;
    }

    .image-placeholder p{
        margin-top:.8rem;
        margin-bottom:0;
        color:#9ca3af;
        font-size:.9rem;
    }

    @media(max-width:768px){

        .hero-section{
            padding:1.5rem;
        }

        .hero-title{
            font-size:1.5rem;
        }

        .module-body{
            padding:1.2rem;
        }

        .status-card{
            width:100%;
        }
    }
</style>

<div class="formation-wrapper py-4">

    @include('formation.partials.nav')

    {{-- HERO --}}
    <div class="hero-section">

        <div class="d-flex align-items-start gap-3">

            <div class="hero-icon">
                <i class="bi bi-box-seam-fill"></i>
            </div>

            <div>

                <h1 class="hero-title">
                    Logiciels & licences
                </h1>

                <p class="hero-text">
                    Gérez les licences logicielles,
                    les affectations sur les équipements
                    et surveillez les expirations
                    afin d’assurer la conformité du parc informatique.
                </p>

            </div>

        </div>

    </div>

    {{-- AJOUT --}}
    <div class="module-card">

        <div class="module-header">
            <div class="module-number">01</div>
            <h2 class="module-title">
                Ajouter une licence logicielle
            </h2>
        </div>

        <div class="module-body">

            <p class="text-muted mb-4">
                Accédez à
                <strong>Patrimoine → Logiciels</strong>
                puis cliquez sur
                <span class="badge rounded-pill px-3 py-2"
                      style="background:#6f42c1;">
                    <i class="bi bi-plus-circle me-1"></i>
                    Ajouter
                </span>
            </p>

            <div class="row g-4">

                <div class="col-md-6">

                    <div class="info-box">

                        <h6 style="color:#6f42c1;">
                            Informations licence
                        </h6>

                        <ul>
                            <li><strong>Désignation</strong> — nom du logiciel</li>
                            <li><strong>Clé de licence</strong> — code d’activation</li>
                            <li><strong>Type</strong> — commercial, open source, freeware...</li>
                            <li><strong>Version</strong> du logiciel</li>
                            <li><strong>Environnement</strong> — Windows, Linux, Web...</li>
                        </ul>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="info-box">

                        <h6 style="color:#6f42c1;">
                            Validité & expiration
                        </h6>

                        <ul>
                            <li><strong>Date d’expiration</strong> — laissez vide pour une licence permanente</li>
                            <li><strong>Nombre de postes</strong> — installations autorisées</li>
                        </ul>

                        <div class="warning-box">

                            <i class="bi bi-exclamation-triangle me-1"></i>

                            Les licences expirant dans moins de 30 jours
                            apparaissent automatiquement en rouge.

                        </div>

                    </div>

                </div>

            </div>

            <div class="image-placeholder">

                <i class="bi bi-image"></i>

                <p>
                    [ Capture d’écran : Formulaire d’ajout de licence ]
                </p>

            </div>

        </div>

    </div>

    {{-- ASSIGNATION --}}
    <div class="module-card">

        <div class="module-header">
            <div class="module-number">02</div>
            <h2 class="module-title">
                Assigner une licence
            </h2>
        </div>

        <div class="module-body">

            <ol class="modern-list">

                <li>
                    Ouvrez la fiche du logiciel
                    depuis la liste des licences.
                </li>

                <li>
                    Cliquez sur
                    <span class="badge rounded-pill px-3 py-2"
                          style="background:#6f42c1;">
                        <i class="bi bi-link me-1"></i>
                        Assigner à un équipement
                    </span>
                </li>

                <li>
                    Sélectionnez l’équipement cible.
                </li>

                <li>
                    La licence devient visible
                    dans la fiche de l’équipement,
                    section <strong>Logiciels installés</strong>.
                </li>

            </ol>

            <div class="image-placeholder">

                <i class="bi bi-image"></i>

                <p>
                    [ Capture d’écran : Assignation licence → équipement ]
                </p>

            </div>

        </div>

    </div>

    {{-- EXPIRATION --}}
    <div class="module-card">

        <div class="module-header">
            <div class="module-number">03</div>
            <h2 class="module-title">
                Suivi des expirations
            </h2>
        </div>

        <div class="module-body">

            <p class="text-muted">
                Le système affiche automatiquement
                l’état de chaque licence logicielle.
            </p>

            <div class="status-grid">

                <div class="status-card">
                    <div class="status-dot bg-success"></div>
                    <span>Licence valide</span>
                </div>

                <div class="status-card">
                    <div class="status-dot bg-warning"></div>
                    <span>Expire bientôt</span>
                </div>

                <div class="status-card">
                    <div class="status-dot bg-danger"></div>
                    <span>Licence expirée</span>
                </div>

                <div class="status-card">
                    <div class="status-dot bg-secondary"></div>
                    <span>Licence permanente</span>
                </div>

            </div>

            <p class="text-muted mb-0">
                Utilisez les filtres
                <strong>Logiciels expirés</strong>
                et
                <strong>Bientôt expirés</strong>
                pour afficher rapidement
                les licences nécessitant une action.
            </p>

            <div class="image-placeholder">

                <i class="bi bi-image"></i>

                <p>
                    [ Capture d’écran : Liste des licences avec statuts ]
                </p>

            </div>

        </div>

    </div>

    {{-- IMPORT EXPORT --}}
    <div class="module-card">

        <div class="module-header">
            <div class="module-number">04</div>
            <h2 class="module-title">
                Import & export
            </h2>
        </div>

        <div class="module-body">

            <div class="row g-4">

                <div class="col-md-6">

                    <div class="feature-box">

                        <h6>
                            <i class="bi bi-upload me-2"></i>
                            Import Excel
                        </h6>

                        <p>
                            Importez plusieurs licences simultanément
                            via un fichier Excel (.xlsx)
                            à partir du modèle disponible
                            sur la page logiciels.
                        </p>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="feature-box">

                        <h6>
                            <i class="bi bi-download me-2"></i>
                            Export des données
                        </h6>

                        <p>
                            Exportez la liste des licences
                            en format Excel ou PDF
                            depuis les boutons d’export
                            disponibles dans le tableau.
                        </p>

                    </div>

                </div>

            </div>

            <div class="image-placeholder">

                <i class="bi bi-image"></i>

                <p>
                    [ Capture d’écran : Import / Export logiciels ]
                </p>

            </div>

        </div>

    </div>

    @include('formation.partials.module-footer', [
        'prev' => ['slug'=>'mobilier','titre'=>'Mobilier & matériel de bureau'],
        'next' => ['slug'=>'inventaire','titre'=>'Inventaire du parc']
    ])

</div>

@endsection