<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des équipements assignés</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 2cm;
        }
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            width: 100%;
        }
        /* En-tête avec logos en tableau */
        .header-table {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 2px solid #009E60;
            padding-bottom: 15px;
        }
        .logo-cell-left {
            text-align: left;
            width: 50%;
        }
        .logo-cell-right {
            text-align: right;
            width: 50%;
        }
        .logo-img {
            height: 80px;
            width: auto;
        }
        
        h2 {
            color: #F77F00;
            text-align: center;
            font-size: 22px;
            margin: 20px 0;
            padding: 10px;
            background-color: #f8f8f8;
            border-radius: 4px;
        }
        
        /* Style du tableau principal */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }
        
        .data-table th {
            background-color: #009E60;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #ddd;
        }
        
        .data-table td {
            padding: 8px 10px;
            border: 1px solid #ddd;
        }
        
        .data-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        
        .data-table tr:hover {
            background-color: #e9e9e9;
        }
        
        .confirmed {
            color: #009E60;
            font-weight: bold;
        }
        
        .not-confirmed {
            color: #F77F00;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        /* Styles pour l'impression PDF */
        @media print {
            body {
                font-size: 11pt;
            }
            
            /* Force l'affichage des couleurs */
            * {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            /* Gestion des sauts de page */
            thead {
                display: table-header-group;
            }
            
            tr {
                page-break-inside: avoid;
            }
            
            h2 {
                page-break-after: avoid;
            }
        }
    </style>
</head>
<body>
    <!-- Structure en tableau pour l'en-tête avec logos -->
    <table class="header-table">
        <tr>
            <td class="logo-cell-left">
                <img src="dsid.jpg" class="logo-img" alt="Logo de l'entreprise">
            </td>
            <td class="logo-cell-right">
                <img src="ivoire.png" class="logo-img" alt="Logo principal">
            </td>
        </tr>
    </table>

    <h2>Liste des équipements assignés</h2>

    <table class="data-table">
        <thead>
            <tr>
                <th>Réception</th>
                <th>Utilisateur</th>
                <th>Remetteur</th>
                <th>Équipement</th>
                <th>Marque</th>
                <th>Numéro de série</th>
                <th>Date d'assignation</th>
                <th>Heure</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assignments as $assignment)
            <tr>
                <td class="{{ $assignment->confirmed ? 'confirmed' : 'not-confirmed' }}">
                    {{ $assignment->confirmed ? 'Confirmé' : 'Non confirmé' }}
                </td>
                <td>{{ $assignment->user->nom ?? 'Inconnu' }}</td>
                <td>{{ optional($assignment->assignedBy)->nom ?? 'Inconnu' }}</td>
                <td>{{ $assignment->equipement->des_equipement ?? 'Inconnu' }}</td>
                <td>{{ $assignment->equipement->marque ?? 'Inconnu' }}</td>
                <td>{{ $assignment->equipement->numero_serie ?? 'Inconnu' }}</td>
                <td>{{ $assignment->created_at->format('d/m/Y') }}</td>
                <td>{{ $assignment->created_at->format('H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        Document généré le {{ date('d/m/Y à H:i') }} - Direction des Systèmes d'Information et de la Digitalisation
    </div>
</body>
</html>