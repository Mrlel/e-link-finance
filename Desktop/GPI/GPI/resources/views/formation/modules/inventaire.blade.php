@extends('layouts.main-board')

@section('title', 'Formation — Inventaire du parc')

@section('content')

@php $currentSlug = 'inventaire'; @endphp

<style>
    :root{
        --primary-color:#6f42c1;
        --primary-light:rgba(111,66,193,.10);
        --primary-border:#ddd6fe;

        --text-dark:#111827;
        --text-muted:#6b7280;

        --border-color:#e5e7eb;
        --bg-soft:#fafafa;
    }
body {
    background-color: light;
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
        border:1px solid var(--primary-border);
        border-radius:10px;
        padding:2rem;
        margin-bottom:2rem;
    }

    .hero-section::after{
        content:"";
        position:absolute;
        width:240px;
        height:240px;
        border-radius:50%;
        background:rgba(111,66,193,.06);
        top:-120px;
        right:-70px;
    }

    .hero-icon{
        width:68px;
        height:68px;
        border-radius:16px;
        background:var(--primary-light);
        display:flex;
        align-items:center;
        justify-content:center;
        flex-shrink:0;
    }

    .hero-icon i{
        font-size:1.9rem;
        color:var(--primary-color);
    }

    .hero-title{
        font-size:2rem;
        font-weight:800;
        color:var(--text-dark);
        margin-bottom:.35rem;
    }

    .hero-text{
        color:var(--text-muted);
        line-height:1.7;
        margin-bottom:0;
        max-width:760px;
    }

    /* CARD */

    .module-card{
        background:#fff;
        border:none;
        border-radius:10px;
        overflow:hidden;
        margin-bottom:1.8rem;
        box-shadow:0 8px 24px rgba(15,23,42,.05);
    }

    .module-header{
        display:flex;
        align-items:center;
        gap:1rem;
        padding:1rem 1.4rem;
        border-bottom:1px solid var(--border-color);
        background:#fff;
    }

    .module-number{
        width:42px;
        height:42px;
        border-radius:12px;
        background:var(--primary-light);
        color:var(--primary-color);
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
        color:var(--text-dark);
    }

    .module-body{
        padding:1.5rem;
    }

    /* STATUS */

    .status-card{
        border:1px solid #edf0f2;
        border-radius:14px;
        padding:1.3rem 1rem;
        background:#fff;
        text-align:center;
        height:100%;
        transition:.25s ease;
    }

    .status-card:hover{
        transform:translateY(-2px);
    }

    .status-card i{
        font-size:2rem;
        margin-bottom:.7rem;
    }

    .status-title{
        font-weight:700;
        color:var(--text-dark);
        margin-bottom:.35rem;
    }

    .status-text{
        color:var(--text-muted);
        font-size:.9rem;
        line-height:1.5;
    }

    /* TABLE */

    .modern-table{
        border:1px solid var(--border-color);
        border-radius:10px;
        overflow:hidden;
    }

    .modern-table table{
        margin-bottom:0;
    }

    .modern-table thead{
        background:#faf5ff;
    }

    .modern-table th{
        border:none;
        padding:1rem;
        font-size:.92rem;
        color:var(--text-dark);
        font-weight:700;
    }

    .modern-table td{
        padding:1rem;
        border-top:1px solid #f1f5f9;
        color:#4b5563;
        vertical-align:middle;
    }

    /* EXPORT */

    .feature-card{
        border:1px solid #edf0f2;
        border-radius:10px;
        padding:1.4rem;
        background:#fff;
        height:100%;
        transition:.25s ease;
    }

    .feature-card:hover{
        transform:translateY(-2px);
    }

    .feature-icon{
        width:54px;
        height:54px;
        border-radius:14px;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:1.5rem;
        flex-shrink:0;
    }

    .feature-title{
        font-size:1rem;
        font-weight:700;
        color:var(--text-dark);
        margin-bottom:.35rem;
    }

    .feature-text{
        color:var(--text-muted);
        font-size:.92rem;
        line-height:1.6;
        margin-bottom:0;
    }

    /* ALERT */

    .info-alert{
        background:#f5f3ff;
        border:1px solid #ddd6fe;
        border-radius:14px;
        padding:1rem 1.1rem;
        color:#5b21b6;
    }

    /* IMAGE */

    .image-placeholder{
        border:2px dashed #d1d5db;
        border-radius:10px;
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
    }
</style>

<div class="formation-wrapper py-4">

    @include('formation.partials.nav')

    {{-- HERO --}}
    <div class="hero-section">

        <div class="d-flex align-items-start gap-3">

            <div class="hero-icon">
                <i class="bi bi-journal-text"></i>
            </div>

            <div>

                <h1 class="hero-title">
                    Inventaire du parc
                </h1>

                <p class="hero-text">
                    Consultez l’ensemble des équipements,
                    appliquez des filtres avancés
                    et exportez rapidement les données
                    pour le suivi et les rapports administratifs.
                </p>

            </div>

        </div>

    </div>

    {{-- SECTION 1 --}}
    <div class="module-card">

        <div class="module-header">

            <div class="module-number">01</div>

            <h2 class="module-title">
                Accéder à l’inventaire
            </h2>

        </div>

        <div class="module-body">

            <p class="text-muted mb-4">
                Cliquez sur
                <strong>Inventaire du parc</strong>
                dans la barre de navigation.
                Cette section centralise tous les équipements
                de votre direction.
            </p>

            <div class="row g-4">

                <div class="col-md-3">
                    <div class="status-card">

                        <i class="bi bi-check-circle-fill text-success"></i>

                        <div class="status-title">
                            En stock
                        </div>

                        <div class="status-text">
                            Équipements disponibles
                            non affectés
                        </div>

                    </div>
                </div>

                <div class="col-md-3">
                    <div class="status-card">

                        <i class="bi bi-person-fill text-primary"></i>

                        <div class="status-title">
                            En service
                        </div>

                        <div class="status-text">
                            Affectés à un utilisateur
                        </div>

                    </div>
                </div>

                <div class="col-md-3">
                    <div class="status-card">

                        <i class="bi bi-tools text-warning"></i>

                        <div class="status-title">
                            Maintenance
                        </div>

                        <div class="status-text">
                            Sortis pour réparation
                        </div>

                    </div>
                </div>

                <div class="col-md-3">
                    <div class="status-card">

                        <i class="bi bi-box-arrow-right text-secondary"></i>

                        <div class="status-title">
                            Enlèvement
                        </div>

                        <div class="status-text">
                            Réformés définitivement
                        </div>

                    </div>
                </div>

            </div>

            <div class="image-placeholder">

                <i class="bi bi-image"></i>

                <p>
                    [ Capture d’écran : Page inventaire — vue générale ]
                </p>

            </div>

        </div>

    </div>

    {{-- SECTION 2 --}}
    <div class="module-card">

        <div class="module-header">

            <div class="module-number">02</div>

            <h2 class="module-title">
                Filtres & recherche avancée
            </h2>

        </div>

        <div class="module-body">

            <p class="text-muted mb-4">
                Les filtres permettent de retrouver rapidement
                un équipement spécifique.
            </p>

            <div class="modern-table">

                <table class="table align-middle">

                    <thead>
                        <tr>
                            <th>Filtre</th>
                            <th>Valeurs possibles</th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr>
                            <td>Recherche texte</td>
                            <td>Désignation, marque, modèle, N° série</td>
                        </tr>

                        <tr>
                            <td>Catégorie</td>
                            <td>Ordinateur, Imprimante, Switch, Serveur…</td>
                        </tr>

                        <tr>
                            <td>Statut</td>
                            <td>En stock, En service, Maintenance, Enlèvement</td>
                        </tr>

                        <tr>
                            <td>État</td>
                            <td>Bon, Moyen, Hors service</td>
                        </tr>

                        <tr>
                            <td>Source d’acquisition</td>
                            <td>État, Bailleur, Autre</td>
                        </tr>

                    </tbody>

                </table>

            </div>

            <p class="small text-muted mt-3 mb-0">
                Cliquez sur
                <strong>Filtrer</strong>
                pour appliquer les critères
                ou sur
                <strong>Reset</strong>
                pour les réinitialiser.
            </p>

            <div class="image-placeholder">

                <i class="bi bi-image"></i>

                <p>
                    [ Capture d’écran : Barre de filtres inventaire ]
                </p>

            </div>

        </div>

    </div>

    {{-- SECTION 3 --}}
    <div class="module-card">

        <div class="module-header">

            <div class="module-number">03</div>

            <h2 class="module-title">
                Exporter l’inventaire
            </h2>

        </div>

        <div class="module-body">

            <p class="text-muted mb-4">
                Plusieurs formats d’export sont disponibles
                selon vos besoins.
            </p>

            <div class="row g-4">

                <div class="col-md-6">

                    <div class="feature-card">

                        <div class="d-flex gap-3 align-items-start">

                            <div class="feature-icon bg-success bg-opacity-10 text-success">
                                <i class="bi bi-file-earmark-excel"></i>
                            </div>

                            <div>

                                <h6 class="feature-title">
                                    Export Excel (.xlsx)
                                </h6>

                                <p class="feature-text">
                                    Export complet avec toutes les colonnes.
                                    Idéal pour les analyses,
                                    statistiques et rapports détaillés.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="feature-card">

                        <div class="d-flex gap-3 align-items-start">

                            <div class="feature-icon bg-danger bg-opacity-10 text-danger">
                                <i class="bi bi-file-earmark-pdf"></i>
                            </div>

                            <div>

                                <h6 class="feature-title">
                                    Export PDF
                                </h6>

                                <p class="feature-text">
                                    Document formaté pour impression,
                                    archivage administratif
                                    et transmission officielle.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="info-alert d-flex gap-2 align-items-start mt-4">

                <i class="bi bi-info-circle-fill mt-1"></i>

                <div>
                    Les exports tiennent compte des filtres actifs.
                    Filtrez d’abord les données
                    avant de lancer l’export.
                </div>

            </div>

            <div class="image-placeholder">

                <i class="bi bi-image"></i>

                <p>
                    [ Capture d’écran : Boutons d’export ]
                </p>

            </div>

        </div>

    </div>

    @include('formation.partials.module-footer', [
        'prev' => ['slug'=>'logiciels','titre'=>'Logiciels & licences'],
        'next' => ['slug'=>'demandes','titre'=>'Demandes']
    ])

</div>

@endsection