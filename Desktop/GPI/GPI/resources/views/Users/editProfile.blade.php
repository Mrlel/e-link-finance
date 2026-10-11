@extends('layouts.user-section')

@section('content')

<div class="profile-container">

    <!-- Header -->
    <div class="profile-header">
        <h2>Mon Profil</h2>
        <button class="edit-btn" data-bs-toggle="modal" data-bs-target="#editProfileModal">
            <i class="fas fa-user-edit"></i> Modifier
        </button>
    </div>

    <!-- Card Profil -->
    <div class="profile-card">
        <div class="avatar-section">
            <div class="avatar">
                <i class="fas fa-user"></i>
            </div>
        </div>

        <div class="info-section">

            <div class="info-item">
                <span class="label"><i class="fas fa-user"></i> Nom complet</span>
                <span class="value">{{ auth()->user()->nom }} {{ auth()->user()->prenom }}</span>
            </div>

            <div class="info-item">
                <span class="label"><i class="fas fa-envelope"></i> Email</span>
                <span class="value">{{ auth()->user()->email }}</span>
            </div>

            <div class="info-item">
                <span class="label"><i class="fas fa-phone"></i> Contact</span>
                <span class="value">{{ auth()->user()->contact ?? 'Non renseigné' }}</span>
            </div>

            <div class="info-item">
                <span class="label"><i class="fas fa-briefcase"></i> Rôle</span>
                <span class="value badge bg-primary">{{ auth()->user()->role }}</span>
            </div>

        </div>
    </div>

</div>




{{-- ------------------------------------------------------ --}}
{{--  MODAL DE MODIFICATION DU PROFIL                      --}}
{{-- ------------------------------------------------------ --}}
<div class="modal fade" id="editProfileModal" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="POST" action="{{ route('profile.update') }}">
        @csrf

      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Modifier le profil</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <!-- Email -->
        <div class="mb-3">
            <label class="form-label"><i class="fas fa-envelope"></i> Email</label>
            <input type="email" class="form-control" name="email" value="{{ auth()->user()->email }}" required>
        </div>

        <!-- Contact -->
        <div class="mb-3">
            <label class="form-label"><i class="fas fa-phone"></i> Contact</label>
            <input type="text" class="form-control" name="contact" value="{{ auth()->user()->contact }}">
        </div>

        <!-- Nouveau mot de passe -->
        <div class="mb-3">
            <label class="form-label"><i class="fas fa-lock"></i> Nouveau mot de passe</label>
            <input type="password" class="form-control" name="password">
        </div>

        <!-- Confirmation -->
        <div class="mb-3">
            <label class="form-label">Confirmer le mot de passe</label>
            <input type="password" class="form-control" name="password_confirmation">
        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
      </div>

    </form>
  </div>
</div>



@endsection


<style>
    .profile-container {
        max-width: 700px;
        margin: auto;
        margin-top: 20px;
    }

    .profile-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .profile-header h2 {
        font-weight: 700;
        margin: 0;
    }

    .edit-btn {
        background: #007bff;
        color: white;
        padding: 8px 14px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        display: flex;
        gap: 7px;
        align-items: center;
        transition: 0.2s;
    }

    .edit-btn:hover {
        background: #0056b3;
    }

    .profile-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: rgba(0,0,0,0.1) 0px 2px 8px;
        display: flex;
        gap: 20px;
    }

    .avatar-section {
        display: flex;
        align-items: center;
    }

    .avatar {
        background: #e9ecef;
        width: 90px;
        height: 90px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 40px;
        color: #6c757d;
    }

    .info-section {
        flex: 1;
    }

    .info-item {
        margin-bottom: 10px;
    }

    .label {
        font-size: 13px;
        font-weight: 600;
        color: #777;
    }

    .value {
        display: block;
        font-size: 16px;
        margin-top: 3px;
    }

</style>


@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
