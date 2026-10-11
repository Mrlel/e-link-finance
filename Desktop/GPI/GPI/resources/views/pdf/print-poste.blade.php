<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fiche technique - {{ $poste->code_poste }}</title>
    <style>
        /* Styles optimisés pour la génération PDF */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.4;
            color: #333;
            width: 100%;
            margin: 0;
            padding: 10px;
            font-size: 12px;
        }
        
        /* En-tête officiel - version simplifiée */
        .official-header {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .official-header td {
            vertical-align: top;
            padding: 0;
            text-align: center;
        }
        .ministry-title {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 3px;
        }
        .republic-title {
            font-weight: bold;
            font-size: 12px;
        }
        .republic-motto {
            font-style: italic;
            font-size: 10px;
            margin: 3px 0;
        }
        .flag-colors {
            width: 60px;
            height: 4px;
            margin: 3px auto;
            overflow: hidden;
        }
        .flag-color {
            width: 20px;
            height: 4px;
            float: left;
        }
        .flag-orange { background: #ff8c00; }
        .flag-white { background: #ffffff; border: 1px solid #ddd; }
        .flag-green { background: #228b22; }
        
        /* Header principal */
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 1px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 5px 0;
            font-size: 16px;
            color: #2c3e50;
        }
        .header .date {
            color: #7f8c8d;
            font-size: 10px;
        }
        
        /* Sections */
        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .section-title {
            background-color:rgb(14, 14, 14);
            color: white;
            padding: 5px 10px;
            margin: 15px 0 10px 0;
            font-size: 13px;
            font-weight: bold;
        }
        
        /* Grille d'information - version table pour meilleure compatibilité */
        .info-table {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 4px 0;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            color: #2c3e50;
            width: 40%;
        }
        
        /* Cartes d'équipement */
        .equipment-card {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .equipment-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .equipment-title {
            font-size: 13px;
            color: #2c3e50;
            margin: 0;
            font-weight: bold;
        }
        .serial {
            font-size: 11px;
            color: #7f8c8d;
        }
        
        /* Détails équipement - version table */
        .detail-table {
            width: 100%;
            border-collapse: collapse;
        }
        .detail-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        .detail-label {
            width: 120px;
            font-weight: bold;
            color: #7f8c8d;
        }
        
        /* QR Code */
        .qr-code {
            text-align: center;
            margin: 15px 0;
        }
        .qr-code img {
            width: 100px;
            height: 100px;
            border: 1px solid #ddd;
            padding: 5px;
        }
        .qr-code p {
            margin: 5px 0 0 0;
            font-size: 10px;
        }
        
        /* Signature */
        .signature {
            margin-top: 30px;
            text-align: right;
        }
        .signature-line {
            border-top: 1px solid #333;
            width: 150px;
            display: inline-block;
            margin-top: 30px;
        }
        
        /* Pour les impressions */
        @media print {
            body {
                padding: 0;
                font-size: 10pt;
            }
            .header {
                margin-top: 0;
            }
            .equipment-card {
                page-break-inside: avoid;
            }
        }
        .mini-space{
            height:5px;
        }
         .nom-direction{
            border-bottom:2px solid;
            border-top:2px solid; 
            max-width:15%;
            display:flex;
            justify-content:center;
            margin: auto;
        }
        .big-space{
            height:15rem;
        }
    </style>
</head>
<body>
    <!-- En-tête officiel simplifié -->
    <table class="official-header">
        <tr>
            <td style="width: 50%;">
                <div class="ministry-title">MINISTÈRE</div>
                <div class="ministry-title">DU PLAN ET DU DÉVELOPPEMENT</div>
                <div class="nom-direction" style="font-size: 11px; margin-top: 5px; font-weight: bold;">{{ Auth::user()->direction->code_direction}}</div>
            </td>
            <td style="width: 50%;">
                <div class="republic-title">RÉPUBLIQUE DE CÔTE D'IVOIRE</div>
                <div class="republic-motto">Union - Discipline - Travail</div>
                <div class="mini-space"></div>
                <div class="flag-colors">
                    <div class="flag-color flag-orange"></div>
                    <div class="flag-color flag-white"></div>
                    <div class="flag-color flag-green"></div>
                </div>
            </td>
        </tr>
    </table>

        <div class="mini-space"></div>

    <div class="header">
        <h1>Fiche technique du poste {{ $poste->emplacement }}</h1>
        <div class="date">Généré le {{ now()->format('d/m/Y à H:i') }}</div>
    </div>

    <div class="section">
        <div class="section-title">Code Poste : {{ $poste->code_poste }} ({{ $poste->equipements->count() }})</div>
        
        @php $uc = $poste->equipements->where('categorie', 'unite centrale')->first(); @endphp
        @if($uc)
        <div class="equipment-card">

            <table class="detail-table">
                <tr>
                    <td class="detail-label">Marque</td>
                    <td>{{ $uc->marque ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="detail-label">Modèle</td>
                    <td>{{ $uc->modele ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="detail-label">Processeur</td>
                    <td>{{ $uc->processeur ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="detail-label">Mémoire RAM</td>
                    <td>{{ $uc->ram ?? 'N/A' }} Go</td>
                </tr>
                <tr>
                    <td class="detail-label">Système d'exploitation</td>
                    <td>{{ $uc->systeme ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="detail-label">Adresse MAC</td>
                    <td>{{ $uc->adresse_mac ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="detail-label">État</td>
                    <td>{{ ucfirst($uc->etat) }}</td>
                </tr>
            </table>
        </div>
        @endif

        @foreach($poste->equipements->whereIn('categorie', ['ecran', 'clavier', 'souris'])->groupBy('categorie') as $categorie => $equipements)
            <div class="equipment-card">
                <div class="section-title" style="margin-top: 0; margin-bottom: 10px;">{{ ucfirst($categorie) }}(s)</div>
                @foreach($equipements as $equipement)
                    <table class="detail-table" style="margin-bottom: 10px; width: 100%; {{ !$loop->last ? 'border-bottom: 1px dashed #ddd; padding-bottom: 10px;' : '' }}">
                        <tr>
                            <td class="detail-label">N° Série</td>
                            <td>{{ $equipement->numero_serie ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="detail-label">Marque</td>
                            <td>{{ $equipement->marque ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="detail-label">Modèle</td>
                            <td>{{ $equipement->modele ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="detail-label">État</td>
                            <td>{{ ucfirst($equipement->etat) }}</td>
                        </tr>
                    </table>
                @endforeach
            </div>

        @endforeach
    </div>

    <div class="big-space"></div>

    <table class="official-header">
        <tr>
            <td style="width: 50%;">
                
            </td>
            <td style="width: 50%;">
                @if($poste->qr_code)
                <div class="qr-code">
                    <img src="{{ public_path('storage/' . $poste->qr_code) }}" alt="QR Code" height="100" width="auto">
                    <p>Code QR du poste</p>
                </div>
                @endif
            </td>

            <td style="width: 50%;">
              
            </td>
        </tr>
    </table>

    <script>
        // Imprimer automatiquement quand la page est chargée
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 200);
        };
        
        // Rediriger vers la page précédente après l'impression
        window.onafterprint = function() {
            setTimeout(function() {
                window.history.back();
            }, 500);
        };
    </script>
</body>
</html>