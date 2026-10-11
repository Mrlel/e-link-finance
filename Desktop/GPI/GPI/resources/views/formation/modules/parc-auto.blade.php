@extends('layouts.main-board')

@section('title', 'Formation — Parc automobile')

@section('content')

@php $currentSlug = 'parc-auto'; @endphp

<style>
    :root{
        --auto-danger:#dc2626;
        --auto-danger-soft:rgba(220,38,38,.10);
        --auto-border:#e5e7eb;
        --auto-muted:#6b7280;
        --auto-text:#111827;
    }

    .formation-wrapper{
        max-width:1180px;
        margin:auto;
    }

    /* HERO */

    .hero-section{
        position:relative;
        overflow:hidden;
        background:linear-gradient(135deg,#fef2f2 0%,#ffffff 100%);
        border:1px solid #fecaca;
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
        background:rgba(220,38,38,.08);
        top:-120px;
        right:-70px;
    }

    .hero-icon{
        width:68px;
        height:68px;
        border-radius:16px;
        background:var(--auto-danger-soft);
        display:flex;
        align-items:center;
        justify-content:center;
        flex-shrink:0;
    }

    .hero-icon i{
        font-size:1.9rem;
        color:var(--auto-danger);
    }

    .hero-title{
        font-size:2rem;
        font-weight:800;
        color:var(--auto-text);
        margin-bottom:.35rem;
    }

    .hero-text{
        color:var(--auto-muted);
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
        border-bottom:1px solid var(--auto-border);
        background:#fff;
    }

    .module-number{
        width:40px;
        height:40px;
        border-radius:12px;
        background:var(--auto-danger-soft);
        color:var(--auto-danger);
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
        color:var(--auto-text);
    }

    .module-body{
        padding:1.5rem;
    }

    /* INFO CARD */

    .info-card{
        background:#fafafa;
        border:1px solid #edf0f2;
        border-radius:14px;
        padding:1.3rem;
        height:100%;
    }

    .info-card h6{
        font-weight:700;
        margin-bottom:1rem;
    }

    .info-card ul{
        padding-left:1rem;
        margin-bottom:0;
    }

    .info-card li{
        margin-bottom:.7rem;
        color:#4b5563;
        line-height:1.6;
    }

    .info-card li:last-child{
        margin-bottom:0;
    }

    .status-alert{
        background:#f0fdf4;
        border:1px solid #bbf7d0;
        border-radius:12px;
        padding:.9rem 1rem;
        color:#166534;
        margin-top:1rem;
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

    /* HISTORY BOX */

    .history-box{
        background:#fafafa;
        border:1px solid #edf0f2;
        border-radius:14px;
        padding:1.2rem;
        margin-top:1.2rem;
    }

    .history-box ul{
        margin-bottom:0;
        padding-left:1rem;
    }

    .history-box li{
        margin-bottom:.7rem;
        color:#4b5563;
    }

    .history-box li:last-child{
        margin-bottom:0;
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
    }
</style>

<div class="formation-wrapper py-4">

    @include('formation.partials.nav')

    {{-- HERO --}}
    <div class="hero-section">

        <div class="d-flex align-items-start gap-3">

            <div class="hero-icon">
                <i class="bi bi-car-front-fill"></i>
            </div>

            <div>

                <h1 class="hero-title">
                    Gestion du parc automobile
                </h1>

                <p class="hero-text">
                    Enregistrez les véhicules, gérez les affectations,
                    suivez les retraits et consultez l’historique
                    complet des mouvements du parc automobile.
                </p>

            </div>

        </div>

    </div>

    {{-- AJOUT --}}
    <div class="module-card">

        <div class="module-header">
            <div class="module-number">01</div>
            <h2 class="module-title">Ajouter un véhicule</h2>
        </div>

        <div class="module-body">

            <p class="text-muted mb-4">
                Accédez à
                <strong>Patrimoine → Gestion du parc auto</strong>
                puis cliquez sur
                <span class="badge bg-success rounded-pill px-3 py-2">
                    <i class="bi bi-plus-circle me-1"></i>
                    Ajouter un véhicule
                </span>
            </p>

            <div class="row g-4">

                <div class="col-md-6">

                    <div class="info-card">

                        <h6 class="text-danger">
                            Identification
                        </h6>

                        <ul>
                            <li><strong>Immatriculation</strong> — identifiant unique obligatoire</li>
                            <li><strong>Numéro de châssis</strong> — VIN</li>
                            <li><strong>Catégorie</strong> — Auto ou Moto</li>
                            <li><strong>Marque / Modèle / Couleur</strong></li>
                            <li><strong>Date de mise en circulation</strong></li>
                            <li><strong>Lieu d’utilisation</strong></li>
                        </ul>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="info-card">

                        <h6 class="text-danger">
                            État & acquisition
                        </h6>

                        <ul>
                            <li><strong>État</strong> — NEUF, BON, MOYEN, MAUVAIS ou HORS SERVICE</li>
                            <li><strong>Mode d’acquisition</strong> — Budget État ou Don</li>
                        </ul>

                        <div class="status-alert">
                            <i class="bi bi-check-circle me-1"></i>
                            Le statut initial du véhicule est automatiquement
                            défini sur <strong>Disponible</strong>.
                        </div>

                    </div>

                </div>

            </div>

            <div class="image-placeholder">
                <img src="/formation/add-auto.jpeg" height="380">
            </div>

    </div>

    {{-- AFFECTATION --}}
    <div class="module-card">

        <div class="module-header">
            <div class="module-number">02</div>
            <h2 class="module-title">Affecter un véhicule</h2>
        </div>

        <div class="module-body">

            <p class="text-muted">
                Seuls les véhicules avec le statut
                <span class="badge bg-success rounded-pill px-3 py-2">
                    Disponible
                </span>
                peuvent être affectés.
            </p>

            <ol class="modern-list">

                <li>
                    Depuis la liste des véhicules, ouvrez les actions puis cliquez sur
                    <strong>Affecter</strong>.
                </li>

                <li>
                    Sélectionnez l’utilisateur dans la liste déroulante.
                </li>

                <li>
                    Confirmez l’opération pour passer le véhicule au statut
                    <span class="badge bg-primary rounded-pill px-3 py-2">
                        Affecté
                    </span>
                </li>

                <li>
                    L’affectation est automatiquement enregistrée avec la date
                    et l’utilisateur ayant effectué l’opération.
                </li>

            </ol>

            <div class="image-placeholder">
 <img src="/formation/attrib-auto.jpeg"
                     alt="Modal d'affectation auto">

            </div>

        </div>

    </div>

    {{-- RETRAIT --}}
    <div class="module-card">

        <div class="module-header">
            <div class="module-number">03</div>
            <h2 class="module-title">Retirer un véhicule</h2>
        </div>

        <div class="module-body">

            <ol class="modern-list">

                <li>
                    Ouvrez la fiche du véhicule concerné.
                </li>

                <li>
                    Dans la section affectation active, cliquez sur
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                        <i class="bi bi-arrow-return-left me-1"></i>
                        Retirer le véhicule
                    </span>
                </li>

                <li>
                    Ajoutez un commentaire ou un motif de retrait.
                </li>

                <li>
                    Le véhicule repasse automatiquement au statut
                    <span class="badge bg-success rounded-pill px-3 py-2">
                        Disponible
                    </span>
                </li>

            </ol>

            <div class="image-placeholder">
 <img src="/formation/retrait-auto.jpeg"
                     alt="Modal de retrait auto" height="380">
            </div>
    </div>

    {{-- HISTORIQUE --}}
    <div class="module-card">

        <div class="module-header">
            <div class="module-number">04</div>
            <h2 class="module-title">Historique des affectations</h2>
        </div>

        <div class="module-body">

            <p class="text-muted">
                Consultez tous les mouvements des véhicules
                depuis le bouton
                <span class="badge bg-secondary rounded-pill px-3 py-2">
                    <i class="bi bi-clock-history me-1"></i>
                    Historique
                </span>
            </p>

            <div class="history-box">

                <ul>
                    <li>Véhicule concerné (immatriculation et marque)</li>
                    <li>Utilisateur affecté</li>
                    <li>Dates d’affectation et de retrait</li>
                    <li>Statut de l’affectation</li>
                    <li>Commentaires de retrait</li>
                </ul>

            </div>

            <p class="text-muted mt-4 mb-0">
                Utilisez la barre de recherche pour filtrer rapidement
                par immatriculation ou nom d’utilisateur.
            </p>

            <div class="image-placeholder">

                <i class="bi bi-image"></i>

                <p>
                    [ Capture d’écran : Historique des affectations véhicules ]
                </p>

            </div>

        </div>

    </div>

    @include('formation.partials.module-footer', [
        'prev' => ['slug'=>'materiel-informatique','titre'=>'Matériel informatique'],
        'next' => ['slug'=>'mobilier','titre'=>'Mobilier & matériel de bureau']
    ])

</div>

@endsection