<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inventaire Parc Automobile</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #333; }
        h1 { font-size: 14px; text-align: center; margin-bottom: 4px; }
        p.sub { text-align: center; font-size: 9px; color: #666; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #1a1a2e; color: #fff; padding: 5px 6px; text-align: left; font-size: 9px; }
        td { padding: 4px 6px; border-bottom: 1px solid #e0e0e0; font-size: 9px; }
        tr:nth-child(even) td { background: #f9f9f9; }
        .badge-success { background:#198754; color:#fff; padding:1px 5px; border-radius:3px; }
        .badge-primary { background:#0d6efd; color:#fff; padding:1px 5px; border-radius:3px; }
        .badge-warning { background:#ffc107; color:#000; padding:1px 5px; border-radius:3px; }
        .badge-danger  { background:#dc3545; color:#fff; padding:1px 5px; border-radius:3px; }
        .footer { margin-top: 16px; font-size: 8px; color: #999; text-align: right; }
    </style>
</head>
<body>
    <h1>Inventaire du Parc Automobile</h1>
    <p class="sub">Généré le {{ now()->format('d/m/Y à H:i') }} — {{ $vehicules->count() }} véhicule(s)</p>

    <table>
        <thead>
            <tr>
                <th>Immatriculation</th>
                <th>Catégorie</th>
                <th>Marque / Modèle</th>
                <th>Couleur</th>
                <th>État</th>
                <th>Statut</th>
                <th>Affecté à</th>
                <th>Mode acquisition</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vehicules as $v)
            @php
                $statutClass = match($v->statut) {
                    'affecté' => 'badge-primary',
                    'en maintenance' => 'badge-warning',
                    'hors service' => 'badge-danger',
                    default => 'badge-success',
                };
            @endphp
            <tr>
                <td>{{ $v->immatriculation }}</td>
                <td>{{ ucfirst($v->categorie) }}</td>
                <td>{{ $v->marque ?? '—' }} {{ $v->modele ? '/ '.$v->modele : '' }}</td>
                <td>{{ $v->couleur ?? '—' }}</td>
                <td>{{ $v->etat }}</td>
                <td><span class="{{ $statutClass }}">{{ ucfirst($v->statut) }}</span></td>
                <td>{{ $v->affectationActive?->user ? $v->affectationActive->user->nom.' '.$v->affectationActive->user->prenom : '—' }}</td>
                <td>{{ ucfirst($v->mode_acquisition ?? '—') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">Document généré automatiquement par le système GPI</div>
</body>
</html>
