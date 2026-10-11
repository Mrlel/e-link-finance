@extends('layouts.main-board')

@section('content')
    <style>
        :root {
            --Orange: #FF9900; /* Orange */
            --Vert: #4CAF50; /* Vert */
            --light-color: #FFFFFF; /* Blanc */
            --bg-color: #f9f9f9;
            --text-color: #333333;
            --accent-light: #FFE0B2; /* Orange clair */
            --accent-dark: #E65100; /* Orange foncé */
            --accent-green-light: #C8E6C9; /* Vert clair */
            --accent-green-dark:rgb(72, 161, 125); /* Vert foncé */
        }
      
        
        .main-content {
            background-color: var(--light-color);
            border-radius: 0 0 10px 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .software-header {
            display: flex;
            align-items: flex-start;
            margin-bottom: 30px;
            gap: 30px;
        }
        
        .software-image {
            width: 130px;
            height: 130px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .software-image img {
            max-width: 100%;
            max-height: 100%;
        }
        
        .software-info {
            flex: 1;
        }
        
        .software-title {
            color: var(--Orange);
            margin-bottom: 10px;
            font-size: 32px;
            border-bottom: 2px solid var(--accent-light);
            padding-bottom: 10px;
        }
        
        .software-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
            font-size: 14px;
            color: #666;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .tabs {
            margin: 30px 0;
            border-bottom: 2px solid var(--accent-light);
        }
        
        .tab-links {
            display: flex;
            gap: 5px;
        }
        
        .tab-link {
            padding: 10px 20px;
            background-color: var(--accent-light);
            border: none;
            border-radius: 8px 8px 0 0;
            cursor: pointer;
            font-weight: 500;
            color: var(--text-color);
        }
        
        .tab-link.active {
            background-color: var(--Orange);
            color: var(--light-color);
        }
        
        .tab-content {
            padding: 20px 0;
        }
        
        .tab-pane {
            display: none;
        }
        
        .tab-pane.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        
        .feature-card {
            background-color: var(--accent-green-light);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        
        .feature-icon {
            background-color: var(--Vert);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            color: var(--light-color);
        }
        
        .feature-title {
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--accent-green-dark);
        }
        
        .spec-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .spec-table th, .spec-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .spec-table th {
            background-color: var(--accent-light);
            color: var(--accent-dark);
            font-weight: 600;
        }
        
        .spec-table tr:nth-child(even) {
            background-color: rgba(255, 127, 0, 0.05);
        }  

        @media (max-width: 768px) {
            .header-content, .footer-content {
                flex-direction: column;
                gap: 15px;
            }
            
            .nav-links {
                margin-top: 15px;
            }
            
            .software-header {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            
            .tab-links {
                overflow-x: auto;
                white-space: nowrap;
                padding-bottom: 5px;
            }
        }
          :root {
        --orange-ci: #ff6b00;
        --vert-ci: #00a651;
        --orange-hover: #e55a00;
        --vert-hover: #008542;
    }

    .documents-section {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .documents-header {
        background: #f8f9fa;
        padding: 20px;
        border-bottom: 2px solid var(--orange-ci);
    }

    .documents-title {
        color: #2c3e50;
        font-size: 24px;
        font-weight: 600;
        margin: 0;
    }

    .search-container {
        background: #f8f9fa;
        padding: 0 20px 20px;
        border-bottom: 1px solid #e9ecef;
    }

    .search-input {
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 10px 15px 10px 40px;
        font-size: 14px;
        width: 100%;
        background: white;
        transition: border-color 0.2s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--orange-ci);
        box-shadow: 0 0 0 3px rgba(255, 107, 0, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        font-size: 14px;
    }

    .table-header {
        background: #f8f9fa;
        padding: 15px 20px;
        border-bottom: 1px solid #e9ecef;
        font-weight: 600;
        color: #495057;
        font-size: 14px;
    }

    .documents-list {
        max-height: 600px;
        overflow-y: auto;
    }

    .document-item {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #f1f3f4;
        transition: background-color 0.2s ease;
        cursor: pointer;
    }

    .document-item:hover {
        background-color: #f8f9fa;
    }

    .document-item:last-child {
        border-bottom: none;
    }

    .file-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
        margin-right: 15px;
        flex-shrink: 0;
    }

    .icon-pdf { background: linear-gradient(135deg, #dc2626, #ef4444); }
    .icon-doc, .icon-docx { background: linear-gradient(135deg, #2563eb, #3b82f6); }
    .icon-xls, .icon-xlsx { background: linear-gradient(135deg, var(--vert-ci), #16a34a); }
    .icon-ppt, .icon-pptx { background: linear-gradient(135deg, var(--orange-ci), #f97316); }
    .icon-jpg, .icon-jpeg, .icon-png, .icon-gif { background: linear-gradient(135deg, #7c3aed, #8b5cf6); }
    .icon-zip, .icon-rar { background: linear-gradient(135deg, #6b7280, #9ca3af); }
    .icon-default { background: linear-gradient(135deg, var(--orange-ci), var(--orange-hover)); }

    .file-info {
        flex: 1;
        min-width: 0;
    }

    .file-name {
        font-weight: 600;
        color: #2c3e50;
        font-size: 16px;
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .file-type {
        color: #6c757d;
        font-size: 13px;
        text-transform: capitalize;
    }

    .file-size {
        color: #6c757d;
        font-size: 14px;
        min-width: 80px;
        text-align: right;
        margin-right: 20px;
    }

    .file-date {
        color: #6c757d;
        font-size: 14px;
        min-width: 100px;
        text-align: right;
        margin-right: 20px;
    }

    .file-actions {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        text-decoration: none;
        font-size: 14px;
    }

    .btn-download {
        background: var(--vert-ci);
        color: white;
    }

    .btn-download:hover {
        background: var(--vert-hover);
        color: white;
        transform: translateY(-1px);
    }

    .btn-delete {
        background: #dc2626;
        color: white;
    }

    .btn-delete:hover {
        background: #b91c1c;
        transform: translateY(-1px);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 20px;
        color: #dee2e6;
    }

    .empty-state h4 {
        color: #495057;
        margin-bottom: 10px;
    }

    .documents-count {
        color: #6c757d;
        font-size: 14px;
        margin-left: 10px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .file-size,
        .file-date {
            display: none;
        }
        
        .document-item {
            padding: 12px 15px;
        }
        
        .file-name {
            font-size: 14px;
        }
        
        .file-type {
            font-size: 12px;
        }
        
        .action-btn {
            width: 28px;
            height: 28px;
            font-size: 12px;
        }
    }
    </style>
</head> 

            <div class="software-header">
                <div class="software-image">
                <a href="{{ route('equipements.downloadQr', $equipement->id) }}">{!! $qrCode !!}</a>
                </div>
                <div class="software-info">
                    <h1 class="software-title">{{ $equipement->des_equipement }}</h1>
                    <div class="software-meta">
                        <div class="meta-item">
                            <span>Marque :</span>
                            <strong>{{ $equipement->marque }}</strong>
                        </div>
                    
                        <div class="meta-item">
                            <span>N° Série :</span>
                            <strong>{{ $equipement->numero_serie }}</strong>
                        </div>
                       
                    </div>

                    {{-- Alerte fin de vie --}}
                    @if($equipement->date_fin_vie)
                        @php
                            $joursRestants = now()->diffInDays($equipement->date_fin_vie, false);
                            $finVieClass = $joursRestants < 0 ? 'danger' : ($joursRestants <= 30 ? 'warning' : 'success');
                            $finVieIcon  = $joursRestants < 0 ? 'bi-x-circle-fill' : ($joursRestants <= 30 ? 'bi-exclamation-triangle-fill' : 'bi-check-circle-fill');
                        @endphp
                        <div class="alert alert-{{ $finVieClass }} py-2 px-3 d-inline-flex align-items-center gap-2 mt-2 mb-0" style="font-size:0.875rem;">
                            <i class="bi {{ $finVieIcon }}"></i>
                            @if($joursRestants < 0)
                                Fin de durée de vie dépassée depuis {{ abs($joursRestants) }} jour(s) ({{ $equipement->date_fin_vie->format('d/m/Y') }})
                            @elseif($joursRestants <= 30)
                                Fin de durée de vie dans {{ $joursRestants }} jour(s) — {{ $equipement->date_fin_vie->format('d/m/Y') }}
                            @else
                                Fin de durée de vie : {{ $equipement->date_fin_vie->format('d/m/Y') }} ({{ $joursRestants }} jours)
                            @endif
                        </div>
                    @endif

                    {{-- Sortie active --}}
                    @if($equipement->sortieActive)
                        <div class="alert alert-danger py-2 px-3 d-inline-flex align-items-center gap-2 mt-2 mb-0" style="font-size:0.875rem;">
                            <i class="bi bi-box-arrow-right"></i>
                            Sortie en cours : <strong>{{ $equipement->sortieActive->type_label }}</strong>
                            depuis le {{ $equipement->sortieActive->date_sortie->format('d/m/Y') }}
                            <a href="{{ route('sorties-equipements.show', $equipement->sortieActive->id) }}" class="ms-2 text-white">Voir</a>
                        </div>
                    @endif

                    {{-- Bouton sortie --}}
                    @if(!$equipement->sortieActive)
                    <div class="mt-3">
                        <a href="{{ route('sorties-equipements.create', $equipement->id) }}"
                           class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-box-arrow-right me-1"></i> Enregistrer une sortie
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="tabs">
                <div class="tab-links">
                    <button class="tab-link active" onclick="openTab(event, 'fonctionnalites')"><i class="fas fa-cog me-2"></i>Spécifications techniques</button>
                    <button class="tab-link" onclick="openTab(event, 'specifications')"><i class="fas fa-history me-2"></i>Historique d'assignation</button>
                    <button class="tab-link" onclick="openTab(event, 'modifications')"><i class="fas fa-cube me-2"></i>Logiciel installé</button>
                    <button class="tab-link" onclick="openTab(event, 'sorties')"><i class="bi bi-box-arrow-right me-2"></i>Sorties</button>
                    <!--</BR><button class="tab-link" onclick="openTab(event, 'documents')"><i class="fas fa-file me-2"></i>Documents</button> -->
                </div>
            </div>
            
            <div class="tab-content">
                <div id="fonctionnalites" class="tab-pane active">
                    <div class="card shadow-sm mb-4 border-0 ">
    <div class="card-header bg-dark text-white py-3">
        <h5 class="mb-0">
            <i class="bi bi-gear-fill me-2"></i>Détails de l'Équipement
        </h5>
    </div>
    <div class="card-body">
        <div class="row g-4">
            <div class="col-md-6 border-end border-1 border-light">
                <div class="p-3 bg-light rounded">
                    <p class="mb-2">
                        <strong class="text-success">Catégorie :</strong> 
                        {{ $equipement->categorie }}
                    </p>
                    <p class="mb-2">
                        <strong class="text-success">Marque :</strong> 
                        {{ $equipement->marque }}
                    </p>
                    <p class="mb-2">
                        <strong class="text-success">Modèle :</strong> 
                        {{ $equipement->modele }}
                    </p>
                    <p class="mb-2">
                        <strong class="text-success">N° Série :</strong> 
                        <span class="badge bg-secondary">{{ $equipement->numero_serie }}</span>
                    </p>
                    <p class="mb-2">
                        <strong class="text-success">Mémoire ROM :</strong> 
                        {{ $equipement->capacite }}
                    </p>
                    <p class="mb-2">
                        <strong class="text-success">Mémoire RAM :</strong> 
                        {{ $equipement->ram }}
                    </p>
                    <p class="mb-0">
                        <strong class="text-success">Date d'acquisition :</strong> 
                        {{ $equipement->date_acquisition }}
                    </p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 bg-light rounded">
                    <p class="mb-2">
                        <strong class="text-success">Nature :</strong> 
                        {{ $equipement->nature }}
                    </p>
                    <p class="mb-2">
                        <strong class="text-success">Processeur :</strong> 
                        {{ $equipement->processeur }}
                    </p>
                    <p class="mb-2">
                        <strong class="text-success">Système :</strong> 
                        {{ $equipement->systeme }}
                    </p>
                    <p class="mb-2">
                        <strong class="text-success">Statut :</strong> 
                        <span class="badge rounded-pill bg-info text-dark">
                            {{ $equipement->statut }}
                        </span>
                    </p>
                    <p class="mb-2">
                        <strong class="text-success">Adresse MAC:</strong> 
                        <code>{{ $equipement->adresse_mac }}</code>
                    </p>
                    <p class="mb-2">
                        <strong class="text-success">Etat :</strong> 
                        <span class="badge bg-success">
                            {{ $equipement->etat }}
                        </span>
                    </p>
                    <p class="mb-0">
                        <strong class="text-success">Ajouter le :</strong> 
                        {{ $equipement->created_at }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer text-end text-muted bg-white border-0">
        <small>Informations détaillées de l'équipement</small>
    </div>
</div>
                </div>
                
                <div id="specifications" class="tab-pane">
                <div class="card shadow-sm mb-4 border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            @if($equipement->assignments && $equipement->assignments->isNotEmpty())
            <table class="table mb-0">
                <thead class="bg-dark t">
                    <tr>
                        <th scope="col" class="border-end border-1">Utilisateur</th>
                        <th scope="col" class="border-end border-1">Fonction</th>
                        <th scope="col" class="border-end border-1">Assignateur</th>
                        <th scope="col" class="border-end border-1">Date d'Assign.</th>
                        <th scope="col" class="border-end border-1">Heure</th>
                        <th scope="col" class="border-end border-1">Date Retour</th>
                        <th scope="col">Heure Retour</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($equipement->assignments as $assignment)
                    <tr>
                        <td>{{ $assignment->user->nom ?? 'Inconnu' }}</td>
                        <td>{{ $assignment->user->fonction ?? 'Inconnu' }}</td>
                        <td>{{ optional($assignment->assignedBy)->nom ?? 'Inconnu' }}</td>
                        <td class="text-nowrap">{{ $assignment->created_at->format('d/m/Y') }}</td>
                        <td class="text-nowrap">{{ $assignment->created_at->format('H:i') }}</td>
                        
                        <td>
                            @if($assignment->returned_at)
                                <span class="text-success">{{ $assignment->returned_at->format('d/m/Y') }}</span>
                            @else
                                <span class="badge bg-danger">En cours</span>
                            @endif
                        </td>
                        <td>
                            @if($assignment->returned_at)
                                <span class="text-success">{{ $assignment->returned_at->format('H:i') }}</span>
                            @else
                                <span class="text-muted">...</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="p-4">
                <div class="alert alert-info border-start border-4 border-info py-3" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    **Aucune assignment** enregistrée pour cet équipement.
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
                </div>
                
                <div id="modifications" class="tab-pane">
                  <div class="card shadow-sm mb-4 border-0">
    <div class="card-header bg-dark text-white py-3">
        <h5 class="mb-0">
            <i class="bi bi-file-earmark-code-fill me-2"></i> Logiciels et Licences Associés 
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            @if($logiciels && $logiciels->isNotEmpty())
            <table class="table table-striped table-hover table-bordered align-middle mb-0">
                <thead class="bg-warning text-dark text-nowrap">
                    <tr>
                        <th scope="col" class="border-end border-1">Clé de licence</th>
                        <th scope="col" class="border-end border-1">Logiciel</th>
                        <th scope="col" class="border-end border-1">Type</th>
                        <th scope="col" class="border-end border-1">Version</th>
                        <th scope="col" class="border-end border-1">Environnement</th>
                        <th scope="col" class="border-end border-1">Langage/Version</th>
                        <th scope="col" class="border-end border-1">SGBD/Version</th>
                        <th scope="col">Date d'expiration</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logiciels as $logiciel)
                    <tr>
                        <td><code class="text-secondary">{{ $logiciel->licence->cle_licence ?? 'N/A' }}</code></td>
                        <td><strong>{{ $logiciel->licence->designation_licence ?? 'Inconnu' }}</strong></td>
                        <td><span class="badge bg-info text-dark">{{ $logiciel->licence->type_licence ?? 'N/A' }}</span></td>
                        <td>{{ $logiciel->licence->version ?? 'N/A' }}</td>
                        <td>{{ $logiciel->licence->environnement ?? 'N/A' }}</td>
                        <td>{{ $logiciel->licence->langage_version ?? 'N/A' }}</td>
                        <td>{{ $logiciel->licence->sgbd_version ?? 'N/A' }}</td>
                        
                        <td>
                            @if($logiciel->licence->date_expiration)
                                @php
                                    $expirationDate = \Carbon\Carbon::parse($logiciel->licence->date_expiration);
                                    $isExpired = $expirationDate->isPast();
                                @endphp
                                <span class="badge {{ $isExpired ? 'bg-danger' : 'bg-success' }}">
                                    {{ $expirationDate->format('d/m/Y') }}
                                </span>
                            @else
                                <span class="badge bg-secondary">Permanente</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="p-4">
                <div class="alert alert-warning border-start border-4 border-warning py-3" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    **Aucun logiciel** avec licence n'est enregistré pour cet équipement.
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
                </div>
                <div id="sorties" class="tab-pane">
                    <div class="card shadow-sm mb-4 border-0">
                        <div class="card-header bg-dark text-white py-3 d-flex align-items-center justify-content-between">
                            <h5 class="mb-0"><i class="bi bi-box-arrow-right me-2"></i>Historique des sorties</h5>
                            @if(!$equipement->sortieActive)
                            <a href="{{ route('sorties-equipements.create', $equipement->id) }}"
                               class="btn btn-sm btn-danger">
                                <i class="bi bi-plus-circle me-1"></i> Nouvelle sortie
                            </a>
                            @endif
                        </div>
                        <div class="card-body p-0">
                            @if($equipement->sorties && $equipement->sorties->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle mb-0">
                                    <thead class="table-secondary">
                                        <tr>
                                            <th>Type</th>
                                            <th>Motif</th>
                                            <th>Prestataire</th>
                                            <th>Date sortie</th>
                                            <th>Retour prévu</th>
                                            <th>Retour effectif</th>
                                            <th>Statut</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($equipement->sorties->sortByDesc('date_sortie') as $s)
                                        @php
                                            $sc = match($s->statut) { 'retourne' => 'bg-success', 'definitif' => 'bg-secondary', default => 'bg-warning text-dark' };
                                            $tc = match($s->type_sortie) { 'maintenance_externe' => 'bg-info text-dark', 'reforme' => 'bg-danger', default => 'bg-primary' };
                                        @endphp
                                        <tr>
                                            <td><span class="badge {{ $tc }}">{{ $s->type_label }}</span></td>
                                            <td class="text-truncate" style="max-width:160px;" title="{{ $s->motif }}">{{ $s->motif }}</td>
                                            <td>{{ $s->prestataire ?? '—' }}</td>
                                            <td>{{ $s->date_sortie->format('d/m/Y') }}</td>
                                            <td>{{ $s->date_retour_prevue?->format('d/m/Y') ?? '—' }}</td>
                                            <td>{{ $s->date_retour_effective?->format('d/m/Y') ?? '—' }}</td>
                                            <td><span class="badge {{ $sc }}">{{ $s->statut_label }}</span></td>
                                            <td>
                                                <a href="{{ route('sorties-equipements.show', $s->id) }}"
                                                   class="btn btn-sm btn-outline-secondary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="p-4 text-center text-muted">
                                <i class="bi bi-box-arrow-right fs-2 mb-2 d-block"></i>
                                Aucune sortie enregistrée pour cet équipement.
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div id="documents" class="tab-pane">
                    <button class="btn bg-success" data-bs-toggle="modal" data-bs-target="#addDocumentModal" data-equipement-id="{{$equipement->id}}">Ajouter</button>
                    @if($documents && $documents->isNotEmpty())
                                            
                        <div class="documents-section">
                            <!-- En-têtes des colonnes (Desktop seulement) -->
                            <div class="table-header d-none d-md-flex">
                                <div style="">Documents</div>
                                <div style="min-width: 80px; text-align: right; margin-right: 20px;">Description</div>
                                <div style="min-width: 100px; text-align: right; margin-right: 20px;">Date</div>
                                <div style="min-width: 80px; text-align: center;">Actions</div>
                            </div>

                            <!-- Liste des documents -->
                            <div class="documents-list" id="documentsList">
                                @forelse($documents as $document)
                                    <div class="document-item" data-name="{{ strtolower($document->titre) }}" data-type="{{ strtolower($document->type_document) }}">
                                        <div class="file-icon icon-{{ strtolower(pathinfo($document->chemin_document, PATHINFO_EXTENSION)) ?: 'default' }}">
                                            @php
                                                $extension = pathinfo($document->chemin_document, PATHINFO_EXTENSION);
                                                $icon = match(strtolower($extension)) {
                                                    'pdf' => 'fas fa-file-pdf',
                                                    'doc', 'docx' => 'fas fa-file-word',
                                                    'xls', 'xlsx' => 'fas fa-file-excel',
                                                    'ppt', 'pptx' => 'fas fa-file-powerpoint',
                                                    'jpg', 'jpeg', 'png', 'gif' => 'fas fa-file-image',
                                                    'zip', 'rar' => 'fas fa-file-archive',
                                                    default => 'fas fa-file'
                                                };
                                            @endphp
                                            <i class="{{ $icon }}"></i>
                                        </div>
                                        
                                        <div class="file-info">
                                            <div class="file-name" title="{{ $document->titre }}">
                                                {{ $document->titre }}
                                            </div>
                                            <div class="file-type">
                                                {{ $document->type_document }}
                                            </div>
                                        </div>
                                        <div class="file-date d-none d-md-block">
                                            {{ $document->created_at->format('d/m/Y') }}
                                        </div>
                                        
                                        <div class="file-actions">
                                            <a href="{{ asset($document->chemin_document) }}" 
                                            download 
                                            class="action-btn btn-download" 
                                            title="Télécharger">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            
                                            <form action="{{ route('documents.destroy', $document) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="action-btn btn-delete" 
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?')" 
                                                        title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <div class="empty-state">
                                        <i class="fas fa-folder-open"></i>
                                        <h4>Aucun document trouvé</h4>
                                        <p>Aucun document n'a été trouvé dans cette section.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @else
                        <p class="py-4 no-history">
                            <i class="fas fa-info-circle me-2"></i>Aucun document enregistré pour cet équipement.
                            
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            
            // Hide all tab content
            tabcontent = document.getElementsByClassName("tab-pane");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].className = tabcontent[i].className.replace(" active", "");
            }
            
            // Remove active class from all tab links
            tablinks = document.getElementsByClassName("tab-link");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            
            // Show the current tab and add an "active" class to the button that opened the tab
            document.getElementById(tabName).className += " active";
            evt.currentTarget.className += " active";
        }
    </script>
@endsection