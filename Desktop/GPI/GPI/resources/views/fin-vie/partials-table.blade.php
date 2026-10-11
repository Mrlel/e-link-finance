@if($items->count() > 0)
<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-dark">
            <tr class="small text-uppercase">
                <th class="ps-3">Désignation / S/N</th>
                <th>Affecté à</th>
                <th>Date Fin de Vie</th>
                <th>Urgence</th>
                <th class="text-end pe-3">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            @php
                $diff = now()->diffInDays($item->date_fin_vie, false);
                $rowClass = $diff < 0 ? 'urgency-expire' : ($diff <= 30 ? 'urgency-critical' : '');
                $badge = $diff < 0 ? 'bg-danger' : ($diff <= 30 ? 'bg-warning text-dark' : 'bg-info');
            @endphp
            <tr class="{{ $rowClass }}">
                <td class="ps-3">
                    <div class="fw-bold text-dark">{{ $item->des_equipement ?? $item->designation }}</div>
                    <div class="small text-muted">{{ $item->numero_serie ?? $item->num_inventaire ?? 'N/A' }}</div>
                </td>
                <td class="small">
                    @if($item->affectationActive?->user)
                        <i class="bi bi-person me-1"></i>{{ $item->affectationActive->user->nom }}
                    @else
                        <span class="text-muted italic">Non affecté</span>
                    @endif
                </td>
                <td class="fw-bold">
                    {{ $item->date_fin_vie->format('d/m/Y') }}
                </td>
                <td>
                    <span class="badge {{ $badge }}">
                        {{ $diff < 0 ? 'Expiré' : $diff.' jours restants' }}
                    </span>
                </td>
                <td class="text-end pe-3">
                    <a href="{{ $type == 'equipement' ? route('equipement.details', $item->id) : route('mobiliers.show', $item->id) }}" 
                       class="btn btn-sm btn-light border">
                        <i class="bi bi-eye"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="p-5 text-center">
    <i class="bi bi-check2-circle text-success fs-1"></i>
    <p class="text-muted mt-2">Aucun élément critique dans cette catégorie.</p>
</div>
@endif