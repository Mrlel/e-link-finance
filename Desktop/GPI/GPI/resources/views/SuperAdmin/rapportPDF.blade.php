<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport de Gestion du Patrimoine</title>
    <style>
        @page {
            size: A4;
            margin: 2cm;
        }
       
        /* Table pour chaque ligne d'information */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table tr {
            border-bottom: 1px dotted #ddd;
        }
        .info-table td {
            padding: 8px 0;
        }
        .label-cell {
            text-align: left;
            font-size: 12px;
        }
        .value-cell {
            text-align: right;
            font-size: 12px;
        }
        .section {
            margin-bottom: 15px;
        }
        .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #F77F00;
            padding-top: 10px;
        }
        
        /* Styles spécifiques pour l'impression PDF */
        @media print {
            body {
                font-size: 8pt;
            }
            
            /* Force l'affichage des couleurs d'arrière-plan */
            * {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            /* Directives pour les sauts de page */
            .rapport-body {
                page-break-before: auto;
            }
            
            h3 {
                page-break-after: avoid;
            }
            
            .info-table {
                page-break-inside: avoid;
            }
        }
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
            font-size: 10px;
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
            font-size: 10px;

        }

        .republic-motto {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-style: italic;
            color: #7f8c8d;
            font-size:7px;
            font-weight: normal;
        }

        .flag-colors {
            display: table;
            width: 8px;
            margin: 5px auto;
        }

        .flag-color {
            display: table-cell;
            width: 15px;
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
    </style>
</head>
<body>
  
 <!-- En-tête en deux colonnes via tableau -->
 <table class="official-header">
            <tr>
                <td style="width: 50%; text-align: center;">
                    <div class="ministry-title">MINISTÈRE</div>
                    <div class="ministry-title">DU PLAN ET DU DÉVELOPPEMENT</div>
                    <div class="mini-space"></div>
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
        <h2 style="text-decoration: underline;font-size: 18px;">Fiche d'Inventaire des Actifs</h2>

    @foreach($stats as $directionStats)
    <div class="rapport-body">

        <div class="section">
            <h3>Les Équipements</h3>
            <table class="info-table">
                <tr>
                    <td class="label-cell">Total</td>
                    <td class="value-cell">{{ $directionStats['equipements']['total'] }}</td>
                </tr>
                <tr>
                    <td class="label-cell">En stock</td>
                    <td class="value-cell">{{ $directionStats['equipements']['en_stock'] }}</td>
                </tr>
                <tr>
                    <td class="label-cell">En service</td>
                    <td class="value-cell">{{ $directionStats['equipements']['en_service'] }}</td>
                </tr>
                <tr>
                    <td class="label-cell">En maintenance</td>
                    <td class="value-cell">{{ $directionStats['equipements']['en_maintenance'] }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Hors service</td>
                    <td class="value-cell">{{ $directionStats['equipements']['hors_service'] }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h3>Les Utilisateurs</h3>
            <table class="info-table">
                <tr>
                    <td class="label-cell">Total</td>
                    <td class="value-cell">{{ $directionStats['utilisateurs']['total'] }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Équipés</td>
                    <td class="value-cell">{{ $directionStats['utilisateurs']['equipes'] }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Non équipés</td>
                    <td class="value-cell">{{ $directionStats['utilisateurs']['non_equipes'] }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Connectés</td>
                    <td class="value-cell">{{ $directionStats['utilisateurs']['connectes'] }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h3>Les Logiciels</h3>
            <table class="info-table">
                <tr>
                    <td class="label-cell">Total</td>
                    <td class="value-cell">{{ $directionStats['logiciels']['total'] }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Actifs</td>
                    <td class="value-cell">{{ $directionStats['logiciels']['actifs'] }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Bientôt expirés</td>
                    <td class="value-cell">{{ $directionStats['logiciels']['bientot_expires'] }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Expirés</td>
                    <td class="value-cell">{{ $directionStats['logiciels']['expires'] }}</td>
                </tr>
            </table>
        </div>
    </div>
    @endforeach

    <div class="footer">
        Direction des Systèmes d'Information et de la Digitalisation © {{ $dateToday->format('Y') }}<br>
        Généré le : {{ $dateToday->format('d/m/Y à H:i') }}
    </div>
</body>
</html>