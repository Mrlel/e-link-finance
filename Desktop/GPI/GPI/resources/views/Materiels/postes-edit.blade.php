@extends('layouts.main-board')
@section('title', 'Modifier un poste')
@section('content')

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('list_poste.index') }}" class="text-decoration-none">Postes informatiques</a>
                    </li>
                    <li class="breadcrumb-item active">Modifier {{ $poste->code_poste }}</li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold text-dark d-flex align-items-center gap-2">
                <i class="bi bi-pencil-square text-primary"></i> Modifier le poste
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            @if($errors->any())
                <div class="alert alert-danger border-0 shadow-sm mb-4">
                    <ul class="mb-0 small">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body p-4">
                    <form action="{{ route('postes.update', $poste->id) }}" method="POST">
                        @csrf @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Code poste <span class="text-danger">*</span></label>
                                <input type="text" name="code_poste"
                                       class="form-control @error('code_poste') is-invalid @enderror"
                                       value="{{ old('code_poste', $poste->code_poste) }}" required>
                                @error('code_poste')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Emplacement</label>
                                <input type="text" name="emplacement"
                                       class="form-control @error('emplacement') is-invalid @enderror"
                                       value="{{ old('emplacement', $poste->emplacement) }}">
                                @error('emplacement')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Utilisateur affecté</label>
                                <select name="user_id" class="form-select">
                                    <option value="">— Aucun —</option>
                                    @foreach($users ?? [] as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id', $poste->user_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->nom }} {{ $user->prenom }} — {{ $user->matricule }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">Changer l'utilisateur ici ne déplace pas les affectations d'équipements individuelles — utilisez « Affecter »/« Retirer » depuis la liste pour ça.</div>
                            </div>
                        </div>

                        <hr class="my-4 opacity-25">
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('list_poste.index') }}" class="btn btn-link text-muted text-decoration-none">Annuler</a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-lg me-2"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
