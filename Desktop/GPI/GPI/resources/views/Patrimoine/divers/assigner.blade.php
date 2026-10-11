@extends('layouts.main-board')
@section('title', 'Assigner un article')
@section('content')

<div class="py-4">
    <h1 class="h3 fw-bold text-dark mb-1 d-flex align-items-center gap-2">
        <i class="bi bi-person-check"></i> Assigner un article
    </h1>
    <p class="text-muted">{{ $item->libelle }} — Stock disponible : <strong>{{ $item->nombre }}</strong></p>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<div class="card shadow-sm" style="max-width:600px">
    <div class="card-body p-4">
        <form action="{{ route('patrimoine-divers.assigner', $item->id) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Utilisateur <span class="text-danger">*</span></label>
                <select name="user_id" class="form-select" required>
                    <option value="">— Sélectionner un utilisateur —</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->nom }} {{ $user->prenom }} — {{ $user->matricule }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Quantité à assigner <span class="text-danger">*</span></label>
                <input type="number" name="quantite" class="form-control" value="1" min="1" max="{{ $item->nombre }}" required>
                <div class="form-text">Maximum disponible : {{ $item->nombre }}</div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle me-1"></i> Confirmer l'assignation
                </button>
                <a href="{{ route('patrimoine-divers.show', $item->id) }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

@endsection
