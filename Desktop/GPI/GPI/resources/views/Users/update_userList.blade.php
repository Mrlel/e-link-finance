@extends('layouts.main-board')
@section('title', 'Modifier un utilisateur')
@section('content')

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin/userlist') }}" class="text-decoration-none">Gestion du personnel</a>
                    </li>
                    <li class="breadcrumb-item active">Modifier {{ $users->nom }} {{ $users->prenom }}</li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold text-dark d-flex align-items-center gap-2">
                <i class="bi bi-pencil-square text-primary"></i> Modifier l'utilisateur
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            @if($errors->any())
                <div class="alert alert-danger border-0 shadow-sm mb-4">
                    <ul class="mb-0 small">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body p-4">
                    <form action="/update_user/traitement" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $users->id }}">

                        {{-- Identité --}}
                        <h6 class="fw-bold text-muted text-uppercase mb-3 border-bottom pb-2">
                            <i class="bi bi-card-text me-1"></i> Identité
                        </h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                                <input type="text" name="nom"
                                       class="form-control @error('nom') is-invalid @enderror"
                                       value="{{ old('nom', $users->nom) }}" required>
                                @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Prénom</label>
                                <input type="text" name="prenom"
                                       class="form-control @error('prenom') is-invalid @enderror"
                                       value="{{ old('prenom', $users->prenom) }}">
                                @error('prenom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Matricule <span class="text-danger">*</span></label>
                                <input type="text" name="matricule"
                                       class="form-control @error('matricule') is-invalid @enderror"
                                       value="{{ old('matricule', $users->matricule) }}" required>
                                @error('matricule')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $users->email) }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Contact <span class="text-danger">*</span></label>
                                <input type="text" name="contact"
                                       class="form-control @error('contact') is-invalid @enderror"
                                       value="{{ old('contact', $users->contact) }}" required>
                                @error('contact')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- Informations professionnelles --}}
                        <h6 class="fw-bold text-muted text-uppercase mb-3 border-bottom pb-2">
                            <i class="bi bi-briefcase me-1"></i> Informations professionnelles
                        </h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Emploi <span class="text-danger">*</span></label>
                                <input type="text" name="emploie"
                                       class="form-control @error('emploie') is-invalid @enderror"
                                       value="{{ old('emploie', $users->emploie) }}" required>
                                @error('emploie')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Fonction <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select name="fonction_id" class="form-select @error('fonction_id') is-invalid @enderror" required>
                                        @foreach($fonctions as $fonc)
                                            <option value="{{ $fonc->id }}" {{ old('fonction_id', $users->fonction_id) == $fonc->id ? 'selected' : '' }}>
                                                {{ $fonc->fonction }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#fonctionModal" title="Ajouter une fonction">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                    @error('fonction_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Grade <span class="text-danger">*</span></label>
                                <input type="text" name="grade"
                                       class="form-control @error('grade') is-invalid @enderror"
                                       value="{{ old('grade', $users->grade) }}" required>
                                @error('grade')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">1ère prise de service</label>
                                <input type="date" name="date_prise_service_un" class="form-control"
                                       value="{{ old('date_prise_service_un', $users->date_prise_service_un) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Prise de service actuelle</label>
                                <input type="date" name="date_prise_service" class="form-control"
                                       value="{{ old('date_prise_service', $users->date_prise_service) }}">
                            </div>
                        </div>

                        {{-- Droits d'accès --}}
                        <h6 class="fw-bold text-muted text-uppercase mb-3 border-bottom pb-2">
                            <i class="bi bi-shield-lock me-1"></i> Droits d'accès
                        </h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Rôle</label>
                                <select name="role" class="form-select">
                                    <option value="user" {{ old('role', $users->role) == 'user' ? 'selected' : '' }}>Agent</option>
                                    <option value="chef_de_service" {{ old('role', $users->role) == 'chef_de_service' ? 'selected' : '' }}>Chef de service</option>
                                    <option value="sous_directeur" {{ old('role', $users->role) == 'sous_directeur' ? 'selected' : '' }}>Sous directeur</option>
                                    <option value="directeur" {{ old('role', $users->role) == 'directeur' ? 'selected' : '' }}>Directeur</option>
                                    <option value="gestionnaire_parc" {{ old('role', $users->role) == 'gestionnaire_parc' ? 'selected' : '' }}>Gestionnaire de parc</option>
                                    <option value="technicien" {{ old('role', $users->role) == 'technicien' ? 'selected' : '' }}>Chef de service maintenance</option>
                                    <option value="admin" {{ old('role', $users->role) == 'admin' ? 'selected' : '' }}>Administrateur</option>
                                    <option value="point_focal" {{ old('role', $users->role) == 'point_focal' ? 'selected' : '' }}>Point focal</option>
                                    <option value="service_gestionnaire" {{ old('role', $users->role) == 'service_gestionnaire' ? 'selected' : '' }}>Service gestionnaire</option>
                                    <option value="ministre" {{ old('role', $users->role) == 'ministre' ? 'selected' : '' }}>Ministre</option>
                                </select>
                            </div>
                        </div>

                        <hr class="my-4 opacity-25">
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('admin/userlist') }}" class="btn btn-link text-muted text-decoration-none">Annuler</a>
                            <button type="submit" class="btn btn-success px-4">
                                <i class="bi bi-check-lg me-2"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4 d-none d-lg-block">
            <div class="card border-0 bg-primary bg-opacity-10">
                <div class="card-body">
                    <h6 class="fw-bold text-primary"><i class="bi bi-info-circle me-2"></i>Rappel</h6>
                    <p class="small mb-0">
                        Le matricule et l'email doivent rester uniques.
                        Cette page ne permet pas de réinitialiser le mot de passe — utilisez l'action dédiée depuis la liste des utilisateurs.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL AJOUT FONCTION (rapide, sans quitter la page) ===== --}}
<div class="modal fade" id="fonctionModal" tabindex="-1" aria-labelledby="fonctionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fonctionModalLabel">Ajouter une fonction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('fonctions.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <label class="form-label fw-semibold">Nom de la fonction <span class="text-danger">*</span></label>
                    <input type="text" name="fonction" class="form-control" placeholder="Ex: Informaticien" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-1"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
