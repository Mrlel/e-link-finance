@extends('layouts.main-board')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <ion-icon name="document-text-outline"></ion-icon>
                        Demande d'autorisation pour la demande de matériel #{{ $demande->id }}
                    </h4>
                </div>

                <div class="card-body">
                    <div class="mb-4">
                        <h5>Détails de la demande :</h5>
                        <p><strong>Description :</strong> {{ $demande->des_materiel }}</p>
                        <p><strong>Type de matériel :</strong> {{ $demande->typ_mat }}</p>
                        <p><strong>Priorité :</strong> {{ ucfirst($demande->priorite_dem) }}</p>
                        <p><strong>Description :</strong> {{ $demande->desc_demande }}</p>
                        <p><strong>Demandeur :</strong> {{ $demande->user->nom }}</p>
                    </div>

                    <form action="{{ url('/chef_de_service/demande-materiel/' . $demande->id . '/update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group mb-4">
                            <label for="demande_autorisation" class="form-label">Décision :</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="demande_autorisation" id="autoriser" value="1" required>
                                <label class="form-check-label text-success" for="autoriser">
                                    <ion-icon name="checkmark-circle-outline"></ion-icon> Approuver la demande
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="demande_autorisation" id="refuser" value="0" required>
                                <label class="form-check-label text-danger" for="refuser">
                                    <ion-icon name="close-circle-outline"></ion-icon> Refuser la demande
                                </label>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="commentaire" class="form-label">Commentaire (optionnel) :</label>
                            <textarea name="commentaire" id="commentaire" rows="3" class="form-control" placeholder="Ajoutez un commentaire si nécessaire"></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('chef_de_service.dashboard') }}" class="btn btn-secondary">
                                <ion-icon name="arrow-back-outline"></ion-icon> Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <ion-icon name="save-outline"></ion-icon> Enregistrer la décision
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Script pour gérer l'affichage conditionnel des champs
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const commentaireField = document.getElementById('commentaire');
        
        form.addEventListener('submit', function(e) {
            const radioButtons = document.querySelectorAll('input[name="demande_autorisation"]');
            let isChecked = false;
            
            radioButtons.forEach(radio => {
                if (radio.checked) isChecked = true;
            });
            
            if (!isChecked) {
                e.preventDefault();
                alert('Veuillez sélectionner une option (Approuver ou Refuser)');
            }
        });
    });
</script>
@endpush
@endsection
