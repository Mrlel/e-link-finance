@extends('layouts.main-board')

@section('content')
<style>
    /* --------------------------------- */
    /* COULEURS IVOIRIENNES */
    /* --------------------------------- */
    :root {
        --ci-orange: #FF8200; /* Orange (Terre de la Savane / Optimisme) */
        --ci-green: #009E60; /* Vert (Forêt / Espoir) */
        --ci-white: #FFFFFF; /* Blanc (Paix) */
        --ci-orange-light: #fff3e0; /* Orange très clair pour fond */
        --ci-green-light: #e0fff3; /* Vert très clair pour fond */
    }

    .ci-text-green { color: var(--ci-green) !important; }
    .ci-text-orange { color: var(--ci-orange) !important; }
    .ci-bg-green { background-color: var(--ci-green) !important; color: white; }
    .ci-bg-orange { background-color: var(--ci-orange) !important; color: white; }
    .ci-border-orange { border-left: 5px solid var(--ci-orange); }
    .ci-border-green { border-left: 5px solid var(--ci-green); }

    /* Styles spécifiques aux notifications */
    .notification-card {
        border-radius: 4px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    /* Style pour les notifications non lues */
    .notification-card.unread {
        background-color: var(--ci-orange-light); /* Fond léger pour non lu */
        border-left: 5px solid var(--ci-orange); /* Bande de couleur Orange (Haute priorité visuelle) */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .notification-card:hover {
        transform: translateY(-2px);
    }

    /* Bouton 'Marquer tout comme lu' */
    .btn-ci-outline {
        color: var(--ci-orange);
        border-color: var(--ci-orange);
        transition: all 0.2s;
    }

    .btn-ci-outline:hover {
        background-color: var(--ci-orange);
        color: var(--ci-white);
        border-color: var(--ci-orange);
    }

</style>

<div class="d-flex justify-content-between align-items-center mt-4 pb-3">
    <h1 class="h3 fw-bold ci-text-green">
        <i class="fas fa-bullhorn me-2"></i> Centre de Notifications ({{ $notifications->count() }})
    </h1>

    @if($notifications->count() > 0)
    <form action="{{ url('/notifications/mark-all-as-read') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-sm btn-ci-outline">
            <i class="fas fa-check-double me-1"></i> Marquer tout comme lu
        </button>
    </form>
    @endif
</div>

<div class="tab-content" id="notificationTabsContent">
    <div class="tab-pane fade show active" id="materiel" role="tabpanel">
        
        @foreach($notifications as $notification)
        
        {{-- Détermine si la notification est lue (read_at est NULL si non lue) --}}
        @php
            $isUnread = is_null($notification->read_at);
            $cardClass = $isUnread ? 'unread shadow-md' : 'shadow-sm';
            $iconColor = $isUnread ? 'ci-text-orange' : 'text-muted';

            $priorite = $notification->data['priorite'] ?? 'Basse';
            $badgeColor = match($priorite) {
                'Haute' => 'ci-bg-orange',     // Orange pour Haute Priorité
                'Moyenne' => 'ci-bg-green',   // Vert pour Moyenne Priorité
                default => 'bg-light text-muted', // Subtil pour Basse Priorité
            };
            $prioriteIcon = match($priorite) {
                'Haute' => 'fas fa-exclamation-triangle',
                'Moyenne' => 'fas fa-exclamation-circle',
                default => 'fas fa-info-circle',
            };
        @endphp

        <a href="{{ $notification->data['link'] ?? '#' }}" 
           class="card mb-3 notification-card {{ $cardClass }} text-decoration-none text-dark 
                  {{ $isUnread ? 'ci-border-orange' : 'ci-border-green' }} 
                  @if($isUnread) fw-bold @endif">

            <div class="card-body d-flex justify-content-between align-items-center p-2">
                
                {{-- Contenu de la notification --}}
                <div class="flex-grow-1">
                    <h6 class="card-title mb-1 d-flex align-items-center">
                        <i class="fas fa-bell me-2 {{ $iconColor }}"></i>
                        <span class="{{ $isUnread ? 'ci-text-orange' : '' }}">
                           {{ $notification->data['message'] ?? 'Nouvelle notification' }}
                        </span>
                    </h6>
                    
                    <div class="d-flex align-items-center mt-2">
                        <span class="badge {{ $badgeColor }} me-3">
                            <i class="{{ $prioriteIcon }}"></i> {{ $notification->data['type_materiel'] ?? 'Type inconnu' }} 
                        </span>
                        
                        <small class="text-muted">
                            <i class="far fa-clock"></i> Reçu {{ $notification->created_at->diffForHumans() }}
                        </small>
                    </div>
                </div>

                {{-- Indicateur et lien --}}
                <div class="ms-3 text-end">
                    <i class="fas fa-chevron-right text-muted"></i>
                </div>
            </div>
        </a> {{-- Fin du lien/carte --}}
        @endforeach
    </div>
</div>

@if($notifications->isEmpty())
<div class="text-center text-muted mt-5 py-5 border rounded-lg bg-light">
    <i class="fas fa-bell-slash fa-3x mb-3 ci-text-green"></i>
    <h5 class="fw-bold">Rien à Signaler</h5>
    <p>Vous n'avez aucune notification en attente pour le moment. Félicitations !</p>
</div>
@endif
@endsection