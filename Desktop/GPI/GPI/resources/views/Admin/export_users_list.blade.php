<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title>Liste des Utilisateurs - Document Administratif</title>
    <style>
        /* Configuration générale pour l'impression PDF (CSS PURE) */
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9.5pt; /* Taille réduite pour faire tenir les 9 colonnes */
            margin: 40px 30px; /* Marges confortables */
            color: #1a1a1a;
        }
        
        /* --- Styles de l'En-tête de la République (Copie du modèle précédent) --- */
        .official-header {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .official-header td {
            vertical-align: top;
            padding: 0 10px;
            border: none !important;
            margin: 0;
            background: transparent;
        }
        .ministry-title {
            font-family: sans-serif;
            font-size: 11pt;
            font-weight: bold;
            text-align: center;
            line-height: 1.2;
            color: #2c3e50;
        }
        .republic-info {
            font-family: sans-serif;
            text-align: center;
            font-size: 9pt;
        }
        .republic-title {
            font-weight: bold;
            color: #2c3e50;
            font-size: 11pt;
        }
        .republic-motto {
            font-style: italic;
            font-size: 9pt;
            font-weight: normal;
        }
        .flag-colors {
            display: table;
            width: 60px;
            margin: 5px auto;
        }
        .flag-color {
            display: table-cell;
            width: 33.33%;
            height: 4px;
        }
        .flag-orange { background: #ff8c00; } /* Orange CI */
        .flag-white { background: #ffffff; }
        .flag-green { background: #228b22; } /* Vert CI */
        .mini-space{ height:5px; }
        .space{ height:20px; }

        .nom-direction{
            border-bottom: 1px solid;
            max-width: 110px;
            margin: 0 auto;
            border-top:1px solid; 
            padding: 2px 0;
            font-size: 9pt;
            max-width: fit-content;
            margin: 5px auto 0;
            font-weight: normal;
        }
        /* --- FIN Styles de l'En-tête de la République --- */

        /* --- CONTENEUR DU TITRE PRINCIPAL --- */
        .main-title-container {
            text-align: center;
            padding-bottom: 10px;
            margin-bottom: 25px;
            border-bottom: 2px solid #000;
        }
        .main-title-container h1 {
            font-size: 16pt;
            font-weight: bold;
            margin: 0;
            color: #000;
            text-transform: uppercase;
        }
        .main-title-container .subtitle {
            font-size: 10pt;
            margin: 5px 0 0;
            color: #444;
        }
        .date-info {
            text-align: right;
            font-size: 9pt;
            margin-bottom: 15px;
            color: #555;
            position: absolute;
            top: 0;
            right: 0;
        }

        /* --- TABLEAU DE DONNÉES --- */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background: #e9ecef; /* Gris clair formel */
            border: 1px solid #000;
            padding: 8px 5px;
            text-align: center;
            font-weight: bold;
            font-size: 9.5pt;
            color: #000;
            text-transform: uppercase;
            vertical-align: middle;
        }
        td {
            border: 1px solid #aaa; /* Bordure fine */
            padding: 6px 5px;
            font-size: 9pt;
            vertical-align: top;
            line-height: 1.3;
        }
        tbody tr:nth-child(even) {
            background-color: #f8f8f8;
        }
        /* Mise en évidence des colonnes clés */
        td:nth-child(1) { /* Nom */
            font-weight: 600;
        }
        td:nth-child(3) { /* Matricule */
            font-weight: bold;
            color: #333;
            text-align: center;
        }
        
        /* --- Pied de page --- */
        .footer {
            text-align: right;
            font-size: 8pt;
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            color: #555;
        }
    </style>
</head>
<body>

    <table class="official-header">
        <tr>
            <td style="width: 40%; text-align: center;">
                <div class="ministry-title">MINISTÈRE,</div>
                <div class="ministry-title">DU PLAN ET DU DÉVELOPPEMENT</div>
                <div class="mini-space"></div>
                <p class="nom-direction" style="text-transform:uppercase;text-align:center">{{ Auth::user()->direction->code_direction}}</p>
            </td>

            

            <td style="width: 40%; text-align: center;">
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

    <div class="main-title-container" style="border-bottom: none;">
        <h1 style="text-decoration: underline; margin-bottom: 5px;">Liste Détaillée du Personnel</h1>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;">N°</th>
                <th style="width: 20%;">Nom Prenoms</th>
                <th style="width: 8%;">Matricule</th>
                <th style="width: 25%;">Emploi</th>
                <th style="width: 15%;">Fonction</th>
                <th style="width: 5%;">Grade</th>
                <th style="width: 15%;">Date prise de service</th>
                <th style="width: 25%;">Date 1ère prise de service</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->nom }} {{ $user->prenom }}</td>
                    <td>{{ $user->matricule }}</td>
                    <td>{{ $user->emploie }}</td>
                    <td>{{ $user->fonction_id ? $user->fonction->fonction : '' }}</td>
                    <td>{{ $user->grade }}</td>
                    <td>{{ $user->date_prise_service_un }}</td>
                    <td>{{ $user->date_prise_service }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        Document généré le {{ date('d/m/Y à H:i') }}
    </div>

</body>
</html>