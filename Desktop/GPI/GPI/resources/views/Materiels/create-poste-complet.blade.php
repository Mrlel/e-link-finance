@extends('layouts.main-board')
@section('title', 'Création poste complet')

@section('content')
<div class="container-fluid">
    
    <div class="row justify-content-center">
        <div class="col-md-12">

            <form method="POST" action="{{ route('poste-complet.store') }}" class="needs-validation" novalidate>
                @csrf

                <!--
                <div class="mb-3">
                    <label for="nom_poste" class="form-label">
                        <i class="fas fa-computer me-2 text-success"></i>
                        Nom du poste/emplacement
                    </label>
                    <input type="text" class="form-control" id="nom_poste" name="nom_poste" 
                           placeholder="Saisissez le nom du poste..." required
                           value="{{ old('nom_poste') }}">
                    <div class="invalid-feedback">
                        Veuillez saisir le nom du poste.
                    </div>
                </div> -->

                <!-- Unité centrale -->
                <div class="card mb-4 border-0">
                    <div class="card-header py-3 bg-white">
                        <h3 class="h5 mb-0">
                            <i class="fas fa-microchip text-green me-2"></i>
                            Unité centrale
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="unite_centrale_id" class="form-label fw-bold">
                                
                                Sélectionner une unité centrale existante
                            </label>
                            <select class="fluid ui search dropdown" id="unite_centrale_id" name="unite_centrale_id" required>
                                <option value="">-- Sélectionnez une unité centrale existante --</option>
                                @foreach($equipementsStock->where('categorie', 'unite centrale')->sortByDesc('created_at') as $equipement)
                                    <option value="{{ $equipement->id }}" 
                                            {{ old('unite_centrale_id', $equipementsStock->where('categorie', 'unite centrale')->sortByDesc('created_at')->first()->id ?? '') == $equipement->id ? 'selected' : '' }}>
                                        {{ $equipement->numero_serie }} - {{ $equipement->marque }} {{ $equipement->modele }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Composants existants -->
                <div class="card mb-4 border-0">
                    <div class="card-header py-3 bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="h5 mb-0">
                                <i class="fas fa-box-open text-success me-2"></i>
                                Composants existants
                            </h3>
                            <button type="button" class="btn btn-sm btn-outline-orange rounded-circle" id="refreshStock">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                               
                                Sélectionner les composants du stock
                            </label>
                            
                            <!-- Filtre -->
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" id="filtreComposants" class="form-control" 
                                           placeholder="Filtrer par catégorie, marque ou n° série...">
                                </div>
                            </div>
                            
                            <div class="composants-list-container">
                                <div class="row g-3">
                                    @foreach($equipementsStock->whereIn('categorie', ['souris', 'clavier', 'ecran']) as $equipement)
                                    <div class="col-md-6 composant-item" 
                                         data-categorie="{{ strtolower($equipement->categorie) }}" 
                                         data-marque="{{ strtolower($equipement->marque) }}" 
                                         data-serie="{{ strtolower($equipement->numero_serie) }}">
                                        <div class="card h-100 border-0 shadow-sm composant-card">
                                            <div class="card-body p-3">
                                                <div class="form-check h-100">
                                                    <input class="form-check-input" type="checkbox" 
                                                           name="composants_existants[]" 
                                                           id="equipement_{{ $equipement->id }}" 
                                                           value="{{ $equipement->id }}"
                                                           @if(in_array($equipement->id, old('composants_existants', []))) checked @endif>
                                                    <label class="form-check-label w-100" for="equipement_{{ $equipement->id }}">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <span class="fw-bold text-orange">{{ ucfirst($equipement->categorie) }}</span>
                                                            <span class="badge bg-{{ $equipement->etat == 'bon' ? 'success' : ($equipement->etat == 'moyen' ? 'warning' : 'danger') }}">
                                                                {{ $equipement->etat }}
                                                            </span>
                                                        </div>
                                                        <div class="mt-2">
                                                            <div class="d-flex align-items-center mb-1">
                                                                <i class="fas fa-tag me-2 text-muted"></i>
                                                                <span>{{ $equipement->marque }} {{ $equipement->modele }}</span>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-barcode me-2 text-muted"></i>
                                                                <span>{{ $equipement->numero_serie }}</span>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <div class="mt-3 text-end">
                                <small id="compteur-selection" class="text-muted fw-bold">
                                    {{ count(old('composants_existants', [])) }} sélectionné(s)
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nouveaux composants -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header py-3 bg-white">
                        <h3 class="h5 mb-0">
                            <i class="fas fa-plus-circle text-green me-2"></i>
                            Nouveaux composants
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="nouveaux-composants-container">
                            @if(old('nouveaux_composants'))
                                @foreach(old('nouveaux_composants') as $index => $composant)
                                <div class="nouveau-composant mb-4">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Nature du composant</label>
                                            <select name="nouveaux_composants[{{ $index }}][nature]" class="form-control" required>
                                                <option value="">-- Sélectionnez --</option>
                                                <option value="accesoires informatiques" {{ $composant['nature'] == 'accesoires informatiques' ? 'selected' : '' }}>Accessoires informatiques</option>
                                                <option value="informatiques et bureautiques" {{ $composant['nature'] == 'informatiques et bureautiques' ? 'selected' : '' }}>Informatiques et bureautiques</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Type de composant</label>
                                            <select name="nouveaux_composants[{{ $index }}][categorie]" class="form-control" required>
                                                <option value="">-- Sélectionnez --</option>
                                                <option value="ecran" {{ $composant['categorie'] == 'ecran' ? 'selected' : '' }}>Écran</option>
                                                <option value="clavier" {{ $composant['categorie'] == 'clavier' ? 'selected' : '' }}>Clavier</option>
                                                <option value="souris" {{ $composant['categorie'] == 'souris' ? 'selected' : '' }}>Souris</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Marque</label>
                                            <input type="text" name="nouveaux_composants[{{ $index }}][marque]" 
                                                   class="form-control" required
                                                   value="{{ $composant['marque'] }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Modèle</label>
                                            <input type="text" name="nouveaux_composants[{{ $index }}][modele]" 
                                                   class="form-control" required
                                                   value="{{ $composant['modele'] }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">N° Série</label>
                                            <input type="text" name="nouveaux_composants[{{ $index }}][numero_serie]" 
                                                   class="form-control" required
                                                   value="{{ $composant['numero_serie'] }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">État</label>
                                            <select name="nouveaux_composants[{{ $index }}][etat]" class="form-control">
                                                <option value="bon" {{ $composant['etat'] == 'bon' ? 'selected' : '' }}>Bon</option>
                                                <option value="moyen" {{ $composant['etat'] == 'moyen' ? 'selected' : '' }}>Moyen</option>
                                                <option value="hors service" {{ $composant['etat'] == 'hors service' ? 'selected' : '' }}>Hors service</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Date d'acquisition</label>
                                            <input type="date" name="nouveaux_composants[{{ $index }}][date_acquis]" 
                                                   class="form-control"
                                                   value="{{ $composant['date_acquis'] }}">
                                        </div>
                                        <div class="col-md-12 text-end">
                                            <button type="button" class="btn btn-danger btn-sm supprimer-composant">
                                                <i class="fas fa-trash me-1"></i> Supprimer
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" class="btn btn-success mt-3" id="ajouter-composant">
                            <i class="fas fa-plus me-2"></i>
                            Ajouter un composant
                        </button>
                    </div>
                </div>

                <!-- Bouton de soumission -->
                <div class="text-center my-5">
                    <button type="submit" class="btn btn-orange-ivoire px-5 py-3">
                        <i class="fas fa-save me-2"></i>
                        Enregistrer le poste complet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Styles CSS personnalisés -->
<style>
    :root {
        --orange-ivoirien: #ff8c00;
        --vert-ivoirien: #228b22;
        --bleu-ivoirien: #0056b3;
    }
    
    .text-orange { color: var(--orange-ivoirien); }
    .text-green { color: var(--vert-ivoirien); }
    .text-blue { color: var(--bleu-ivoirien); }
    
    .bg-gradient-ivoire {
        background: #2C3E50;
    }
        
    .form-title {
        text-align: center;
        margin-bottom: 2.5rem;
        font-weight: 300;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        border:1px solid rgba(132, 246, 176, 0.48);
        padding: 1rem 0;
        border-radius: 5px;
    }
    
    .form-title img {
        font-size: 2rem;

        background-color: rgba(66, 255, 151, 0.1);
        padding: 2.5rem;
        border-radius: 50%;
    }
    
    .btn-orange-ivoire {
        background-color: var(--orange-ivoirien);
        color: white;
        border: none;
        transition: all 0.3s;
    }
    
    .btn-orange-ivoire:hover {
        background-color: #e67e00;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .btn-outline-orange {
        color: var(--orange-ivoirien);
        border-color: var(--orange-ivoirien);
    }
    
    .btn-outline-orange:hover {
        background-color: var(--orange-ivoirien);
        color: white;
    }
    
    .composants-list-container {
        max-height: 500px;
        overflow-y: auto;
        padding-right: 10px;
    }
    
    .composants-list-container::-webkit-scrollbar {
        width: 8px;
    }
    
    .composants-list-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .composants-list-container::-webkit-scrollbar-thumb {
        background: var(--orange-ivoirien);
        border-radius: 10px;
    }
    
    .composant-card {
        transition: all 0.3s ease;
    }
    
    .composant-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .nouveau-composant {
        background: rgba(255, 140, 0, 0.05);
        border: 1px dashed var(--orange-ivoirien);
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }
    
    .nouveau-composant:hover {
        background: rgba(255, 140, 0, 0.08);
        border-style: solid;
    }
    
    .form-check-input {
        transform: scale(1.1);
        margin-top: 0.3rem;
        margin-right: 0.5rem;
    }
    
    .form-check-label {
        cursor: pointer;
        user-select: none;
    }
    
    @media (max-width: 768px) {
        .container-fluid {
            padding: 15px !important;
        }
        
        .composant-item {
            margin-bottom: 15px;
        }
    }
</style>

<!-- Scripts JavaScript -->
<script>
$(document).ready(function() {
    // Compteur pour les nouveaux composants
    let compteurComposants = {{ count(old('nouveaux_composants', [])) }};
    
    // Ajout dynamique de composants
    $('#ajouter-composant').click(function() {
        const html = `
        <div class="nouveau-composant mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Nature du composant</label>
                    <select name="nouveaux_composants[${compteurComposants}][nature]" class="form-control" required>
                        <option value="">-- Sélectionnez --</option>
                        <option value="accesoires informatiques">Accessoires informatiques</option>
                        <option value="informatiques et bureautiques">Informatiques et bureautiques</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Type de composant</label>
                    <select name="nouveaux_composants[${compteurComposants}][categorie]" class="form-control" required>
                        <option value="">-- Sélectionnez --</option>
                        <option value="ecran">Écran</option>
                        <option value="clavier">Clavier</option>
                        <option value="souris">Souris</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Marque</label>
                    <input type="text" name="nouveaux_composants[${compteurComposants}][marque]" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Modèle</label>
                    <input type="text" name="nouveaux_composants[${compteurComposants}][modele]" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">N° Série</label>
                    <input type="text" name="nouveaux_composants[${compteurComposants}][numero_serie]" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">État</label>
                    <select name="nouveaux_composants[${compteurComposants}][etat]" class="form-control">
                        <option value="bon">Bon</option>
                        <option value="moyen">Moyen</option>
                        <option value="hors service">Hors service</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date d'acquisition</label>
                    <input type="date" name="nouveaux_composants[${compteurComposants}][date_acquis]" class="form-control">
                </div>
                <div class="col-md-12 text-end">
                    <button type="button" class="btn btn-danger btn-sm supprimer-composant">
                        <i class="fas fa-trash me-1"></i> Supprimer
                    </button>
                </div>
            </div>
        </div>`;
        
        $('#nouveaux-composants-container').append(html);
        compteurComposants++;
    });

    // Suppression de composants
    $(document).on('click', '.supprimer-composant', function() {
        $(this).closest('.nouveau-composant').fadeOut(300, function() {
            $(this).remove();
        });
    });

    // Filtrage des composants existants
    $('#filtreComposants').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        
        $('.composant-item').each(function() {
            const categorie = $(this).data('categorie');
            const marque = $(this).data('marque');
            const serie = $(this).data('serie');
            
            if (categorie.includes(searchTerm) || marque.includes(searchTerm) || serie.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Compteur de sélection
    $(document).on('change', 'input[name="composants_existants[]"]', function() {
        const count = $('input[name="composants_existants[]"]:checked').length;
        $('#compteur-selection').text(count + ' sélectionné(s)');
    });

    // Actualisation de la liste
    $('#refreshStock').click(function() {
        $(this).addClass('fa-spin');
        setTimeout(() => {
            location.reload();
        }, 500);
    });

    // Validation Bootstrap
    (function() {
        'use strict';
        
        const forms = document.querySelectorAll('.needs-validation');
        
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                
                form.classList.add('was-validated');
            }, false);
        });
    })();
});
</script>
@endsection