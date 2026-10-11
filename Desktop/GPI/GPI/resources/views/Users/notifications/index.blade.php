@extends('layouts.user-section')

@section('content')
<div class="container py-4">

    {{-- Header notifications --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-bell-fill text-warning me-2"></i> Notifications
        </h2>

            @if($notificationsMaterielAssignation->count() > 0)
    <form action="{{ url('/notifications/mark-all-as-read') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-sm btn-ci-outline">
            <i class="fas fa-check-double me-1"></i> Marquer tout comme lu
        </button>
    </form>
    @endif
    </div>

    {{-- Onglets --}}
    <ul class="nav nav-tabs mb-4" id="notifTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active d-flex align-items-center" id="assign-tab"
                data-bs-toggle="tab" data-bs-target="#assign" type="button" role="tab">
                <i class="bi bi-laptop me-2"></i>
                Matériel Assigné
                <span class="badge bg-primary ms-2">{{ $notificationsMaterielAssignation->count() }}</span>
            </button>
        </li>

        <li class="nav-item" role="presentation">
            <button class="nav-link d-flex align-items-center" id="retour-tab"
                data-bs-toggle="tab" data-bs-target="#retour" type="button" role="tab">
                <i class="bi bi-arrow-counterclockwise me-2"></i>
                Matériel Retourné
                <span class="badge bg-warning ms-2">{{ $notificationsMaterielRetour->count() }}</span>
            </button>
        </li>
    </ul>

    {{-- Contenu des tabs --}}
    <div class="tab-content" id="notifTabsContent">

        {{-- MATÉRIEL ASSIGNÉ --}}
        <div class="tab-pane fade show active" id="assign" role="tabpanel">
            @forelse($notificationsMaterielAssignation as $notification)
                <div class="card shadow-sm mb-3 border-0 notif-card">
                    <div class="card-body">

                        <h5 class="fw-bold text-primary mb-2">
                            <i class="bi bi-bell-fill me-2"></i> {{ $notification->data['message'] }}
                        </h5>

                        <p class="mb-1"><strong>Assigné par :</strong> {{ $notification->data['assigned_by'] }}</p>
                        <p class="mb-1"><strong>Équipement :</strong> {{ $notification->data['designation'] }}</p>
                        <p class="mb-1"><strong>Marque :</strong> {{ $notification->data['marque'] }}</p>
                        <p class="mb-1"><strong>Modèle :</strong> {{ $notification->data['modele'] }}</p>
                        <p class="mb-1"><strong>N° Série :</strong> {{ $notification->data['numero_serie'] }}</p>

                        <span class="text-muted small">
                            <i class="bi bi-clock-history me-1"></i>
                            {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                        </span>

                    </div>
                </div>
            @empty
                <div class="alert alert-secondary text-center">Aucune notification d’assignation</div>
            @endforelse
        </div>

        {{-- MATÉRIEL RETOURNÉ --}}
        <div class="tab-pane fade" id="retour" role="tabpanel">
            @forelse($notificationsMaterielRetour as $notification)
                <div class="card shadow-sm mb-3 border-0 notif-card">
                    <div class="card-body">

                        <h5 class="fw-bold text-warning mb-2">
                            <i class="bi bi-arrow-repeat me-2"></i> {{ $notification->data['message'] }}
                        </h5>

                        <p class="mb-1"><strong>Équipement :</strong> {{ $notification->data['equipement_nom'] }}</p>
                        <p class="mb-1"><strong>Retourné par :</strong> {{ $notification->data['returned_by'] }}</p>
                        <p class="mb-1"><strong>Date de retour :</strong> {{ $notification->data['date_retour'] }}</p>
                        <p class="mb-1"><strong>État :</strong> {{ $notification->data['etat_retour'] }}</p>
                        <p class="mb-2"><strong>Commentaire :</strong> {{ $notification->data['commentaire'] }}</p>

                        <span class="text-muted small">
                            <i class="bi bi-clock-history me-1"></i>
                            {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                        </span>

                    </div>
                </div>
            @empty
                <div class="alert alert-secondary text-center">Aucun retour enregistré</div>
            @endforelse
        </div>

    </div>

    {{-- Empty state --}}
    @if($notificationsMaterielAssignation->isEmpty() && $notificationsMaterielRetour->isEmpty())
        <div class="text-center mt-5">
            <i class="bi bi-bell-slash text-secondary fs-1"></i>
            <h4 class="mt-3">Aucune notification</h4>
            <p class="text-muted">Vous n'avez aucune activité récente.</p>
        </div>
    @endif

</div>

{{-- Script pour marquer tout lu --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('mark-all-read').addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelectorAll('.notif-card').forEach(card => {
            card.style.opacity = "0.45";
        });
    });
});
</script>
@endsection
