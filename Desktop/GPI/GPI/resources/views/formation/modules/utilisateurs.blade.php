@extends('layouts.main-board')

@section('title', 'Formation — Gestion des utilisateurs')

@section('content')

@php $currentSlug = 'utilisateurs'; @endphp

<style>
    :root{
        --user-primary:#2563eb;
        --user-primary-soft:rgba(37,99,235,.10);
        --user-border:#e5e7eb;
        --user-muted:#6b7280;
        --user-text:#111827;
        --user-bg:#f8fafc;
                --soft-bg:#f5f7fb;
    }
   body{
        background: var(--soft-bg) important!;
    }

    .formation-wrapper{
        max-width:1180px;
        margin:auto;
    }

    /* HERO */

    .hero-section{
        position:relative;
        overflow:hidden;
        background:linear-gradient(135deg,#eff6ff 0%,#ffffff 100%);
        border:1px solid #dbeafe;
        border-radius:18px;
        padding:2rem;
        margin-bottom:2rem;
    }

    .hero-section::after{
        content:"";
        position:absolute;
        width:220px;
        height:220px;
        border-radius:50%;
        background:rgba(37,99,235,.08);
        top:-110px;
        right:-70px;
    }

    .hero-icon{
        width:68px;
        height:68px;
        border-radius:16px;
        background:var(--user-primary-soft);
        display:flex;
        align-items:center;
        justify-content:center;
        flex-shrink:0;
    }

    .hero-icon i{
        font-size:1.9rem;
        color:var(--user-primary);
    }

    .hero-title{
        font-size:2rem;
        font-weight:800;
        color:var(--user-text);
        margin-bottom:.3rem;
    }

    .hero-text{
        color:var(--user-muted);
        line-height:1.7;
        max-width:720px;
        margin-bottom:0;
    }

    /* SECTION */

    .module-card{
        border:none;
        border-radius:16px;
        overflow:hidden;
        background:#fff;
        margin-bottom:1.8rem;
        box-shadow:0 8px 24px rgba(15,23,42,.05);
    }

    .module-header{
        display:flex;
        align-items:center;
        gap:1rem;
        padding:1rem 1.4rem;
        border-bottom:1px solid var(--user-border);
        background:#fff;
    }

    .module-number{
        width:40px;
        height:40px;
        border-radius:12px;
        background:var(--user-primary-soft);
        display:flex;
        align-items:center;
        justify-content:center;
        color:var(--user-primary);
        font-weight:700;
        flex-shrink:0;
    }

    .module-title{
        font-size:1.05rem;
        font-weight:700;
        color:var(--user-text);
        margin-bottom:0;
    }

    .module-body{
        padding:1.5rem;
    }

    /* ROLE CARDS */

    .role-card{
        height:100%;
        border:1px solid var(--user-border);
        border-radius:14px;
        padding:1.5rem;
        background:#fff;
        transition:.2s ease;
    }

    .role-card:hover{
        transform:translateY(-3px);
        box-shadow:0 10px 24px rgba(0,0,0,.05);
    }

    .role-icon{
        width:58px;
        height:58px;
        border-radius:14px;
        display:flex;
        align-items:center;
        justify-content:center;
        margin-bottom:1rem;
    }

    .role-icon i{
        font-size:1.5rem;
    }

    .role-card h6{
        font-weight:700;
        margin-bottom:.7rem;
        color:#111827;
    }

    .role-card p{
        color:var(--user-muted);
        line-height:1.6;
        font-size:.93rem;
        margin-bottom:0;
    }

    .danger-soft{
        background:rgba(239,68,68,.10);
        color:#dc2626;
    }

    .primary-soft{
        background:rgba(37,99,235,.10);
        color:#2563eb;
    }

    .success-soft{
        background:rgba(34,197,94,.10);
        color:#16a34a;
    }

    /* IMAGE */

    .image-box{
        margin-top:1.5rem;
        background:#fafafa;
        border:2px dashed #d1d5db;
        border-radius:14px;
        padding:1rem;
    }

    .image-box img{
        width:100%;
        border-radius:10px;
        display:block;
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

    /* ACTION BOX */

    .action-box{
        border:1px solid var(--user-border);
        border-radius:14px;
        padding:1.2rem;
        height:100%;
        background:#fff;
    }

    .action-box h6{
        font-weight:700;
        margin-bottom:1rem;
        color:#111827;
    }

    .action-box ol{
        margin-bottom:0;
        padding-left:1rem;
    }

    .action-box li{
        margin-bottom:.8rem;
        color:#4b5563;
    }

    .warning-alert{
        background:#fff7ed;
        border:1px solid #fed7aa;
        border-radius:12px;
        padding:.9rem 1rem;
        color:#9a3412;
        margin-top:1rem;
        font-size:.9rem;
    }

    .placeholder-box{
        border:2px dashed #d1d5db;
        border-radius:14px;
        background:#fafafa;
        padding:3rem 1rem;
        text-align:center;
        margin-top:1.5rem;
    }

    .placeholder-box i{
        font-size:3rem;
        color:#c4c4c4;
    }

    .placeholder-box p{
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
                <i class="bi bi-people-fill"></i>
            </div>

            <div>

                <h1 class="hero-title">
                    Gestion des utilisateurs
                </h1>

                <p class="hero-text">
                    Gérez les comptes utilisateurs, les rôles,
                    les accès et les opérations administratives
                    liées aux agents de votre direction.
                </p>

            </div>

        </div>

    </div>

    {{-- ROLES --}}
    <div class="module-card">

        <div class="module-header">
            <div class="module-number">01</div>
            <h2 class="module-title">Les rôles du système</h2>
        </div>

        <div class="module-body">

            <p class="text-muted mb-4">
                Chaque utilisateur dispose d’un rôle définissant ses permissions dans le système.
            </p>

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="role-card">

                        <div class="role-icon danger-soft">
                            <i class="bi bi-shield-fill-check"></i>
                        </div>

                        <h6>Super Admin</h6>

                        <p>
                            Accès total à toutes les directions,
                            gestion des administrateurs et
                            configuration globale de la plateforme.
                        </p>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="role-card">

                        <div class="role-icon primary-soft">
                            <i class="bi bi-person-gear"></i>
                        </div>

                        <h6>Admin</h6>

                        <p>
                            Responsable de la gestion des utilisateurs,
                            équipements et demandes de sa direction.
                        </p>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="role-card">

                        <div class="role-icon success-soft">
                            <i class="bi bi-person"></i>
                        </div>

                        <h6>Utilisateur</h6>

                        <p>
                            Consulte ses équipements,
                            effectue des demandes et suit ses affectations.
                        </p>

                    </div>

                </div>

            </div>

            <div class="image-box">
                <img src="/formation/utilisateurs.png"
                     alt="Liste des utilisateurs">
            </div>

        </div>

    </div>

    {{-- CREER --}}
    <div class="module-card">

        <div class="module-header">
            <div class="module-number">02</div>
            <h2 class="module-title">Créer un utilisateur</h2>
        </div>

        <div class="module-body">

            <p class="text-muted">
                Seul un administrateur peut créer des comptes utilisateurs pour sa direction.
            </p>

            <ol class="modern-list">

                <li>
                    Ouvrez le module
                    <strong>Gestion Utilisateurs</strong>.
                </li>

                <li>
                    Cliquez sur
                    <span class="badge bg-primary rounded-pill px-3 py-2">
                        <i class="bi bi-plus-circle me-1"></i>
                        Ajouter un utilisateur
                    </span>
                </li>

                <li>
                    Remplissez les informations du formulaire :
                    nom, matricule, email, rôle et fonction.
                </li>

                <li>
                    Enregistrez pour envoyer automatiquement
                    les identifiants provisoires par email.
                </li>

            </ol>

            <div class="image-box">
                <img src="/formation/ajouter_utilisateur.png"
                     alt="Formulaire utilisateur">
            </div>

        </div>

    </div>

    {{-- ACTIONS --}}
    <div class="module-card">

        <div class="module-header">
            <div class="module-number">03</div>
            <h2 class="module-title">
                Modifier, réinitialiser et supprimer
            </h2>
        </div>

        <div class="module-body">

            <div class="row g-4">

                <div class="col-md-6">

                    <div class="action-box">

                        <h6>
                            <i class="bi bi-pencil-square text-primary me-2"></i>
                            Modifier
                        </h6>

                        <ol>
                            <li>Cliquez sur Modifier dans la liste.</li>
                            <li>Modifiez les informations nécessaires.</li>
                            <li>Enregistrez les changements.</li>
                        </ol>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="action-box">

                        <h6>
                            <i class="bi bi-desktop text-primary me-2"></i>
                            Équipements assignés
                        </h6>

                        <ol>
                            <li>Accédez au bouton Équipements assignés.</li>
                            <li>Consultez tous les matériels liés à l’utilisateur.</li>
                        </ol>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="action-box">

                        <h6>
                            <i class="bi bi-key text-primary me-2"></i>
                            Réinitialiser
                        </h6>

                        <ol>
                            <li>Cliquez sur l’action de réinitialisation.</li>
                            <li>Confirmez l’opération.</li>
                        </ol>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="action-box">

                        <h6>
                            <i class="bi bi-trash text-danger me-2"></i>
                            Supprimer
                        </h6>

                        <ol>
                            <li>Cliquez sur l’icône de suppression.</li>
                            <li>Confirmez la boîte de dialogue.</li>
                            <li>La suppression est irréversible.</li>
                        </ol>

                        <div class="warning-alert">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            Un utilisateur possédant des équipements affectés
                            ne peut pas être supprimé directement.
                        </div>

                    </div>

                </div>

            </div>

            <div class="image-box">
                <img src="/formation/action.jpeg"
                     height="400" width="200"
                     alt="Actions utilisateur">
            </div>

        </div>

    </div>

    {{-- IMPORT --}}
    <div class="module-card" hidden>

        <div class="module-header">
            <div class="module-number">04</div>
            <h2 class="module-title">Import en masse</h2>
        </div>

        <div class="module-body">

            <p class="text-muted">
                Importez plusieurs utilisateurs simultanément via un fichier Excel.
            </p>

            <ol class="modern-list">

                <li>Téléchargez le modèle Excel.</li>

                <li>
                    Remplissez les colonnes :
                    nom, prénom, matricule, email, rôle et fonction.
                </li>

                <li>
                    Cliquez sur
                    <span class="badge bg-secondary rounded-pill px-3 py-2">
                        <i class="bi bi-upload me-1"></i>
                        Importer
                    </span>
                </li>

                <li>
                    Les comptes sont automatiquement créés
                    et les emails envoyés.
                </li>

            </ol>

            <div class="placeholder-box">

                <i class="bi bi-image"></i>

                <p>
                    [ Capture d’écran : Import Excel utilisateurs ]
                </p>

            </div>

        </div>

    </div>

    @include('formation.partials.module-footer', [
        'prev' => ['slug'=>'tableau-de-bord','titre'=>'Tableau de bord'],
        'next' => ['slug'=>'materiel-informatique','titre'=>'Matériel informatique']
    ])

</div>

@endsection