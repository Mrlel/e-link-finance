@extends('layouts.main-board')

@section('title', 'Centre de formation')

@section('content')

<style>
    :root{
        --primary-green:#198754;
        --soft-bg:#f5f7fb;
        --card-border:#edf1f7;
        --text-muted:#6c757d;
    }

    body{
        background: var(--soft-bg);
    }


    /* HERO CENTRÉ */
    .formation-hero{
        background:
            radial-gradient(circle at top right, rgba(255,255,255,.12), transparent 30%),
            linear-gradient(135deg,#157347 0%, #198754 45%, #20a16c 100%);
        border-radius: 11px;
        overflow: hidden;
        position: relative;

        max-width: 1100px;
        margin: 24px auto;
    }

    .formation-hero::before{
        content:'';
        position:absolute;
        width:280px;
        height:280px;
        background:rgba(255,255,255,.06);
        border-radius:50%;
        top:-120px;
        right:-100px;
    }

    .hero-badge{
        background: rgba(255,255,255,.14);
        border: 1px solid rgba(255,255,255,.15);
        backdrop-filter: blur(4px);
        color:#fff;
        border-radius: 10px;
        padding: .65rem .9rem;
        font-weight: 500;
        font-size: .85rem;
    }

    .section-title{
        font-size: 1.3rem;
        font-weight: 700;
        color:#1e293b;
    }

    /* MODULE CARD */
    .module-card{
        border-radius: 10px;
        border: 1px solid var(--card-border);
        transition: all .25s ease;
        text-decoration: none;
        background:#fff;
        height: 100%;
    }

    .module-card:hover{
        transform: translateY(-5px);
        box-shadow: 0 14px 35px rgba(15,23,42,.08);
    }

    .module-icon{
        width:58px;
        height:58px;
        border-radius:14px;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:1.4rem;
        background: var(--icon-bg);
        color: var(--accent);
    }

    .module-badge{
        background: var(--icon-bg);
        color: var(--accent);
        font-size:.7rem;
        font-weight:600;
        padding:.35rem .7rem;
        border-radius:999px;
    }

    .module-title{
        font-size:1rem;
        font-weight:700;
        color:#1e293b;
    }

    .module-desc{
        color:var(--text-muted);
        font-size:.9rem;
        line-height:1.5;
    }

    .module-link{
        color: var(--accent);
        font-weight:600;
        font-size:.88rem;
    }

    .module-arrow{
        transition: transform .2s ease;
    }

    .module-card:hover .module-arrow{
        transform: translateX(5px);
    }

    /* TIP BOX */
    .tip-box{
        border-radius:14px;
        background: #fff;
        border:1px solid #e8eef7;
        max-width: 1100px;
        margin: 40px auto 20px;
    }

</style>

<div class="formation-wrapper">

    {{-- HERO CENTRÉ --}}
    <div class="formation-hero shadow-sm">

        <div class="card-body py-5 px-4 px-lg-5 text-center text-lg-start d-flex flex-column flex-lg-row align-items-center justify-content-between gap-4">

            <div class="text-white flex-grow-1">
                <h1 class="fw-bold mb-3" style="font-size:2.2rem;">
                    Centre de Formation
                </h1>

                <p class="mb-4 opacity-75 fs-5" style="max-width:720px;">
                    Maîtrisez chaque module du système de Gestion du Patrimoine Informatique
                    grâce à des guides simples, illustrés et progressifs.
                </p>

                <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-lg-start">

                    <div class="hero-badge">
                        <i class="bi bi-grid-3x3-gap-fill me-2"></i>
                        9 modules
                    </div>

                    <div class="hero-badge">
                        <i class="bi bi-images me-2"></i>
                        Illustré
                    </div>

                    <div class="hero-badge">
                        <i class="bi bi-person-check me-2"></i>
                        Accessible
                    </div>

                </div>

            </div>

            <div class="d-none d-lg-flex align-items-center justify-content-center">
                <div class="bg-white bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                     style="width:160px;height:160px;">
                   <i class="bi bi-mortarboard text-white opacity-75" style="font-size:4.5rem;"></i>
                </div>
            </div>

        </div>

    </div>

    {{-- TITRE --}}
    <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
        <h2 class="section-title mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-grid-fill text-success"></i>
            Modules disponibles
        </h2>
    </div>

    {{-- MODULES --}}
    <div class="row g-4">

    @php
    $modules = [
        [
            'slug'=>'tableau-de-bord',
            'titre'=>'Tableau de bord',
            'desc'=>'Comprendre les statistiques, indicateurs et la navigation générale.',
            'icon'=>'bi-grid-1x2-fill',
            'color'=>'#198754',
            'bg'=>'rgba(25,135,84,.12)'
        ],

        [
            'slug'=>'utilisateurs',
            'titre'=>'Gestion des utilisateurs',
            'desc'=>'Créer, modifier, supprimer des comptes et gérer les rôles et permissions.',
            'icon'=>'bi-people-fill',
            'color'=>'#0d6efd',
            'bg'=>'rgba(13,110,253,.12)'
        ],

        [
            'slug'=>'materiel-informatique',
            'titre'=>'Matériel informatique',
            'desc'=>'Gérer le stock, affecter, retourner et suivre les équipements.',
            'icon'=>'bi-pc-display-horizontal',
            'color'=>'#f59e0b',
            'bg'=>'rgba(245,158,11,.14)'
        ],

        [
            'slug'=>'parc-auto',
            'titre'=>'Parc automobile',
            'desc'=>'Enregistrer les véhicules, gérer les affectations et l’historique.',
            'icon'=>'bi-car-front-fill',
            'color'=>'#dc3545',
            'bg'=>'rgba(220,53,69,.12)'
        ],

        [
            'slug'=>'mobilier',
            'titre'=>'Mobilier & bureau',
            'desc'=>'Gérer les fournitures, consommables et le mobilier administratif.',
            'icon'=>'bi-journal-album',
            'color'=>'#0dcaf0',
            'bg'=>'rgba(13,202,240,.12)'
        ],

        [
            'slug'=>'logiciels',
            'titre'=>'Logiciels & licences',
            'desc'=>'Suivre les licences, affectations et dates d’expiration.',
            'icon'=>'bi-box-seam-fill',
            'color'=>'#6f42c1',
            'bg'=>'rgba(111,66,193,.12)'
        ],

        [
            'slug'=>'inventaire',
            'titre'=>'Inventaire du parc',
            'desc'=>'Consulter, filtrer et exporter l’inventaire global.',
            'icon'=>'bi-journal-text',
            'color'=>'#fd7e14',
            'bg'=>'rgba(253,126,20,.12)'
        ],

        [
            'slug'=>'demandes',
            'titre'=>'Demandes',
            'desc'=>'Suivre les demandes de matériel et de maintenance.',
            'icon'=>'bi-envelope-fill',
            'color'=>'#20c997',
            'bg'=>'rgba(32,201,151,.12)'
        ],

        [
            'slug'=>'activites',
            'titre'=>'Activités & historique',
            'desc'=>'Consulter les journaux et l’historique des actions.',
            'icon'=>'bi-clock-history',
            'color'=>'#212529',
            'bg'=>'rgba(33,37,41,.10)'
        ],
    ];
    @endphp

        @foreach($modules as $i => $m)
        <div class="col-md-6 col-xl-4">

            <a href="{{ route('formation.module', $m['slug']) }}"
               class="module-card d-block shadow-sm"
               style="--accent: {{ $m['color'] }}; --icon-bg: {{ $m['bg'] }};">

                <div class="p-4 d-flex flex-column h-100">

                    <div class="d-flex gap-3 mb-3">

                        <div class="module-icon">
                            <i class="bi {{ $m['icon'] }}"></i>
                        </div>

                        <div>
                            <span class="module-badge">Module {{ $i+1 }}</span>
                            <h5 class="module-title mt-2 mb-0">
                                {{ $m['titre'] }}
                            </h5>
                        </div>

                    </div>

                    <p class="module-desc flex-grow-1">
                        {{ $m['desc'] }}
                    </p>
    <hr>
                    <div class="d-flex justify-content-between align-items-center">

                        <span class="module-link">Ouvrir</span>

                        <i class="bi bi-arrow-right module-link module-arrow"></i>

                    </div>

                </div>

            </a>

        </div>
        @endforeach

    </div>

    {{-- TIP --}}
    <div class="tip-box shadow-sm p-4 d-flex gap-3 align-items-start">

        <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
             style="width:48px;height:48px;">
            <i class="bi bi-lightbulb-fill"></i>
        </div>

        <div>
            <h6 class="fw-bold mb-1">Conseil</h6>
            <p class="text-muted mb-0">
                Suivez les modules dans l’ordre pour une meilleure compréhension du système.
            </p>
        </div>

    </div>

</div>

@endsection