@extends('layouts.main-board')

@section('title', 'Formation — Mobilier & matériel de bureau')

@section('content')

@php $currentSlug = 'mobilier'; @endphp

<style>
    :root{
        --office-info:#0891b2;
        --office-info-soft:rgba(8,145,178,.10);
        --office-border:#e5e7eb;
        --office-muted:#6b7280;
        --office-text:#111827;
    }

    .formation-wrapper{
        max-width:1180px;
        margin:auto;
    }

    /* HERO */

    .hero-section{
        position:relative;
        overflow:hidden;
        background:linear-gradient(135deg,#ecfeff 0%,#ffffff 100%);
        border:1px solid #bae6fd;
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
        background:rgba(8,145,178,.08);
        top:-120px;
        right:-70px;
    }

    .hero-icon{
        width:68px;
        height:68px;
        border-radius:16px;
        background:var(--office-info-soft);
        display:flex;
        align-items:center;
        justify-content:center;
        flex-shrink:0;
    }

    .hero-icon i{
        font-size:1.9rem;
        color:var(--office-info);
    }

    .hero-title{
        font-size:2rem;
        font-weight:800;
        color:var(--office-text);
        margin-bottom:.35rem;
    }

    .hero-text{
        color:var(--office-muted);
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
        border-bottom:1px solid var(--office-border);
        background:#fff;
    }

    .module-number{
        width:40px;
        height:40px;
        border-radius:12px;
        background:var(--office-info-soft);
        color:var(--office-info);
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
        color:var(--office-text);
    }

    .module-body{
        padding:1.5rem;
    }

    /* TABLE */

    .modern-table{
        overflow:hidden;
        border:1px solid #e5e7eb;
        border-radius:14px;
    }

    .modern-table table{
        margin-bottom:0;
    }

    .modern-table thead{
        background:#0f172a;
        color:#fff;
    }

    .modern-table th{
        font-weight:600;
        padding:1rem;
        border:none;
        font-size:.92rem;
    }

    .modern-table td{
        padding:1rem;
        vertical-align:middle;
        border-color:#eef2f7;
        font-size:.93rem;
        color:#4b5563;
    }

    .modern-table tbody tr:hover{
        background:#f8fafc;
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

    /* STOCK BOX */

    .stock-box{
        background:#f8fafc;
        border:1px solid #e5e7eb;
        border-radius:14px;
        padding:1.2rem;
        margin-top:1rem;
    }

    .stock-box p{
        margin-bottom:0;
        color:#4b5563;
        line-height:1.7;
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

        .modern-table{
            border-radius:12px;
        }
    }
</style>

<div class="formation-wrapper py-4">

    @include('formation.partials.nav')

    {{-- HERO --}}
    <div class="hero-section">

        <div class="d-flex align-items-start gap-3">

            <div class="hero-icon">
                <i class="bi bi-journal-album"></i>
            </div>

            <div>

                <h1 class="hero-title">
                    Mobilier & matériel de bureau
                </h1>

                <p class="hero-text">
                    Gérez les fournitures, consommables,
                    mobiliers et matériels de bureau,
                    tout en assurant le suivi du stock,
                    des assignations et des réapprovisionnements.
                </p>

            </div>

        </div>

    </div>

    {{-- AJOUT --}}
    <div class="module-card">

        <div class="module-header">
            <div class="module-number">01</div>
            <h2 class="module-title">Ajouter un article</h2>
        </div>

        <div class="module-body">

            <p class="text-muted mb-4">
                Accédez à
                <strong>Patrimoine → Mobilier & matériel de bureau</strong>
                puis cliquez sur
                <span class="badge bg-success rounded-pill px-3 py-2">
                    <i class="bi bi-plus-circle me-1"></i>
                    Ajouter un article
                </span>
            </p>

            <div class="modern-table table-responsive">

                <table class="table align-middle">

                    <thead>
                        <tr>
                            <th>Champ</th>
                            <th>Description</th>
                            <th>Obligatoire</th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr>
                            <td><strong>Libellé</strong></td>
                            <td>Nom de l’article (Cartouche HP, Chaise de bureau...)</td>
                            <td><span class="badge bg-danger rounded-pill px-3">Oui</span></td>
                        </tr>

                        <tr>
                            <td><strong>Quantité</strong></td>
                            <td>Nombre d’unités disponibles en stock</td>
                            <td><span class="badge bg-danger rounded-pill px-3">Oui</span></td>
                        </tr>

                        <tr>
                            <td><strong>Catégorie</strong></td>
                            <td>Fournitures, Consommables ou Autre</td>
                            <td><span class="badge bg-secondary rounded-pill px-3">Non</span></td>
                        </tr>

                        <tr>
                            <td><strong>État</strong></td>
                            <td>Neuf, Bon ou Usé</td>
                            <td><span class="badge bg-danger rounded-pill px-3">Oui</span></td>
                        </tr>

                        <tr>
                            <td><strong>Date d’acquisition</strong></td>
                            <td>Date d’entrée en stock</td>
                            <td><span class="badge bg-secondary rounded-pill px-3">Non</span></td>
                        </tr>

                    </tbody>

                </table>

            </div>

            <div class="image-placeholder">

                <i class="bi bi-image"></i>

                <p>
                    [ Capture d’écran : Formulaire d’ajout d’article ]
                </p>

            </div>

        </div>

    </div>

    {{-- ASSIGNATION --}}
    <div class="module-card">

        <div class="module-header">
            <div class="module-number">02</div>
            <h2 class="module-title">Assigner un article</h2>
        </div>

        <div class="module-body">

            <ol class="modern-list">

                <li>
                    Depuis la liste des articles,
                    ouvrez les actions puis cliquez sur
                    <strong>Assigner</strong>.
                </li>

                <li>
                    Sélectionnez l’utilisateur
                    puis indiquez la quantité à attribuer.
                </li>

                <li>
                    Confirmez l’opération.
                    Le stock disponible est automatiquement décrémenté.
                </li>

                <li>
                    Le statut passe automatiquement à
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                        Partiellement assigné
                    </span>
                    ou
                    <span class="badge bg-danger rounded-pill px-3 py-2">
                        Épuisé
                    </span>
                    selon le stock restant.
                </li>

            </ol>

            <div class="image-placeholder">

                <i class="bi bi-image"></i>

                <p>
                    [ Capture d’écran : Modal d’assignation ]
                </p>

            </div>

        </div>

    </div>

    {{-- RETOUR --}}
    <div class="module-card">

        <div class="module-header">
            <div class="module-number">03</div>
            <h2 class="module-title">Retour d’un article</h2>
        </div>

        <div class="module-body">

            <ol class="modern-list">

                <li>
                    Ouvrez la fiche de l’article concerné.
                </li>

                <li>
                    Dans la section des assignations,
                    cliquez sur
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                        <i class="bi bi-arrow-return-left me-1"></i>
                        Retourner
                    </span>
                </li>

                <li>
                    Ajoutez un commentaire optionnel.
                </li>

                <li>
                    La quantité est automatiquement réintégrée au stock.
                </li>

            </ol>

            <div class="image-placeholder">

                <i class="bi bi-image"></i>

                <p>
                    [ Capture d’écran : Retour d’article ]
                </p>

            </div>

        </div>

    </div>

    {{-- REAPPROVISIONNEMENT --}}
    <div class="module-card">

        <div class="module-header">
            <div class="module-number">04</div>
            <h2 class="module-title">Réapprovisionner le stock</h2>
        </div>

        <div class="module-body">

            <div class="stock-box">

                <p>
                    Lorsqu’un article devient insuffisant,
                    vous pouvez augmenter le stock
                    sans recréer une nouvelle fiche article.
                </p>

            </div>

            <ol class="modern-list mt-4">

                <li>
                    Dans la liste des articles,
                    cliquez sur <strong>Réapprovisionner</strong>.
                </li>

                <li>
                    Indiquez la quantité à ajouter
                    ainsi qu’un commentaire de suivi.
                </li>

                <li>
                    Le stock est immédiatement mis à jour
                    et l’opération enregistrée dans l’historique.
                </li>

            </ol>

            <div class="image-placeholder">

                <i class="bi bi-image"></i>

                <p>
                    [ Capture d’écran : Modal de réapprovisionnement ]
                </p>

            </div>

        </div>

    </div>

    @include('formation.partials.module-footer', [
        'prev' => ['slug'=>'parc-auto','titre'=>'Parc automobile'],
        'next' => ['slug'=>'logiciels','titre'=>'Logiciels & licences']
    ])

</div>

@endsection