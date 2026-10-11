<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inventaire Patrimoine Divers</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #333; }
        h1 { font-size: 14px; text-align: center; margin-bottom: 4px; }
        p.sub { text-align: center; font-size: 9px; color: #666; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #1a1a2e; color: #fff; padding: 5px 6px; text-align: left; font-size: 9px; }
        td { padding: 4px 6px; border-bottom: 1px solid #e0e0e0; font-size: 9px; }
        tr:nth-child(even) td { background: #f9f9f9; }
        .badge-success { background:#198754; color:#fff; padding:1px 5px; border-radius:3px; }
        .badge-warning { background:#ffc107; color:#000; padding:1px 5px; border-radius:3px; }
        .badge-danger  { background:#dc3545; color:#fff; padding:1px 5px; border-radius:3px; }
        .footer { margin-top: 16px; font-size: 8px; color: #999; text-align: right; }
    </style>
</head>
<body>
    <h1>Inventaire du Patrimoine Divers (Fournitures & Consommables)</h1>
    <p class="sub">Généré le {{ now()->format('d/m/Y à H:i') }} — {{ $items->count() }} article(s)</p>

    <table>
        <thead>
            <tr>
                <th>Libellé</th>
                <th>Catégorie</th>
                <th>Couleur</th>
                <th>Quantité en stock</th>
                <th>État</th>
                <th>Statut</th>
                <th>Date acquisition</th>
                <th>Assigné à</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            @php
                $statutClass = match($item->statut) {
                    'épuisé' => 'badge-danger',
                    'partiellement assigné' => 'badge-warning',
                    default => 'badge-success',
                };
            @endphp
            <tr>
                <td>{{ $item->libelle }}</td>
                <td>{{ ucfirst($item->categorie ?? '—') }}</td>
                <td>{{ $item->couleur ?? '—' }}</td>
                <td>{{ $item->nombre }}</td>
                <td>{{ ucfirst($item->etat) }}</td>
                <td><span class="{{ $statutClass }}">{{ ucfirst($item->statut) }}</span></td>
                <td>{{ $item->date_acquisition ?? '—' }}</td>
                <td>
                    {{ $item->utilisateursActuels->isEmpty() ? '—' : $item->utilisateursActuels->map(fn($u) => "{$u->nom} {$u->prenom} (x{$u->pivot->quantite})")->implode(', ') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">Document généré automatiquement par le système GPI</div>
</body>
</html>
