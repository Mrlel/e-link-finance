@extends('layouts.main-board')

@section('title', 'Historique des équipements assignés')

@section('content')

    <h2 class="fw-bold my-4">Historique des équipements assignés à <span style="color: #f3902e; text-transform: uppercase; ">{{ $user->nom }} {{ $user->prenom }}</span></h2>
    <a href="{{ url()->previous() }}" class="btn btn-success mb-3"><i class="fas fa-arrow-left me-2"></i> Retour</a>
    @if($assignments->count() > 0)
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Équipement</th>
                <th>Marque</th>
                <th>N° série</th>
                <th>Catégorie</th>
                <th>Statut</th>
                <th>Date d'attribution</th>
                <th>Date de retour</th>
                <th>État au retour</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assignments as $assignment)
                <tr>
                    <td>{{ $assignment->equipement->des_equipement ?? '-' }}</td>
                    <td>{{ $assignment->equipement->marque ?? '-' }}</td>
                    <td>{{ $assignment->equipement->numero_serie ?? '-' }}</td>
                    <td>{{ $assignment->equipement->categorie ?? '-' }}</td>
                    <td>{{ $assignment->equipement->statut ?? '-' }}</td>
                    <td>
                        @if($assignment->assigned_at)
                            {{ ($assignment->assigned_at instanceof \Illuminate\Support\Carbon || $assignment->assigned_at instanceof \Carbon\Carbon) ? $assignment->assigned_at->format('d/m/Y H:i') : \Carbon\Carbon::parse($assignment->assigned_at)->format('d/m/Y H:i') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($assignment->returned_at)
                            {{ ($assignment->returned_at instanceof \Illuminate\Support\Carbon || $assignment->returned_at instanceof \Carbon\Carbon) ? $assignment->returned_at->format('d/m/Y H:i') : \Carbon\Carbon::parse($assignment->returned_at)->format('d/m/Y H:i') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $assignment->etat_retour ?? '-' }}</td>
                    <td>
                        @if(!$assignment->returned_at)
                                <button type="button" 
                                        class="btn btn-warning btn-sm d-flex align-items-center gap-2" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#retraitModal{{ $assignment->id }}">
                                        <ion-icon name="arrow-undo-outline"></ion-icon> Retirer
                                </button>
                            @else
                             <button type="button" class="btn border-success text-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <p>Cause du retrait</p>
                            </button>
                        @endif
                    </td>
                </tr>



                
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Cause de retrait de l'équipement : {{ $assignment->equipement->des_equipement }} {{ $assignment->equipement->modele }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            {{ $assignment->commentaire_retour ?? 'Aucun commentaire' }}
                                        </div>
                                    
                                    </div>
                                </div>
                            </div>

<!-- Modal de retrait pour chaque assignment -->
                    <div class="modal fade" id="retraitModal{{ $assignment->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Retrait d'équipement</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Confirmez-vous le retrait de l'équipement suivant :</p>
                                    <ul>
                                        <li><strong>Équipement :</strong> {{ $assignment->equipement->des_equipement }}</li>
                                        <li><strong>Utilisateur :</strong> {{ optional($assignment->user)->nom }}</li>
                                        <li><strong>N° Série :</strong> {{ $assignment->equipement->numero_serie }}</li>
                                    </ul>
                                    <form action="{{ route('retourner.equipement', ['assignmentId' => $assignment->id]) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label for="commentaire" class="form-label">Commentaire de retrait</label>
                                            <textarea class="form-control" name="commentaire_retour" rows="3"></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="etat_retour" class="form-label">État au retour</label>
                                            <select class="form-control" name="etat_retour" required>
                                                <option value="bon">Bon état</option>
                                                <option value="moyen">État moyen</option>
                                                <option value="mauvais">Mauvais état</option>
                                            </select>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-warning">Confirmer le retrait</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
            @endforeach
        </tbody>
    </table>
    @else
        <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px;">Aucun équipement n'a été assigné à {{ $user->nom }} {{ $user->prenom }} <i class="fas fa-exclamation-triangle"></i></i></div>
    @endif
</div>
@endsection
