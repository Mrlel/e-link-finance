@extends('layouts.main-board')

@section('content')

<h1>Détails de la Maintenance</h1>
<div>
    <p><strong>Nom de l'utilisateur :</strong> {{ $maintenance->demandeMaintenance->user->nom ?? '—' }} {{ $maintenance->demandeMaintenance->user->prenom ?? '' }}</p>
    <p><strong>Équipement :</strong> {{ $maintenance->demandeMaintenance->equipement->des_equipement ?? '—' }}</p>
    <p><strong>Description :</strong> {{ $maintenance->description }}</p>
    <p><strong>Type :</strong> {{ $maintenance->type_maintenance }}</p>
    <p><strong>Statut :</strong> {{ $maintenance->statut_maintenance }}</p>
    <p><strong>Date de réalisation :</strong> {{ $maintenance->date_realisation?->format('d/m/Y') ?? '—' }}</p>
</div>

<a href="/admin-dashboard" class="btn btn-secondary">Retour</a>

@endsection
