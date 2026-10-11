@extends('layouts.main-board')

@section('content')
@include('layouts.message')
@include('layouts.demande-user-stat-card')

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Liste des demandes de maintenance</h5>
    </div>

    <div class="card-body p-0">
        <table class="table table-hover align-middle">
            <thead class="bg-light">
                <tr>
                    <th>Direction</th>
                    <th>Utilisateur</th>
                    <th>Date / Heure</th>
                    <th>Type de Matériel</th>
                    <th>Marque</th>
                    <th>N° de Séries</th>
                    <th>Statut</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($demandes as $demande)
                <tr>
                    <td>{{ $demande->direction->code_direction }}</td>

                    <td class="fw-semibold">
                        {{ $demande->user->nom }} {{ $demande->user->prenom }}
                    </td>

                    <td>
                        <span class="text-muted">
                            {{ $demande->created_at->format('d/m/Y') }}
                            <br>
                            <small class="text-primary">{{ $demande->created_at->format('H:i') }}</small>
                        </span>
                    </td>

                    <td>{{ $demande->equipement->categorie }}</td>
                    <td>{{ $demande->equipement->marque }}</td>
                    <td>{{ $demande->equipement->numero_serie }}</td>

                    {{-- Badge de statut --}}
                    <td>
                        @if(in_array($demande->statut_dmtc, ['approuvee','approuvée']))
                            <span class="badge bg-success-subtle text-success d-flex align-items-center gap-1">
                                <ion-icon name="checkmark-circle-outline"></ion-icon> Approuvée
                            </span>

                        @elseif($demande->statut_dmtc == 'rejetée')
                            <span class="badge bg-danger-subtle text-danger d-flex align-items-center gap-1">
                                <ion-icon name="close-circle-outline"></ion-icon> Rejetée
                            </span>

                        @elseif($demande->statut_dmtc == 'traitée')
                            <span class="badge bg-success-subtle text-success d-flex align-items-center gap-1">
                                <ion-icon name="checkmark-circle-outline"></ion-icon> Traitée
                            </span>

                        @else
                            <span class="badge bg-primary-subtle text-primary d-flex align-items-center gap-1">
                                <ion-icon name="hourglass-outline"></ion-icon> En attente
                            </span>
                        @endif
                    </td>

                    {{-- Actions --}}
                    <td class="text-center">
                        @if (auth()->user()->role == 'technicien' || auth()->user()->role == 'admin')
                            <a href="{{ route('demande_maintenances.show', $demande->id) }}"
                                class="btn btn-sm bg-primary-subtle px-3 d-inline-flex align-items-center gap-1">
                                <ion-icon name="create-outline"></ion-icon> Traiter
                            </a>
                        @endif
                    </td>

                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>

@endsection
