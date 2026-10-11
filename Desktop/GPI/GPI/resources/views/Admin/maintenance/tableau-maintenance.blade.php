@extends('layouts.main-board')

@section('content')

    <h1 class="mb-4">Tableau de Maintenance</h1>
    <a href="{{ url()->previous() }}" class="btn btn-success mb-3">
        <i class="fas fa-arrow-left me-2"></i> Retour
    </a>

    @if($maintenances->isNotEmpty())
        <table class="table table-striped table-hover align-middle">
            <thead>
                <tr>
                    <th>Provenance</th>
                    <th>Utilisateur</th>
                    <th>Équipement</th>
                    <th>Date de la demande</th>
                    <th>Date de réalisation</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($maintenances as $maintenance)
                    <tr>
                        <td>{{ $maintenance->direction->code_direction ?? 'N/A' }}</td>
                        <!-- 
                        <td>
                            @if($maintenance->directionTraitante)
                                {{ $maintenance->directionTraitante->code_direction }}
                            @elseif($maintenance->demandeMaintenance && $maintenance->demandeMaintenance->user && $maintenance->demandeMaintenance->user->direction)
                                {{ $maintenance->demandeMaintenance->user->direction->code_direction }}
                            @else
                                N/A
                            @endif
                        </td> -->

                        <td>
                            @if($maintenance->demandeMaintenance && $maintenance->demandeMaintenance->user)
                                {{ $maintenance->demandeMaintenance->user->nom . ' ' . $maintenance->demandeMaintenance->user->prenom }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            {{ $maintenance->demandeMaintenance && $maintenance->demandeMaintenance->equipement 
                                ? $maintenance->demandeMaintenance->equipement->des_equipement 
                                : 'N/A' }}
                        </td>
                        <td>{{ $maintenance->date_demande?->format('d/m/Y') ?? 'N/A' }}</td>
                        <td>{{ $maintenance->date_realisation?->format('d/m/Y') ?? 'Non réalisée' }}</td>
                        <td>
                            <span class="badge 
                                @if($maintenance->statut_maintenance === 'resolue') bg-success 
                                @elseif($maintenance->statut_maintenance === 'non resolue') bg-danger
                                @else bg-secondary @endif">
                                {{ ucfirst($maintenance->statut_maintenance ?? 'N/A') }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="text-center py-5">
            <img src="{{ asset('/box.png') }}" alt="Aucun enregistrement" height="130" width="130" class="mb-3">
            <p class="text-muted">Aucune maintenance enregistrée.</p>
        </div>
    @endif
@endsection
