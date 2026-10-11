<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche Inventaire des Équipements</title>
    <style>
        /* Éviter les @import pour les PDF - définir directement les fonts */
        body { 
            font-family: 'Arial', 'DejaVu Sans', sans-serif; 
            font-size: 12px; 
            line-height: 1.4;
            color: #2c3e50;
            background: #ffffff;
            margin: 0;
            padding: 20px;
        }

        .document-container {
            width: 100%;
            max-width: 1000px;
            margin: auto;
        }

        /* En-tête avec deux colonnes */
        .official-header {
            width: 100%;
            max-width: 1000px;
            table-layout: fixed;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .official-header td {
            vertical-align: top;
            padding: 0 10px;
        }

        /* Style sans-serif pour l'en-tête */
        .ministry-title {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 15px;
            font-weight: bold;
            text-align: center;
            line-height: 1.2;
            color: #2c3e50;
        }

        .republic-info {
            font-family:  sans-serif;
            text-align: center;
            font-size: 11px;
        }

        .republic-title {
            font-family:  sans-serif;
            font-weight: bold;
            color: #2c3e50;
            font-size: 15px;

        }

        .republic-motto {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-style: italic;
            font-size:13px;
            font-weight: normal;
        }

        .flag-colors {
            display: table;
            width: 60px;
            margin: 5px auto;
        }

        .flag-color {
            display: table-cell;
            width: 30px;
            height: 4px;
        }

        .flag-orange { background: #ff8c00; }
        .flag-white { background: #ffffff; }
        .flag-green { background: #228b22; }

        h2 {
            font-family: 'Arial', 'Helvetica', sans-serif;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 20px 0;
            color: #2c3e50;
        }

        .additional-info {
            margin-bottom: 20px;
            text-align: center;
            font-family: 'Arial', 'Helvetica', sans-serif;
        }

        .additional-info p {
            margin: 4px 0;
        }

        /* Tableau */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-family: 'Arial', 'Helvetica', sans-serif;
        }

        th {
            background: #f2f2f2;
            font-weight: bold;
        }

        .footer {
            text-align: right;
            font-size: 11px;
            margin-top: 20px;
            font-family: 'Arial', 'Helvetica', sans-serif;
        }
        
        /* === En-tête sans bordure === */
        .official-header {
            width: 100%;
            max-width: 1000px;
            table-layout: fixed;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .official-header, 
        .official-header tr, 
        .official-header td,
        .official-header th {
            border: none !important;
            padding: 0;
            margin: 0;
            background: transparent;
        }

        /* Optimisations spécifiques pour PDF */
        * {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        img {
            max-width: 100%;
            height: auto;
        }
        .space {
            height: 20px;
        }
        .mini-space{
            height:8px;
        }
        .nom-direction{
            border-bottom:2px solid;
            border-top:2px solid; 
            max-width:15%;
            display:flex;
            justify-content:center;
            margin: auto;
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
<body>
    <div class="document-container">
        <!-- En-tête en deux colonnes via tableau -->
        <table class="official-header">
            <tr>
                <td style="width: 50%; text-align: center;">
                    <div class="ministry-title">MINISTÈRE,</div>
                    <div class="ministry-title">DU PLAN ET DU DÉVELOPPEMENT</div>
                    <div class="mini-space"></div>
                    <h2 class="nom-direction" style="text-transform:uppercase;text-align:center">{{ Auth::user()->direction->code_direction}}</h2>
                </td>

                <td style="width: 50%; text-align: center;">
                    <img src="{{ $directionLogoPath }}" style="width:55px; height:auto;" alt="Logo Côte d'Ivoire" />
                </td>

                </td>
                <td style="width: 50%; text-align: center;">
                    <div class="republic-info">
                        <div class="republic-title">RÉPUBLIQUE DE CÔTE D'IVOIRE</div>
                        <div class="republic-motto">Union - Discipline - Travail</div>
                        <div class="mini-space"></div>
                        <div class="flag-colors">
                            <div class="flag-color flag-orange"></div>
                            <div class="flag-color flag-white"></div>
                            <div class="flag-color flag-green"></div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        <div class="space"></div>
        <!-- Titre -->
        <h2 style="text-decoration: underline;font-size: 18px;">Fiche d'Inventaire des Équipements Informatiques</h2>

        <!-- 
        <div class="additional-info">
            <p><strong>Date :</strong> {{ now()->format('d F Y à H:i') }}</p>
        </div>-->
        <div class="space"></div>

        <table>
            <thead>
                <tr>
                    <th>N°</th> 
                    @foreach($fields as $field)
                        <th>
                            @switch($field)
                                @case('des_equipement') Désignation @break
                                @case('marque') Marque @break
                                @case('modele') Modèle @break
                                @case('categorie') Catégorie @break
                                @case('nature') Nature @break
                                @case('num_inventaire') N° Inventaire @break
                                @case('adresse_mac') Adresse MAC @break
                                @case('numero_serie') N° Série @break
                                @case('date_acquis') Date acquisition @break
                                @case('source_acquisition') Source acquisition @break
                                @case('etat') État @break
                                @case('processeur') Processeur @break
                                @case('capacite') Capacité @break
                                @case('systeme') Système @break
                                @case('statut') Statut @break
                                @default {{ ucfirst($field) }}
                            @endswitch
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($equipements as $equipement)
                    <tr>
                        <td>{{ $loop->iteration }}</td> 
                        @foreach($fields as $field)
                            <td>{{ $equipement[$field] ?? '' }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="space"></div>
        <div class="footer">
            Document généré le {{ date('d/m/Y à H:i') }} - Gestion du Patrimoine Informatique 
        </div>
    </div>
</body>
</html>