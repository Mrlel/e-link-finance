<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/details.css') }}">
    <title>Détails de l'équipement</title>
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #f3f4f6;
            --text-color: #1f2937;
            --border-color: #e5e7eb;
            --accent-color: #4f46e5;
            --light-color: #ffffff;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9fafb;
            color: var(--text-color);
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }
        
        .equipment-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px 0;
        }
        
        .equipment-card {
            background-color: var(--light-color);
            border-radius: 12px;
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 30px;
        }
        
        .equipment-header {
            background-color: #363B48;
            color: #ffff;
            padding: 20px 30px;
            border-radius: 12px 12px 0 0;
        }
        
        .equipment-header h1 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .equipment-body {
            padding: 25px 30px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .equipment-details {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }
        
        .details-column {
            flex: 1;
            min-width: 250px;
        }
        
        .details-label {
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .equipment-history {
            padding: 25px 30px;
        }
        
        .equipment-history h2 {
            font-size: 1.2rem;
            color: var(--primary-color);
            margin-top: 0;
            margin-bottom: 20px;
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 10px;
        }
        
        .history-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .history-table th {
            background-color: var(--secondary-color);
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid var(--border-color);
            color: var(--primary-color);
        }
        
        .history-table td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .history-table tr:hover {
            background-color: rgba(37, 99, 235, 0.05);
        }
        
        .no-history {
            color: #6b7280;
            font-style: italic;
            text-align: center;
            padding: 20px;
            background-color: var(--secondary-color);
            border-radius: 8px;
        }
        
        .qrcode-container {
            text-align: center;
            margin: 25px 0;
            padding: 20px;
            background-color: var(--secondary-color);
            border-radius: 8px;
        }
        
        .equipment-qrcode h2 {
            text-align: center;
            font-size: 1.1rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        
        /* Responsive design */
        @media (max-width: 768px) {
            .equipment-details {
                flex-direction: column;
            }
            
            .equipment-body, .equipment-history {
                padding: 20px;
            }
            
            .history-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <div class="equipment-container">
        <div class="equipment-card">
            <div class="equipment-header">
                <h1>Informations de l'utilisateur</h1>
            </div>
            <div class="equipment-body">
                <div class="equipment-details">
                    <div class="details-column">
                        <p><span class="details-label">Nom de l'utilisateur :</span> {{ $user->nom ?? 'Non spécifié' }}</p>
                        <p><span class="details-label">Email :</span> {{ $user->email ?? 'Non spécifiée' }}</p>
                    </div>
                    <div class="details-column">
                        <p><span class="details-label">Emploie :</span> {{ $user->emploie ?? 'Non spécifié' }}</p>
                        <p><span class="details-label">Fonction :</span> {{ $user->fonction ?? 'Non spécifié' }}</p>
                    </div>
                    <div class="details-column">
                        <p><span class="details-label">Contact :</span> {{ $user->Contact ?? 'Non spécifié' }}</p>
                        <p><span class="details-label">Grade :</span> {{ $user->grade ?? 'Non spécifié' }}</p>
                    </div>
                </div>
            </div>

            <div class="equipment-history">
                <h2>Historique des assignations</h2>
                @if($user->assignments->isNotEmpty())
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Matériel assigné</th>
                            <th>Catégorie</th>
                            <th>Marque</th>
                            <th>Modèle</th>
                            <th>Numéro de série</th>
                            <th>Date d'assignment</th>
                            <th>Heure</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->assignments as $assignment)
                        <tr>
                            <td>{{ $assignment->equipement->des_equipement ?? 'Inconnu' }}</td>
                            <td>{{ $assignment->equipement->categorie ?? 'Inconnu' }}</td>
                            <td>{{ $assignment->equipement->marque ?? 'Inconnu' }}</td>
                            <td>{{ $assignment->equipement->modele ?? 'Inconnu' }}</td>
                            <td>{{ $assignment->equipement->numero_serie ?? 'Inconnu' }}</td>
                            <td>{{ $assignment->created_at->format('d/m/Y') }}</td>
                            <td>{{ $assignment->created_at->format('H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="no-history">Aucune assignment enregistrée pour cet équipement.</p>
                @endif
            </div>
        </div>
    </div>
</body>
</html>