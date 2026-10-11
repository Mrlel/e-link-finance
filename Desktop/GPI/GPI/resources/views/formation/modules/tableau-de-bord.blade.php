@extends('layouts.main-board')

@section('title', 'Formation — Tableau de bord')

@section('content')

@php $currentSlug = 'tableau-de-bord'; @endphp

<style>
    :root{
        --dash-primary: #16a34a;
        --dash-primary-soft: rgba(22,163,74,.12);
        --dash-border: #e5e7eb;
        --dash-muted: #6b7280;
        --dash-text: #111827;
        --dash-bg: #f8fafc;
    }

    .formation-wrapper{
        max-width: 1180px;
        margin: auto;
    }

    .hero-dashboard{
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg,#f0fdf4 0%,#ffffff 100%);
        border: 1px solid #d8f0df;
        border-radius: 10px;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .hero-dashboard::after{
        content: "";
        position: absolute;
        width: 260px;
        height: 260px;
        border-radius: 50%;
        background: rgba(22,163,74,.08);
        top: -120px;
        right: -80px;
    }

    .hero-icon{
        width: 74px;
        height: 74px;
        border-radius: 24px;
        background: var(--dash-primary-soft);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .hero-icon i{
        font-size: 2rem;
        color: var(--dash-primary);
    }

    .hero-title{
        font-size: 2rem;
        font-weight: 800;
        color: var(--dash-text);
        margin-bottom: .4rem;
    }

    .hero-text{
        color: var(--dash-muted);
        max-width: 720px;
        line-height: 1.7;
        margin-bottom: 0;
    }

    .module-card{
        background: #fff;
        border: none;
        border-radius: 6px;
        overflow: hidden;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(15,23,42,.06);
    }

    .module-header{
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.2rem 1.5rem;
        border-bottom: 1px solid var(--dash-border);
        background: #fff;
    }

    .module-number{
        width: 44px;
        height: 44px;
        border-radius: 14px;
        background: var(--dash-primary-soft);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--dash-primary);
        font-weight: 700;
        flex-shrink: 0;
    }

    .module-title{
        margin-bottom: 0;
        font-size: 1.08rem;
        font-weight: 700;
        color: var(--dash-text);
    }

    .module-body{
        padding: 1.5rem;
    }

    .feature-list{
        margin-top: 1.5rem;
    }

    .feature-item{
        display: flex;
        gap: 1rem;
        padding: 1rem;
        border-radius: 8px;
        background: #f9fafb;
        border: 1px solid #edf0f2;
        margin-bottom: 1rem;
    }

    .feature-item:last-child{
        margin-bottom: 0;
    }

    .feature-icon{
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .feature-icon i{
        font-size: 1.2rem;
    }

    .feature-content strong{
        display: block;
        margin-bottom: .25rem;
        color: #111827;
    }

    .feature-content{
        color: var(--dash-muted);
        line-height: 1.6;
        font-size: .95rem;
    }

    .feature-success{
        background: rgba(22,163,74,.12);
        color: #15803d;
    }

    .feature-primary{
        background: rgba(59,130,246,.12);
        color: #2563eb;
    }

    .feature-warning{
        background: rgba(245,158,11,.12);
        color: #d97706;
    }

    .modern-image{
        background: #fafafa;
        border: 2px dashed #d1d5db;
        border-radius: 22px;
        padding: 1rem;
        margin-top: 1.5rem;
        overflow: hidden;
    }

    .modern-image img{
        border-radius: 18px;
        width: 100%;
        display: block;
    }

    .table-modern{
        overflow: hidden;
        border-radius: 20px;
        border: 1px solid #edf0f2;
    }

    .table-modern table{
        margin-bottom: 0;
    }

    .table-modern thead{
        background: #111827;
        color: white;
    }

    .table-modern th{
        font-size: .88rem;
        text-transform: uppercase;
        letter-spacing: .5px;
        border: none !important;
        padding: 1rem;
    }

    .table-modern td{
        padding: 1rem;
        vertical-align: middle;
        border-color: #f1f5f9;
    }

    .table-modern tbody tr:hover{
        background: #f9fafb;
    }

    .notification-steps{
        padding-left: 1rem;
        margin-top: 1.2rem;
    }

    .notification-steps li{
        margin-bottom: 1rem;
        color: #4b5563;
        line-height: 1.7;
    }

    .notification-steps li:last-child{
        margin-bottom: 0;
    }

    .warning-box{
        display: flex;
        gap: 1rem;
        background: #fff7ed;
        border: 1px solid #fed7aa;
        border-radius: 18px;
        padding: 1rem 1.2rem;
        margin-top: 1.5rem;
    }

    .warning-box i{
        color: #ea580c;
        font-size: 1.2rem;
        margin-top: 2px;
    }

    .warning-box div{
        color: #9a3412;
        line-height: 1.6;
    }

    @media (max-width: 768px){

        .hero-dashboard{
            padding: 1.5rem;
        }

        .hero-title{
            font-size: 1.5rem;
        }

        .module-body{
            padding: 1.2rem;
        }
    }
</style>

<div class="formation-wrapper py-4">

    @include('formation.partials.nav')

    {{-- HERO --}}
    <div class="hero-dashboard">

        <div class="d-flex align-items-start gap-3">

            <div class="hero-icon">
                <i class="bi bi-grid-1x2-fill"></i>
            </div>

            <div>
                <h1 class="hero-title">Tableau de bord</h1>

                <p class="hero-text">
                    Découvrez les principaux indicateurs du système,
                    les raccourcis de navigation et les alertes importantes
                    afin de piloter efficacement votre parc informatique.
                </p>
            </div>

        </div>

    </div>

    {{-- SECTION 1 --}}
    <div class="module-card">

        <div class="module-header">
            <div class="module-number">01</div>
            <h2 class="module-title">Présentation générale</h2>
        </div>

        <div class="module-body">

            <p class="text-muted">
                Le tableau de bord est la première interface affichée après connexion.
                Il centralise toutes les informations essentielles concernant le parc informatique.
            </p>

            <div class="feature-list">

                <div class="feature-item">

                    <div class="feature-icon feature-success">
                        <i class="bi bi-bar-chart-fill"></i>
                    </div>

                    <div class="feature-content">
                        <strong>Statistiques clés</strong>
                        Nombre d’équipements, affectations actives,
                        demandes en attente et licences arrivant à expiration.
                    </div>

                </div>

                <div class="feature-item">

                    <div class="feature-icon feature-primary">
                        <i class="bi bi-pie-chart-fill"></i>
                    </div>

                    <div class="feature-content">
                        <strong>Graphiques analytiques</strong>
                        Répartition du matériel par catégorie,
                        état du parc et évolution des demandes.
                    </div>

                </div>

                <div class="feature-item">

                    <div class="feature-icon feature-warning">
                        <i class="bi bi-bell-fill"></i>
                    </div>

                    <div class="feature-content">
                        <strong>Alertes importantes</strong>
                        Notifications liées aux équipements en fin de vie,
                        licences expirées et demandes urgentes.
                    </div>

                </div>

            </div>

            <div class="modern-image">
                <img src="/formation/dashboard.jpeg"
                     alt="Vue générale du tableau de bord">
            </div>

        </div>

    </div>

    {{-- SECTION 2 --}}
    <div class="module-card">

        <div class="module-header">
            <div class="module-number">02</div>
            <h2 class="module-title">Navigation principale</h2>
        </div>

        <div class="module-body">

            <p class="text-muted mb-4">
                La barre de navigation donne accès à tous les modules
                essentiels de la plateforme.
            </p>

            <div class="table-responsive table-modern">

                <table class="table align-middle">

                    <thead>
                        <tr>
                            <th>Menu</th>
                            <th>Fonction</th>
                            <th>Accès</th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr>
                            <td><i class="bi bi-grid-1x2 me-2 text-success"></i>Tableau de bord</td>
                            <td>Vue synthétique du système</td>
                            <td><span class="badge bg-success rounded-pill px-3 py-2">Tous</span></td>
                        </tr>

                        <tr>
                            <td><i class="bi bi-people me-2 text-primary"></i>Gestion Utilisateurs</td>
                            <td>Création et gestion des comptes</td>
                            <td><span class="badge bg-dark rounded-pill px-3 py-2">Admin</span></td>
                        </tr>

                        <tr>
                            <td><i class="bi bi-hdd-network me-2 text-warning"></i>Patrimoine</td>
                            <td>Gestion des équipements et logiciels</td>
                            <td><span class="badge bg-success rounded-pill px-3 py-2">Tous</span></td>
                        </tr>

                        <tr>
                            <td><i class="bi bi-journal-text me-2 text-info"></i>Inventaire</td>
                            <td>Suivi complet des inventaires</td>
                            <td><span class="badge bg-success rounded-pill px-3 py-2">Tous</span></td>
                        </tr>

                        <tr>
                            <td><i class="bi bi-envelope me-2 text-danger"></i>Demandes</td>
                            <td>Maintenance et besoins matériels</td>
                            <td><span class="badge bg-success rounded-pill px-3 py-2">Tous</span></td>
                        </tr>

                        <tr>
                            <td><i class="bi bi-clock-history me-2 text-secondary"></i>Activités</td>
                            <td>Historique et journal système</td>
                            <td>
                                <span class="badge bg-secondary rounded-pill px-3 py-2">
                                    Tous / Admin
                                </span>
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

            <div class="modern-image">
                <img src="/formation/nav.jpeg"
                     alt="Navigation principale">
            </div>

        </div>

    </div>

    {{-- SECTION 3 --}}
    <div class="module-card">

        <div class="module-header">
            <div class="module-number">03</div>
            <h2 class="module-title">Notifications</h2>
        </div>

        <div class="module-body">

            <p class="text-muted">
                L’icône
                <i class="bi bi-bell-fill text-warning"></i>
                située en haut à droite affiche les notifications non lues.
            </p>

            <ol class="notification-steps">

                <li>
                    Cliquez sur l’icône cloche pour ouvrir
                    le centre de notifications.
                </li>

                <li>
                    Chaque notification précise l’action concernée :
                    affectation, maintenance, demande ou alerte.
                </li>

                <li>
                    Cliquez sur une notification pour la marquer comme lue
                    et accéder directement à l’élément concerné.
                </li>

            </ol>

            <div class="warning-box">

                <i class="bi bi-exclamation-triangle-fill"></i>

                <div>
                    Les notifications affichées en rouge signalent
                    des alertes critiques nécessitant une intervention rapide :
                    licences expirées, équipements défectueux ou fin de durée de vie.
                </div>

            </div>

            <div class="modern-image mt-4 text-center">

                <img src="/formation/centre_notif.png"
                     alt="Centre de notification">
            </div>

        </div>

    </div>

    @include('formation.partials.module-footer', [
        'prev' => null,
        'next' => ['slug'=>'utilisateurs','titre'=>'Gestion des utilisateurs']
    ])

</div>

@endsection