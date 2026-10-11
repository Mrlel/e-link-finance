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
        
        /* Grille de téléchargement - Style épuré */
.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
    margin: 32px 0;
    max-width: 800px;
}

.feature-card {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 24px;
    text-align: center;
    transition: border-color 0.2s ease;
}

.feature-card:hover {
    border-color: #d1d5db;
}

.feature-card a {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #374151;
    text-decoration: none;
    font-weight: 500;
    font-size: 16px;
    line-height: 1.5;
    transition: color 0.2s ease;
}

.feature-card a:hover {
    color: #1f2937;
}

.feature-card ion-icon {
    font-size: 20px;
    flex-shrink: 0;
}

.feature-card .text-gray-500 {
    color: #6b7280;
    font-size: 14px;
    margin: 0;
    font-style: italic;
}

/* Responsive */
@media (max-width: 768px) {
    .features-grid {
        grid-template-columns: 1fr;
        gap: 16px;
        margin: 24px 0;
    }
    
    .feature-card {
        padding: 20px;
    }
    
    .feature-card a {
        font-size: 15px;
    }
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
        /* Spécifications techniques - Style épuré */
.section-title {
    color: #1f2937;
    font-size: 24px;
    font-weight: 600;
    margin: 32px 0 24px 0;
    padding-bottom: 8px;
    border-bottom: 2px solid #e5e7eb;
}

.main-temp {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 24px;
    margin-bottom: 32px;
}

.temp-txt {
    width: 100%;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 16px;
}

.temp-txt p {
    margin: 0;
    padding: 12px 0;
    border-bottom: 1px solid #f3f4f6;
    font-size: 15px;
    line-height: 1.5;
    color: #374151;
}

.temp-txt p:last-child {
    border-bottom: none;
}

.temp-txt strong {
    color: #1f2937;
    font-weight: 600;
    display: inline-block;
    min-width: 140px;
    margin-right: 8px;
}

/* Responsive */
@media (max-width: 768px) {
    .section-title {
        font-size: 20px;
        margin: 24px 0 16px 0;
    }
    
    .main-temp {
        padding: 20px;
    }
    
    .temp-txt {
        grid-template-columns: 1fr;
        gap: 8px;
    }
    
    .temp-txt p {
        padding: 10px 0;
        font-size: 14px;
    }
    
    .temp-txt strong {
        min-width: auto;
        display: block;
        margin-bottom: 4px;
    }
}
    </style>
</head> 
<!--

            <h2 class="text-xl font-semibold text-indigo-600 mb-2">{{ $logiciel->designation_licence }}</h2>

            <p class="text-gray-700"><strong>Type :</strong> {{ $logiciel->type_licence }}</p>
            <p class="text-gray-700"><strong>Expire le :</strong> {{ $logiciel->date_expiration }}</p>
            <p class="text-gray-700"><strong>Clé :</strong> {{ $logiciel->cle_licence }}</p>
            <p class="text-gray-700"><strong>Environnement :</strong> {{ $logiciel->environnement }}</p>
            <p class="text-gray-700"><strong>Langage / Version :</strong> {{ $logiciel->langage_version }}</p>
            <p class="text-gray-700"><strong>SGBD :</strong> {{ $logiciel->sgbd_version }}</p>
            <p class="text-gray-700"><strong>Base utilisée :</strong> {{ $logiciel->base_donnees }}</p>
            <p class="text-gray-700"><strong>Statut :</strong> {{ $logiciel->statut }}</p>

            <div class="mt-4 flex flex-col gap-2">
                @if ($logiciel->fichier_app)
                <a href="{{ asset('storage/' . $logiciel->fichier_app) }}" download
                   class="bg-indigo-500 text-white py-2 px-4 rounded-xl hover:bg-indigo-700 transition">
                    Télécharger l'application
                </a>
                @endif

                @if ($logiciel->fichier_licence)
                <a href="{{ asset('storage/' . $logiciel->fichier_licence) }}" download
                   class="bg-green-500 text-white py-2 px-4 rounded-xl hover:bg-green-700 transition">
                    Télécharger la base de données
                </a>
                @endif
            </div>

-->

            <div class="software-header">
                <div class="software-image">
                    @if ($logiciel->designation_licence == 'word' || $logiciel->designation_licence == 'Word' )
                        <img src="/word.jpg" alt="Logo du logiciel">
                    @elseif ($logiciel->designation_licence == 'excel' || $logiciel->designation_licence == 'Excel')
                        <img src="/excel.jpg" alt="Logo du logiciel">
                    @elseif ($logiciel->designation_licence == 'powerpoint' || $logiciel->designation_licence == 'PowerPoint')
                        <img src="/powerpoint.jpg" alt="Logo du logiciel">
                    @elseif ($logiciel->designation_licence == 'access' || $logiciel->designation_licence == 'Access')
                        <img src="/access.jpg" alt="Logo du logiciel">
                    @elseif ($logiciel->designation_licence == 'outlook' || $logiciel->designation_licence == 'Outlook')
                        <img src="/outlook.jpg" alt="Logo du logiciel">
                    @elseif ($logiciel->designation_licence == 'onenote' || $logiciel->designation_licence == 'OneNote')
                        <img src="/onenote.jpg" alt="Logo du logiciel">
                    @elseif ($logiciel->designation_licence == 'visio' || $logiciel->designation_licence == 'Visio')
                        <img src="/visio.jpg" alt="Logo du logiciel">
                    @elseif ($logiciel->designation_licence == 'sharepoint' || $logiciel->designation_licence == 'SharePoint')
                        <img src="/sharepoint.jpg" alt="Logo du logiciel">
                    @elseif ($logiciel->designation_licence == 'teams' || $logiciel->designation_licence == 'Teams')
                        <img src="/teams.jpg" alt="Logo du logiciel">
                    @elseif ($logiciel->designation_licence == 'power design' || $logiciel->designation_licence == 'Power Design')
                        <img src="/yammer.jpg" alt="Logo du logiciel">
                    @else
                        <img src="/default.jpg" alt="Logo du logiciel">
                    @endif
                </div>
                <div class="software-info">
                    <h1 class="software-title">{{ $logiciel->designation_licence }}</h1>
                    <div class="software-meta">
                        <div class="meta-item">
                            <span>Type :</span>
                            <strong>{{ $logiciel->type_licence }}</strong>
                        </div>
                        <div class="meta-item">
                            <span>Clé de licence :</span>
                            <strong>{{ $logiciel->cle_licence }}</strong>
                        </div>
                        <div class="meta-item">
                            <span>Environnement :</span>
                            <strong>{{ $logiciel->environnement }}</strong>
                        </div>
                       
                    </div>
                  
                </div>
            </div>
            
            <div class="tabs">
                <div class="tab-links">
                    <button class="tab-link active" onclick="openTab(event, 'fonctionnalites')">Telecharger</button>
                    <button class="tab-link" onclick="openTab(event, 'specifications')">Plus de détails</button>
                    <button class="tab-link" onclick="openTab(event, 'modifications')">Modifier</button>
                </div>
            </div>
            
            <div class="tab-content">
                <div id="fonctionnalites" class="tab-pane active">
                    <h2 class="section-title">Téléchargement des fichiers du logiciel</h2>
                    <div class="features-grid">
                        <div class="feature-card">
                          
                            @if ($logiciel->fichier_app)
                                <a href="{{ asset('storage/' . $logiciel->fichier_app) }}" download>
                                    <ion-icon name="cloud-download-outline"></ion-icon> Télécharger l'application
                                </a>
                            @else
                                <p class="text-gray-500">Aucun fichier disponible</p>
                            @endif
                        </div>
                        <div class="feature-card">
                            @if ($logiciel->fichier_licence)
                                <a href="{{ asset('storage/' . $logiciel->fichier_licence) }}" download>
                                    <ion-icon name="cloud-download-outline"></ion-icon> Télécharger la base de données
                                </a>
                            @else
                                <p class="text-gray-500">Aucun fichier disponible</p>
                            @endif
                        </div>

                    </div>
                </div>
                
                <div id="specifications" class="tab-pane">
                    <h2 class="section-title">Spécifications techniques</h2>
                    <div class="main-temp d-flex gap-4 overflow-hidden">
                        <div class="temp-txt">
                            <p><strong>Désignation :</strong> {{ $logiciel->designation_licence }}</p>
                            <p><strong>Langage / Version :</strong> {{ $logiciel->langage_version }}</p>
                            <p><strong>SGBD :</strong> {{ $logiciel->sgbd_version }}</p>
                            <p><strong>Base utilisée :</strong> {{ $logiciel->base_donnees }}</p>
                            <p><strong>Expire le :</strong> {{ $logiciel->date_expiration }}</p>
                            <p><strong>Clé :</strong> {{ $logiciel->cle_licence }}</p>
                            <p><strong>Libellé :</strong> {{ $logiciel->libelle_licence }}</p>

                        </div>
                    </div>
                </div>
                
                <div id="modifications" class="tab-pane">
                    <h2 class="section-title">Modifications</h2>
                    <form class="ui form" method="POST" action="/update_logiciel/{{ $logiciel->id }}">
            @csrf
            
            <div class="three fields">
                <div class="field">
                    <label>Désignation</label>
                    <input type="text" name="designation_licence" value="{{ $logiciel->designation_licence }}" required>
                </div>
                <div class="field">
                    <label>Type de logiciel</label>
                    <select class="ui selection dropdown" name="type_licence" required>
                        <option value="">Sélectionner un type</option>
                        <option value="Système d'exploitation" {{ $logiciel->type_licence == "Système d'exploitation" ? 'selected' : '' }}>Système d'exploitation</option>
                        <option value="Application bureautique" {{ $logiciel->type_licence == "Application bureautique" ? 'selected' : '' }}>Application bureautique</option>
                        <option value="Logiciel de développement" {{ $logiciel->type_licence == "Logiciel de développement" ? 'selected' : '' }}>Logiciel de développement</option>
                        <option value="Antivirus" {{ $logiciel->type_licence == "Antivirus" ? 'selected' : '' }}>Antivirus</option>
                        <option value="Autre" {{ $logiciel->type_licence == "Autre" ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>
                <div class="field">
                    <label>Date d'expiration</label>
                    <input type="date" name="date_expiration" value="{{ $logiciel->date_expiration }}" required>
                </div>
            </div>

            <div class="three fields">
                <div class="field">
                    <label>Clé de licence</label>
                    <input type="text" name="cle_licence" value="{{ $logiciel->cle_licence }}" required>
                </div>
                <div class="field">
                    <label>Environnement</label>
                    <select class="ui selection dropdown" name="environnement" required>
                        <option value="">Sélectionner l'environnement</option>
                        <option value="Windows" {{ $logiciel->environnement == "Windows" ? 'selected' : '' }}>Windows</option>
                        <option value="Linux" {{ $logiciel->environnement == "Linux" ? 'selected' : '' }}>Linux</option>
                        <option value="MacOS" {{ $logiciel->environnement == "MacOS" ? 'selected' : '' }}>MacOS</option>
                        <option value="Multi-plateforme" {{ $logiciel->environnement == "Multi-plateforme" ? 'selected' : '' }}>Multi-plateforme</option>
                    </select>
                </div>
                <div class="field">
                    <label>Version du langage</label>
                    <input type="text" name="langage_version" value="{{ $logiciel->langage_version }}" required>
                </div>
            </div>

            <div class="three fields">
                <div class="field">
                    <label>Version SGBD</label>
                    <input type="text" name="sgbd_version" value="{{ $logiciel->sgbd_version }}" required>
                </div>
                <div class="field">
                    <label>Base de données</label>
                    <select class="ui selection dropdown" name="base_donnees" required>
                        <option value="">Sélectionner la base de données</option>
                        <option value="MySQL" {{ $logiciel->base_donnees == "MySQL" ? 'selected' : '' }}>MySQL</option>
                        <option value="PostgreSQL" {{ $logiciel->base_donnees == "PostgreSQL" ? 'selected' : '' }}>PostgreSQL</option>
                        <option value="Oracle" {{ $logiciel->base_donnees == "Oracle" ? 'selected' : '' }}>Oracle</option>
                    </select>
                </div>
                <div class="field">
                    <label>Fichier de la base de données</label>
                    <input type="file" name="fichier_licence" value="{{ $logiciel->fichier_licence }}">
                </div>
            </div>
            <div class="field">
                <label>Fichier de l'application</label>
                <input type="file" name="fichier_app" value="{{ $logiciel->fichier_app }}">
            </div>
            <div class="field">
                <label>Libellé du logiciel</label>
                <textarea name="libelle_licence" required>{{ $logiciel->libelle_licence }}</textarea>
            </div>

            @if ($errors->any())
                <div class="ui error message">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="ui divider"></div>

           <button type="submit" class="btn bg-success">
                        <i class="save icon"></i>
                        Enregistrer les modifications
                    </button>
        </form>
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