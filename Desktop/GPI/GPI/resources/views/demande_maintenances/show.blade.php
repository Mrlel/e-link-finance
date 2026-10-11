@extends('layouts.main-board')

@section('content')
<style>
    /* Custom CSS for the maintenance details page */
    :root {
        --primary-color: #009E60;
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
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%233f6ad8' viewBox='0 0 16 16'%3E%3Cpath d='M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 极 0 0 1 1h12a1 1 0 0 0 1-1V4H1z'/%3E%3C/svg%3E");
    }

    .icon-actions {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%233f6ad8' viewBox='0 0 16 16'%3E%3Cpath d='M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 极 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.极 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z'/%3E极/svg%3E");
    }
    
    .icon-hourglass {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='极/w.w3.org/2000/svg' fill='%239e6c0a' viewBox='0 0 16 16'%3E%3Cpath d='M2 1.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-1v1a4.5 4.5 0 0 1-2.557 4.06c-.29.139-.443.377-.443.59v.7c0 .213.154.451.443.59A4.5 4.5 0 0 1 12.5 13v1极h1a.5.5 0 0 1 0 1h-11a.5.5 0 1 1 0-1h1v-1a4.5 4.5 0 0 1 2.557-4.06c.29-.139.443-.377.443-.59v-.7c0-.213-.154-.451-.443-.59A4.5 4.5 0 0 1 3.5 3V2h-1a.5.5 0 0 1-.5-.5zm2.5.5v1a3.5 3.5 0 0 0 1.989 3.158c.533.256 1.011.791 1.011 1.491v.702c0 .7-.478 1.235-1.011 1.491A3.5 3.5 0 0 0 4.5 13v1h7v-1a3.5 3.5 0 0 0-1.989-3.158C8.978 9.586 8.5 9.052 8.5 8.351v-.702c0-.7.478-1.235 1.011-1.491A3.5 3.5 0 0 0 11.5 3V2h-7z'/%3E%3C/svg%3E");
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
        box-shadow: 0 0 极 3px rgba(63, 106, 216, 0.25);
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

    /* Modal styling */
    .modal-custom {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1050;
    }

    .modal-dialog {
        width: 100%;
        max-width: 550px;
        margin: 1.75rem;
    }

    .modal-content {
        position: relative;
        display: flex;
        flex-direction: column;
        background-color: #fff;
        border-radius: var(--border-radius);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.25rem;
        background: linear-gradient(135deg, var(--primary-color), #2d5631ff);
        color: white;
    }

    .modal-title {
        margin: 0;
        font-weight: 600;
        font-size: 1.25rem;
    }

    .modal-close {
        background: transparent;
        border: none;
        font-size: 1.5rem;
        color: white;
        cursor: pointer;
        padding: 0;
        margin: 0;
        width: auto;
        opacity: 0.8;
        transition: opacity 0.15s;
    }

    .modal-极 close:hover {
        opacity: 1;
    }

    .modal-body {
        padding: 1.5rem;
    }

    /* Animation for modal */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideIn {
        from { transform: translateY(-50px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .fade-in {
        animation: fadeIn 0.3s ease-out;
    }

    .slide-in {
        animation: slideIn 0.3s ease-out;
    }
</style>

<div class="container-maintenance">
<header class="my-4">
  <h1 class="h3 fw-bold text-dark mb-1 d-flex align-items-center">
    Détails de la Demande de Maintenance 
  </h1>
  <p class="text-muted mb-0">
    Détails de la demande • informatiques
  </p>
</header>
    
    <div class="info-grid">
        <!-- Requester Information -->
        <div class="info-card">
            <div class="info-header">
                <span class="icon icon-person"></span>
                <h5>Information du Demandeur</h5>
            </div>
            <div class="info-body">
                <div class="list-item">
                    <span class="label">Nom:</span>
                    <span class="value">{{ $demande->user->nom }} {{ $demande->user->prenom }}</span>
                </div>
                <div class="list-item">
                    <span class="label">Contact:</span>
                    <span class="value">{{ $demande->user->contact }}</span>
                </div>
                <div class="list-item">
                    <span class="label">Email:</span>
                    <span class="value">{{ $demande->user->email }}</span>
                </div>
                <div class="list-item">
                    <span class="label">Statut:</span>
                   @php
                    $statusClass = 'badge-pending';
                    $statusIcon = 'icon-hourglass';
                    $statusText = 'En attente';

                    if ($demande->statut_dmtc == 'approuvée') {
                        $statusClass = 'badge-approved';
                        $statusIcon = 'icon-check';
                        $statusText = 'Approuvée';
                    } elseif ($demande->statut_dmtc == 'traitée') {
                        $statusClass = 'badge-approved';
                        $statusIcon = 'icon-check';
                        $statusText = 'Traitée';
                    } elseif ($demande->statut_dmtc != 'en attente chef' && $demande->statut_dmtc != 'en attente dsid') {
                        $statusClass = 'badge-rejected';
                        $statusIcon = 'icon-x';
                        $statusText = 'Rejetée';
                    }
                @endphp
                    <span class="badge-custom {{ $statusClass }}">
                        <span class="icon {{ $statusIcon }}"></span>{{ $statusText }}
                    </span>
                </div>
                <div class="list-item">
                    <span class="label">Direction:</span>
                    <span class="value">{{ $demande->user->direction->code_direction }}</span>
                </div>
            </div>
        </div>
        
        <!-- Equipment Details -->
        <div class="info-card">
            <div class="info-header">
                <span class="icon icon-tools"></span>
                <h5>Détails du Matériel</h5>
            </div>
            <div class="info-body">
                <div class="list-item">
                    <span class="label">Equipement:</span>
                    <span class="value">{{ $demande->equipement->categorie }}</span>
                </div>
                <div class="list-item">
                    <span class="label">Marque:</span>
                    <span class="value">{{ $demande->equipement->marque }}</span>
                </div>
                <div class="list-item">
                    <span class="label">Modele:</span>
                    <span class="value">{{ $demande->equipement->modele }}</span>
                </div>
                <div class="list-item">
                    <span class="label">Status:</span>
                    <span class="value">{{ $demande->equipement->statut }}</span>
                </div>
                <div class="list-item">
                    <span class="label">Numéro de série:</span>
                    <span class="value">{{ $demande->equipement->numero_serie }}</span>
                </div>
            </div>
        </div>
        
        <!-- Maintenance Information -->
        <div class="info-card">
            <div class="info-header">
                <span class="icon icon-calendar"></span>
                <h5>Information de la Maintenance</h5>
            </div>
            <div class="info-body">
                <div class="list-item">
                    <span class="label">Direction:</span>
                    <span class="value">{{ $demande->direction->code_direction ?? 'N/A' }}</span>
                </div>
                <div class="list-item">
                    <span class="label">Date de la demande:</span>
                    <span class="value">{{ $demande->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="list-item">
                    <span class="label">Description:</span>
                    <span class="value">{{ $demande->desc_prblem }}</span>
                </div>
            </div>
        </div>
        
        <!-- Actions -->
       <div class="info-card">
    <div class="info-header">
        <span class="icon icon-actions"></span>
        <h5>Actions</h5>
    </div>
    <div class="info-body">

        {{-- ✅ Admin de la direction traitante peut valider/rejeter --}}
        @can('validate', $demande)
            <div class="m-4">
                <form action="{{ route('admin.demande-maintenance.approuver', $demande->id) }}" method="POST" class="d-inline">
                    @csrf @method('PUT')
                    <button type="submit" class="btn bg-success text-white"
                        onclick="return confirm('Valider cette demande ?')">
                        Accepter
                    </button>
                </form>

                <form action="{{ route('admin.demande-maintenance.rejeter', $demande->id) }}" method="POST" class="d-inline">
                    @csrf @method('PUT')
                    <button type="submit" class="btn bg-danger text-white"
                        onclick="return confirm('Rejeter cette demande ?')">
                        Rejeter
                    </button>
                </form>
            </div>
        @endcan


        {{-- ✅ Technicien de la direction d’origine --}}
        @canany(['transfer','changeStatus','close'], $demande)
            <div class="m-4">
                <button type="button" class="btn bg-success text-white mb-2"
                    data-bs-toggle="modal" data-bs-target="#transfertModal">
                    <i class="fas fa-exchange-alt me-2"></i> Transférer la demande
                </button>

                <button type="button" class="btn bg-success text-white"
                    onclick="openMaintenanceModal()">
                    <i class="fas fa-cog me-2"></i> Créer une maintenance
                </button>

                <div class="row mb-3">
                    <div class="col-md-6">
                        @if($demande->equipement->statut == 'en service')
                            <form action="{{ route('update_en_maintenance', $demande->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn border text-success mb-2"
                                    onclick="return confirm('Mettre en maintenance ?')">
                                    <i class="fa-solid fa-screwdriver-wrench"></i> Placer en maintenance
                                </button>
                            </form>
                        @else
                            <form action="{{ route('update_en_service', $demande->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn border text-success mb-2"
                                    onclick="return confirm('Remettre en service ?')">
                                    <i class="fa-solid fa-square-check"></i> Placer en service
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <form action="{{ route('update_traitee', $demande->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn border text-success mb-2"
                                onclick="return confirm('Marquer comme traitée ?')">
                                <i class="fa-solid fa-circle-check"></i> Marquer comme traitée
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endcanany


        {{-- ✅ Technicien de la direction traitante (si validé) --}}
        @can('actionsTraitante', $demande)
            <div class="m-4">
                <button type="button" class="btn bg-success text-white"
                    onclick="openMaintenanceModal()">
                    <i class="fas fa-cog me-2"></i> Créer une maintenance
                </button>
                <div class="row my-3">
                    <div class="col-md-6">
                        @if($demande->equipement->statut == 'en service')
                            <form action="{{ route('update_en_maintenance', $demande->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn border text-success mb-2"
                                    onclick="return confirm('Mettre en maintenance ?')">
                                    <i class="fa-solid fa-screwdriver-wrench"></i> Placer en maintenance
                                </button>
                            </form>
                        @else
                            <form action="{{ route('update_en_service', $demande->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn border text-success mb-2"
                                    onclick="return confirm('Remettre en service ?')">
                                    <i class="fa-solid fa-square-check"></i> Placer en service
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <form action="{{ route('update_traitee', $demande->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn border text-success mb-2"
                                onclick="return confirm('Marquer comme traitée ?')">
                                <i class="fa-solid fa-circle-check"></i> Marquer comme traitée
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endcan
        @php
            $peutAgir = auth()->user()->canany(['validate','transfer','changeStatus','close','actionsTraitante'], $demande);
        @endphp

{{-- Version avec bordure simple --}}
@if(!$peutAgir)
    <div class="border border-gray-200 rounded p-4 m-4 text-center">
        <i class="fas fa-info-circle text-gray-400 mb-2"></i>
        <p class="text-gray-600 mb-0">Aucune action disponible</p>
    </div>
@endif

    </div>
</div>


    </div>
</div>

<!-- Transfer Modal -->
<div class="modal fade" id="transfertModal" tabindex="-1" aria-labelledby="transfertModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transfertModalLabel">Transférer la demande</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form action="{{ route('demandes.transferer', $demande->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="direction_id" class="form-label">Sélectionnez une direction traitante</label>
                        <select class="ui fluid search selection dropdown" id="direction_id" name="direction_id" required>
                            <option value="">Sélectionner une direction</option>
                            @foreach(\App\Models\Direction::where('id', '!=', $demande->direction_id)->get() as $direction)
                                <option value="{{ $direction->id }}">{{ $direction->nom_direction }}</option>
                            @endforeach
                        </select>
                    </div>
                     <button type="submit" class="btn bg-success text-white">Confirmer le transfert</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Maintenance Modal -->
<div id="maintenanceModal" class="modal-custom">
    <div class="modal-dialog">
        <div class="modal-content slide-in">
            <div class="modal-header">
                <h5 class="modal-title">Créer une Maintenance</h5>
                <button type="button" class="modal-close" onclick="closeMaintenanceModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('admin.maintenance.store') }}">
                    @csrf
                    <input type="hidden" name="demande_maintenance_id" value="{{ $demande->id }}">
                    
                    <div class="form-group">
                        <label for="type_maintenance" class="form-label">Type de maintenance exercé</label>
                        <select name="type_maintenance" id="type_maintenance" class="form-select">
                            <option value="preventive">Préventive</option>
                            <option value="palliative">Paliative</option>
                            <option value="corrective">Corrective</option>
                            <option value="curative">Curative</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="statut" class="form-label">Statut de la Maintenance</label>
                        <select name="statut_maintenance" id="statut" class="form-select">
                            <option value="resolue">Maintenance résolue</option>
                            <option value="non resolue">Maintenance non résolue</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="date_realisation" class="form-label">Date de réalisation</label>
                        <input type="date" name="date_realisation" id="date_realisation" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">Description de la maintenance</label>
                        <textarea name="description" id="description" class="form-control" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Créer la maintenance</button>
                </form>
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