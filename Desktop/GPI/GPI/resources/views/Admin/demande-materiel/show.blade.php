@extends('layouts.main-board')

@section('content')
<style>
    /* Custom CSS for the maintenance details page */
    :root {
        --primary-color: #3f6ad8;
        --success-color: #3ac47d;
        --warning-color: #f7b924;
        --danger-color: #d92550;
        --light-bg: #f5f7fa;
        --dark-text: #343a40;
        --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        --border-radius: 0.2rem;
    }

    .main-card {
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        background-color: #fff;
        overflow: hidden;
    }

    .main-header {        
        padding: 0.5rem;
        position: relative;
    }

    .main-header h1 {
        color: #009E60;
        margin: 0;
        font-weight: 600;
        font-size: 1.75rem;
        margin-bottom: 1rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
    }

    .info-card {
        border-radius: var(--border-radius);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        background: #fff;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.08);
        height: 100%;
    }

    .info-header {
        border-left: 5px solid rgb(255, 180, 6);
        background-color:rgb(255, 247, 223);
        display: flex;
        align-items: center;
        padding: 1rem;
    }

    .info-header h5 {
        margin: 0;
        font-weight: 600;
        font-size: 1.1rem;
        color: var(--dark-text);
    }

    .info-body {
        padding: 0;
    }

    .list-item {
        display: flex;
        justify-content: space-between;
        padding: 0.875rem 1rem;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .list-item:last-child {
        border-bottom: none;
    }

    .label {
        font-weight: 600;
        color: #566573;
    }

    .value {
        color: var(--dark-text);
        text-align: right;
    }

    .badge-custom {
        display: inline-flex;
        align-items: center;
        padding: 0.4rem 0.8rem;
        border-radius: 2rem;
        font-weight: 500;
        font-size: 0.875rem;
    }

    .badge-pending {
        background-color: rgba(247, 185, 36, 0.15);
        color: #9e6c0a;
    }

    .badge-approved {
        background-color: rgba(58, 196, 125, 0.15);
        color: #1e7344;
    }

    .badge-rejected {
        background-color: rgba(217, 37, 80, 0.15);
        color: #8c1a35;
    }

    .icon {
        margin-right: 0.5rem;
        display: inline-block;
        width: 1.2rem;
        height: 1.2rem;
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
    }

    .icon-person {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%233f6ad8' viewBox='0 0 16 16'%3E%3Cpath d='M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z'/%3E%3C/svg%3E");
    }

    .icon-tools {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%233f6ad8' viewBox='0 0 16 16'%3E%3Cpath d='M1 0L0 1l2.2 3.081a1 1 0 0 0 .815.419h.07a1 1 0 0 1 .708.293l2.675 2.675-2.617 2.654A3.003 3.003 0 0 0 0 13a3 3 0 1 0 5.878-.851l2.654-2.617.968.968-.305.914a1 1 0 0 0 .242 1.023l3.356 3.356a1 1 0 0 0 1.414 0l1.586-1.586a1 1 0 0 0 0-1.414l-3.356-3.356a1 1 0 0 0-1.023-.242L10.5 9.5l-.96-.96 2.68-2.643A3.005 3.005 0 0 0 16 3c0-.269-.035-.53-.102-.777l-2.14 2.141L12 4l-.364-1.757L13.777.102a3 3 0 0 0-3.675 3.68L7.462 6.46 4.793 3.793a1 1 0 0 1-.293-.707v-.071a1 1 0 0 0-.419-.814L1 0z'/%3E%3C/svg%3E");
    }

    .icon-calendar {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%233f6ad8' viewBox='0 0 16 16'%3E%3Cpath d='M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z'/%3E%3C/svg%3E");
    }

    .icon-actions {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%233f6ad8' viewBox='0 0 16 16'%3E%3Cpath d='M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z'/%3E%3C/svg%3E");
    }
    
    .icon-hourglass {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%239e6c0a' viewBox='0 0 16 16'%3E%3Cpath d='M2 1.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-1v1a4.5 4.5 0 0 1-2.557 4.06c-.29.139-.443.377-.443.59v.7c0 .213.154.451.443.59A4.5 4.5 0 0 1 12.5 13v1h1a.5.5 0 0 1 0 1h-11a.5.5 0 1 1 0-1h1v-1a4.5 4.5 0 0 1 2.557-4.06c.29-.139.443-.377.443-.59v-.7c0-.213-.154-.451-.443-.59A4.5 4.5 0 0 1 3.5 3V2h-1a.5.5 0 0 1-.5-.5zm2.5.5v1a3.5 3.5 0 0 0 1.989 3.158c.533.256 1.011.791 1.011 1.491v.702c0 .7-.478 1.235-1.011 1.491A3.5 3.5 0 0 0 4.5 13v1h7v-1a3.5 3.5 0 0 0-1.989-3.158C8.978 9.586 8.5 9.052 8.5 8.351v-.702c0-.7.478-1.235 1.011-1.491A3.5 3.5 0 0 0 11.5 3V2h-7z'/%3E%3C/svg%3E");
    }
    
    .icon-check {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%231e7344' viewBox='0 0 16 16'%3E%3Cpath d='M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z'/%3E%3C/svg%3E");
    }
    
    .icon-x {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%238c1a35' viewBox='0 0 16 16'%3E%3Cpath d='M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z'/%3E%3C/svg%3E");
    }

    /* Form elements */
    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #566573;
    }

    .form-control {
        display: block;
        width: 100%;
        padding: 0.6rem 0.75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: var(--dark-text);
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #cbd5e0;
        border-radius: 0.375rem;
        transition: border-color 0.15s ease-in-out;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        outline: 0;
        box-shadow: 0 0 0 3px rgba(63, 106, 216, 0.25);
    }

    .form-select {
        display: block;
        width: 100%;
        padding: 0.6rem 2.25rem 0.6rem 0.75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: var(--dark-text);
        background-color: #fff;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23343a40' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
        border: 1px solid #cbd5e0;
        border-radius: 0.375rem;
        appearance: none;
    }

    .btn {
        display: inline-block;
        font-weight: 500;
        line-height: 1.5;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        user-select: none;
        padding: 0.625rem 1.25rem;
        font-size: 0.95rem;
        border-radius: 0.375rem;
        transition: all 0.15s ease-in-out;
        border: none;
        margin-bottom: 0.5rem;
        width: 100%;
    }

    .btn-primary {
        background-color: var(--primary-color);
        color: white;
    }

    .btn-primary:hover {
        background-color: #345bc0;
    }

    .btn-success {
        background-color: var(--success-color);
        color: white;
    }

    .btn-success:hover {
        background-color: #2eaf6d;
    }

    /* Action buttons */
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
</style>

{{-- Select2 CSS (modal selects) --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="container-maintenance">
 
        <div class="main-header">
            <h1> <ion-icon name="receipt-outline"></ion-icon> Détails de la Demande de Matériel</h1>
        </div>
        
        <div class="info-grid">
            <!-- Requester Information -->
            <div class="info-card">
                <div class="info-header">
                    <ion-icon name="person-outline"></ion-icon>
                    <h5 class="info-title ms-2">Information du Demandeur</h5>
                </div>
                <div class="info-body">
                    <div class="list-item">
                        <span class="label">Nom:</span>
                        <span class="value">{{ $user->nom }}</span>
                    </div>
                    <div class="list-item">
                        <span class="label">Contact:</span>
                        <span class="value">{{ $user->contact }}</span>
                    </div>
                    <div class="list-item">
                        <span class="label">Email:</span>
                        <span class="value">{{ $user->email }}</span>
                    </div>
                    <div class="list-item">
                    <span class="label">Matricule:</span>
                    <span class="value">{{ $user->matricule }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Equipment Details -->
            <div class="info-card">
                <div class="info-header">
                    <ion-icon name="desktop-outline"></ion-icon>
                    <h5 class="info-title ms-2">Détails du Matériel</h5>
                </div>
                <div class="info-body">
                    <div class="list-item">
                        <span class="label">Matériel demandé:</span>
                        <span class="value">{{ $demandeMateriel->typ_mat }}</span>
                    </div>
                    <div class="list-item">
                        <span class="label">Description de la demande:</span>
                        <span class="value">{{ $demandeMateriel->desc_demande }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Maintenance Information -->
            <div class="info-card">
                <div class="info-header">
                    <ion-icon name="calendar-outline"></ion-icon>
                    <h5 class="info-title ms-2">Information du statut de la demande</h5>
                </div>
                <div class="info-body">
                    <div class="list-item">
                    <span class="label">Statut:</span>
                        @if($demandeMateriel->statut_dem == 'en attente chef' || $demandeMateriel->statut_dem == 'en attente admin'|| $demandeMateriel->statut_dem =='en attente GestParc')
                            <span class="badge-custom badge-pending">
                                <span class="icon icon-hourglass"></span>En attente
                            </span>
                        @elseif($demandeMateriel->statut_dem == 'approuvée')
                            <span class="badge-custom badge-approved">
                                <span class="icon icon-check"></span>Approuvée
                            </span>
                            @elseif($demandeMateriel->statut_dem == 'traitée')
                            <span class="badge-custom badge-approved">
                                <span class="icon icon-check"></span>Traitée
                            </span>
                        @elseif($demandeMateriel->statut_dem == 'annulée')
                            <span class="badge-custom badge-approved">
                                <span class="icon icon-check"></span>Annulée
                            </span>
                        @else
                            <span class="badge-custom badge-rejected">
                                <span class="icon icon-x"></span>Rejetée
                            </span>
                        @endif
                    </div>
                    <div class="list-item">
                        <span class="label">Priorité de la demande:</span>
                        <span class="value">{{ $demandeMateriel->priorite_dem }}</span>
                    </div>
                    <div class="list-item">
                        <span class="label">Date de la demande:</span>
                        <span class="value">{{ $demandeMateriel->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="list-item">
                        <span class="label">Heure de la demande:</span>
                        <span class="value">{{ $demandeMateriel->created_at->format('H:i') }}</span>
                    </div>
                </div>
            </div>
            
        
            <div class="info-card">
                <div class="info-header">
                    <ion-icon name="cog-outline"></ion-icon>
                    <h5 class="info-title ms-2">Actions</h5>
                </div>
                <div class="info-body" style="padding: 1rem;">
                    <form action="{{ route('demande-materiel.updateStatus', $demandeMateriel->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="statut_dem" class="form-label">Statut de la demande:</label>
                            <select name="statut_dem" id="statut_dem" class="ui select dropdown w-100">
                                <option value="traitée" {{ $demandeMateriel->statut_dem == 'traitée' ? 'selected' : '' }}>Traitée</option>
                            </select>
                        </div>
                        
                        <div class="action-buttons">
                            <button type="submit" class="btn btn-success">
                                Mettre à jour le statut
                            </button>
                            
                            <!--<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Assigner un equipement
                            </button>-->
                        </div>
                    </form>
                </div>
            </div>
 
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Titre du Modal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <form class="ui form" method="POST" action="{{ route('assign.equipement') }}">
                    @csrf

                    <!-- Sélection de l'utilisateur -->
                    <div class="form-group text-center">
                        <label style="margin-bottom: 10px;">Utilisateur</label>
                        <br>
                        <select class="fluid ui search dropdown" name="user_id" id="user_id" class="form-control custom-select" required>
                            <option value="" disabled selected>-- Sélectionnez un utilisateur --</option>
                                <option value="{{ $user->id }}">
                                    {{ $user->nom }} - {{ $user->matricule }}
                                </option>
                        </select>
                    </div>
                <br>
                    <!-- Sélection de l'équipement -->
                    <div class="form-group text-center">
                        <label style="margin-bottom: 10px;">Équipement</label>
                        <br>
                        <select class="fluid ui search dropdown" name="equipement_id" id="equipement_id" class="form-control custom-select" required>
                            <option value="" disabled selected>-- Sélectionnez un équipement --</option>
                            @foreach($equipements as $equipement)
                                <option value="{{ $equipement->id }}">
                                  {{ $equipement->des_equipement }} -  {{ $equipement->numero_serie }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Bouton de soumission -->
                    <button type="submit" class=" btn btn-success " style="margin-top: 20px;text-align: center;">
                        Attribuer <span uk-icon="check"></span>
                    </button>
                </form>
                </div>
            </div>
        </div>
    </div>

    
</div>

<script>
    // Modal handling functions
    function openMaintenanceModal() {
        const modal = document.getElementById('maintenanceModal');
        modal.style.display = 'flex';
        modal.classList.add('fade-in');
        document.body.style.overflow = 'hidden';
    }
    
    function closeMaintenanceModal() {
        const modal = document.getElementById('maintenanceModal');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('maintenanceModal');
        if (event.target === modal) {
            closeMaintenanceModal();
        }
    });
    
    // Handle form submission animations
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                const submitButton = this.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.textContent = 'Traitement en cours...';
                    submitButton.disabled = true;
                }
            });
        });
    });
</script>
@endsection

@push('scripts')
<!-- jQuery + Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function(){
    // Init Select2 for users (AJAX search)
    try {
        $('#user_id').select2({
            dropdownParent: $('#exampleModal'),
            width: '100%',
            placeholder: '-- Sélectionnez un utilisateur --',
            allowClear: true,
            minimumInputLength: 1,
            ajax: {
                url: "{{ route('users.search') }}",
                dataType: 'json',
                delay: 250,
                data: function(params){ return { q: params.term || '' }; },
                processResults: function(data){
                    return {
                        results: (Array.isArray(data) ? data : []).map(function(u){
                            return { id: u.id, text: (u.nom || '') + ' ' + (u.prenom || '') + ' - ' + (u.matricule || '') };
                        })
                    };
                },
                cache: true
            }
        });
    } catch(e) {
        console.error('Select2 user init error', e);
    }

    // Init Select2 for equipement (use existing options)
    try {
        $('#equipement_id').select2({
            dropdownParent: $('#exampleModal'),
            width: '100%'
        });
    } catch(e) {
        console.error('Select2 equipement init error', e);
    }

});
</script>
@endpush