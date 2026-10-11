@extends('layouts.main-board')
@section('title', 'Formation — Activités & historique')
@section('content')

@php $currentSlug = 'activites'; @endphp

<style>
    :root{
        --primary:#6f42c1;
        --primary-light:rgba(111,66,193,0.10);
        --border:#ece8f8;
        --radius:12px;
    }

    .formation-card{
        border:1px solid var(--border);
        border-radius:var(--radius);
        box-shadow:0 2px 10px rgba(15,23,42,0.05);
        background:#fff;
    }

    .formation-header{
        background:var(--primary);
        color:#fff;
        border-radius:var(--radius) var(--radius) 0 0;
        padding:14px 18px;
        font-weight:600;
        display:flex;
        align-items:center;
        gap:8px;
    }

    .formation-icon{
        width:56px;
        height:56px;
        border-radius:12px;
        background:var(--primary-light);
        color:var(--primary);
    }

    .soft-box{
        border:1px dashed var(--border);
        background:#faf8ff;
        border-radius:12px;
        padding:28px;
        text-align:center;
    }

    .soft-box i{
        font-size:2.2rem;
        color:#b8a7e6;
    }
</style>

<div class="py-4">

    @include('formation.partials.nav')

    {{-- HEADER --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="formation-icon d-flex align-items-center justify-content-center">
            <i class="bi bi-clock-history fs-3"></i>
        </div>
        <div>
            <h1 class="h3 fw-bold mb-0">Activités & historique</h1>
            <p class="text-muted mb-0">Journal des opérations et suivi des affectations</p>
        </div>
    </div>

    {{-- HISTORIQUE AFFECTATION --}}
    <div class="formation-card mb-4">

        <div class="formation-header">
            <i class="bi bi-1-circle"></i>
            Historique d'affectation
        </div>

        <div class="p-4">

            <p>
                Accédez à <strong>Activités → Historique d'affectation</strong> pour consulter tous les mouvements d’équipements.
            </p>

            <h6 class="fw-bold mt-3">Informations disponibles :</h6>
            <ul>
                <li>Équipement concerné</li>
                <li>Utilisateur affecté</li>
                <li>Agent ayant effectué l’opération</li>
                <li>Date d’affectation et de retour</li>
                <li>État de retour + commentaires</li>
            </ul>

            <div class="soft-box mt-3">
                <i class="bi bi-image"></i>
                <p class="text-muted small mt-2 mb-0">Capture d'écran : historique d'affectation</p>
            </div>

        </div>
    </div>

    {{-- JOURNAL --}}
    <div class="formation-card mb-4">

        <div class="formation-header">
            <i class="bi bi-2-circle"></i>
            Journal d’activités (Admin uniquement)
        </div>

        <div class="p-4">

            <p>
                Le journal trace toutes les actions effectuées dans le système.
            </p>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td><span class="badge bg-success">ajout</span></td><td>Création d’un élément</td></tr>
                        <tr><td><span class="badge bg-primary">modification</span></td><td>Mise à jour</td></tr>
                        <tr><td><span class="badge bg-danger">suppression</span></td><td>Suppression</td></tr>
                        <tr><td><span class="badge text-white" style="background:var(--primary)">attribution</span></td><td>Affectation</td></tr>
                        <tr><td><span class="badge bg-warning text-dark">retour</span></td><td>Retour équipement</td></tr>
                        <tr><td><span class="badge bg-dark">import</span></td><td>Import Excel</td></tr>
                    </tbody>
                </table>
            </div>

            <div class="alert alert-warning mt-3">
                <i class="bi bi-shield-lock me-1"></i>
                Accessible uniquement aux administrateurs.
            </div>

            <div class="soft-box mt-3">
                <i class="bi bi-image"></i>
                <p class="text-muted small mt-2 mb-0">Capture d'écran : journal d'activités</p>
            </div>

        </div>
    </div>

    {{-- SORTIES --}}
    <div class="formation-card mb-4">

        <div class="formation-header">
            <i class="bi bi-3-circle"></i>
            Sorties d’équipements
        </div>

        <div class="p-4">

            <ul>
                <li>Filtrage par type de sortie</li>
                <li>Suivi des retours</li>
                <li>Alertes sur retards</li>
            </ul>

            <div class="soft-box mt-3">
                <i class="bi bi-image"></i>
                <p class="text-muted small mt-2 mb-0">Capture d'écran : sorties équipements</p>
            </div>

        </div>
    </div>

    {{-- FIN --}}
    <div class="formation-card">

        <div class="p-4 text-white"
             style="background:linear-gradient(135deg,#6f42c1,#4c2a91); border-radius:12px;">

            <h5 class="fw-bold">🎓 Formation terminée</h5>
            <p class="mb-3 opacity-75">
                Vous maîtrisez maintenant tout le système de gestion.
            </p>

            <div class="d-flex gap-2 flex-wrap">
                <a href="#" class="btn btn-light text-primary fw-semibold">
                    Tableau de bord
                </a>
                <a href="{{ route('formation.index') }}" class="btn btn-outline-light">
                    Revoir la formation
                </a>
            </div>

        </div>

    </div>

    @include('formation.partials.module-footer', [
        'prev' => ['slug'=>'demandes','titre'=>'Demandes'],
        'next' => null
    ])

</div>

@endsection