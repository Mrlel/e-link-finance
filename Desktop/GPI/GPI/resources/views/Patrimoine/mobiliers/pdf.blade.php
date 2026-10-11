<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inventaire Mobilier</title>
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
        .badge-secondary { background:#6c757d; color:#fff; padding:1px 5px; border-radius:3px; }
        .footer { margin-top: 16px; font-size: 8px; color: #999; text-align: right; }
    </style>
</head>
<body>
    <h1>Inventaire du Mobilier & Matériel de bureau</h1>
    <p class="sub">Généré le {{ now()->format('d/m/Y à H:i') }} — {{ $mobiliers->count() }} article(s)</p>

    <table>
        <thead>
            <tr>
                <th>N° Inv.</th>
                <th>Désignation</th>
                <th>Marque</th>
                <th>État</th>
                <th>Statut</th>
                <th>Affecté à</th>
                <th>Acquisition</th>
                <th>Fin de vie</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mobiliers as $m)
            @php
                $etatClass = match($m->etat) {
                    'neuf','bon' => 'badge-success',
                    'moyen' => 'badge-warning',
                    default => 'badge-danger',
                };
                $statutClass = match($m->statut) {
                    'affecté' => 'badge-primary',
                    'en réforme' => 'badge-secondary',
                    default => 'badge-success',
                };
            @endphp
            <tr>
                <td>{{ $m->num_inventaire ?? '—' }}</td>
                <td>{{ $m->designation }}</td>
                <td>{{ $m->marque ?? '—' }}</td>
                <td><span class="{{ $etatClass }}">{{ ucfirst($m->etat) }}</span></td>
                <td><span class="{{ $statutClass }}">{{ ucfirst($m->statut) }}</span></td>
                <td>{{ $m->affectationActive?->user ? $m->affectationActive->user->nom.' '.$m->affectationActive->user->prenom : '—' }}</td>
                <td>{{ $m->date_acquisition?->format('d/m/Y') ?? '—' }}</td>
                <td>{{ $m->date_fin_vie?->format('d/m/Y') ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">Document généré automatiquement par le système GPI</div>
</body>
</html>
