@extends('layouts.main-board')

@section('content')
<header class="my-5 d-flex justify-content-between">
<div>
  <h1 class="h3 fw-bold text-dark mb-1 d-flex align-items-center">
    Inventaire des Equipements Informatiques ({{ $equipements->count() }})
  </h1>
  <p class="text-muted mb-0">
    Liste des Equipements • disponibles dans le parc
  </p>
</div>
 <a href="/stock_materiel" class="btn btn-outline-secondary">Retour</a>
</header>
    <!-- Formulaire de filtrage -->
    <div class="card border-light shadow-sm bg-light  mb-4">
        <div class="card-header bg-white">
            <h5>Filtrer les équipements</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('inventaire.index') }}">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="categorie">Catégorie</label>
                            <select name="categorie" id="categorie" class="ui search select dropdown">
                                <option value="">Toutes les catégories</option>
                                @foreach(['Ordinateur portable','Ordinateur All-in-one','unite centrale','outillage technique', 'Imprimante','ecran','clavier','souris', 'Scanner', 'Serveur', 'Routeur', 'Switch', 'Onduleur', 'Projecteur', 'Téléphone IP','pare-feu', 'photocopieuse','stockage','unite centrale','systeme visio conference', 'Accessoire','Autre'] as $categorie)
                                    <option value="{{ $categorie }}" {{ request('categorie') == $categorie ? 'selected' : '' }}>{{ $categorie }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nature">Nature</label>
                            <select name="nature" id="nature" class="form-control">
                                <option value="">Toutes les natures</option>
                                @foreach(['accesoires informatiques', 'reseaux', 'informatiques et bureautiques', 'multimedia', 'telephonie et connectivite', 'autre'] as $nature)
                                    <option value="{{ $nature }}" {{ request('nature') == $nature ? 'selected' : '' }}>{{ $nature }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="etat">État</label>
                            <select name="etat" id="etat" class="form-control">
                                <option value="">Tous les états</option>
                                @foreach(['bon', 'moyen', 'hors service'] as $etat)
                                    <option value="{{ $etat }}" {{ request('etat') == $etat ? 'selected' : '' }}>{{ $etat }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="statut">Statut</label>
                            <select name="statut" id="statut" class="form-control">
                                <option value="">Tous les statuts</option>
                                @foreach(['en stock', 'en service', 'en maintenance', 'enlèvement'] as $statut)
                                    <option value="{{ $statut }}" {{ request('statut') == $statut ? 'selected' : '' }}>{{ ucfirst($statut) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                                        
                    <!--<div class="col-md-3">
                        <div class="form-group">
                            <label for="poste_id">Poste</label>
                            <select name="poste_id" id="poste_id" class="form-control">
                                <option value="">Tous les postes</option>
                                @foreach(\App\Models\Poste::where('direction_id', auth()->user()->direction_id)->get() as $poste)
                                    <option value="{{ $poste->id }}" {{ request('poste_id') == $poste->id ? 'selected' : '' }}>
                                        {{ $poste->code_poste }} {{ $poste->emplacement ? '('.$poste->emplacement.')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>-->
                    
                    <div class="col-md-2 mt-4">
                
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filtrer
                        </button>
                        <a href="{{ route('inventaire.index') }}" class="btn" style="background-color: #2C3E50; color: white;">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Formulaire d'export -->
    <div class="mb-4 bg-light rounded p-4">
        <div class="card-header p-1">
            <h3 class="mb-3">Veuillez définir les champs à exporter</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('inventaire.export') }}">
                @csrf
                <input type="hidden" name="categorie" value="{{ request('categorie') }}">
                <input type="hidden" name="nature" value="{{ request('nature') }}">
                <input type="hidden" name="etat" value="{{ request('etat') }}">
                <input type="hidden" name="statut" value="{{ request('statut') }}">
                <input type="hidden" name="direction_id" value="{{ request('direction_id') }}">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class=" mb-3">Choisissez le format d'export</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="format" id="formatExcel" value="xlsx" checked>
                                <label class="form-check-label" for="formatExcel">
                                    Excel (.xlsx)
                                </label>
                            </div>
                            <!-- <div class="form-check">
                                <input class="form-check-input" type="radio" name="format" id="formatCsv" value="csv">
                                <label class="form-check-label" for="formatCsv">
                                    CSV (.csv)
                                </label>
                            </div>-->
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="format" id="formatCsv" value="pdf">
                                <label class="form-check-label" for="formatPdf">
                                    PDF (.pdf)
                                </label>
                            </div>
                            <!-- <div class="form-check">
                                <input class="form-check-input" type="radio" name="format" id="formatWord" value="docx">
                                <label class="form-check-label" for="formatWord">
                                    Word (.docx)
                                </label>
                            </div>-->
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="mb-3">Champs à exporter (les champs non coché ne seront pas exportés)</label>
                            <div class="row">
                                @foreach([
                                    'des_equipement' => 'Désignation',
                                    'marque' => 'Marque',
                                    'modele' => 'Modèle',
                                    'categorie' => 'Catégorie',
                                    'nature' => 'Nature',
                                    'adresse_mac' => 'Adresse MAC',
                                    'numero_serie' => 'N° Série',
                                    'date_acquis' => 'Date acquisition',
                                    'source_acquisition' => 'source_acquisition',
                                    'etat' => 'État',
                                    'capacite' => 'capacite',
                                    'systeme' => 'Système',
                                    'statut' => 'Statut',
                                ] as $field => $label)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="fields[]" id="field{{ $field }}" value="{{ $field }}" checked>
                                            <label class="form-check-label" for="field{{ $field }}">
                                                {{ $label }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-2">
                    <div class="col-md-12">
                        <button type="submit" class="btn text-white bg-success">
                            <i class="fas fa-file-export"></i> Exporter la liste
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Résultats -->
    <div class="resultat">

        @if($equipements->count() > 0)
         <div class="card-header bg-white mb-3 d-flex align-item-center justify-content-between gap-4">
            <h3>Resultats de recherche : {{ $equipements->count() }}</h3>

            <div class="search d-flex justify-content-between gap-3">
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>N°</th>
                            <th>Catégorie</th>
                            <th>Nature</th>
                            <th>Marque/Modèle</th>
                            <th>N° Série</th>
                            <th>État</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($equipements as $equipement)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $equipement->categorie }}</td>
                                <td>{{ $equipement->nature }}</td>
                                <td>{{ $equipement->marque }} / {{ $equipement->modele }}</td>
                                <td>{{ $equipement->numero_serie }}</td>
                                <td>
                                    <span>
                                        {{ $equipement->etat }}
                                    </span>
                                </td>
                                <td>
                                   <span>{{ $equipement->statut }}</span>
                                </td>
                                <td>
                                    <a href="/equipement_details/{{$equipement->id}}" class="btn btn-sm border-success text-success">
                                        voir les détails
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="text-center py-3 mt-4">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <p class="text-muted">Aucun equipement trouvé</p>
        </div>
        @endif
    </div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
@endsection