<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des assignments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 5px;
            text-align: left;
        }

        th {
            background-color:rgb(0, 0, 0);
            color: #fff;
        }
    </style>
</head>
<body>
            <div style="text-align: center;" class="logo">
                <img src="logo.png" height="100px" width="100px" alt="">
                <h1 style="color: #007bff;font-size:17px">Direction des Systèmes d'Information et <br>de la Digitalisation</h1>
            </div>
    <h2 style="text-align: center;">Liste des equipements assignés</h2>
    <table class="uk-table uk-table-divider">
        <thead>
            <tr>
                <th>ID</th>
                <th>Utilisateur</th>
                <th>Remetteur</th>
                <th>Équipement</th>
                <th>Marque</th>
                <th>Numero de serie</th>
                <th>Date d'assignment</th>
                <th>Heure</th>
            </tr>
        </thead>
        <tbody>
            @foreach($licences as $licence)
            <tr>
                <td>{{ $licence->id }}</td>
                <td>{{ $licence->user->name ?? 'Inconnu' }}</td>
                <td>{{ optional($licence->assignedBy)->name ?? 'Inconnu' }}</td>
                <td>{{ $licence->logiciel->designation_licence ?? 'Inconnu' }}</td>
                <td>{{ $licence->logiciel->type_licence ?? 'Inconnu' }}</td>
                <td>{{ $licence->created_at->format('d/m/Y') }}</td>
                <td>{{ $licence->created_at->format('H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
