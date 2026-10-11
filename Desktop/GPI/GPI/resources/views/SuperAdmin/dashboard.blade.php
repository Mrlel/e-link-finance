@extends('layouts.superDashboard')
@section('content')

<div class="py-4">
    <h2 class="fw-bold font-serif mb-1">Tableau de Bord</h2>
    <p class="text-muted small">Système de gestion du patrimoine — MEPD</p>
</div>

@php
    $totalUsers     = 0; $totalEquipes = 0; $totalConnectes = 0;
    $totalEquipements = 0; $totalLicences = 0;
    $licBientotTotal = 0; $licExpiresTotal = 0;
    foreach($stats as $s) {
        $totalUsers       += $s['utilisateurs']['total'];
        $totalEquipes     += $s['utilisateurs']['equipes'];
        $totalConnectes   += $s['utilisateurs']['connectes'];
        $totalEquipements += $s['equipements']['total'];
        $totalLicences    += $s['logiciels']['total'];
        $licBientotTotal  += $s['logiciels']['bientot_expires'];
        $licExpiresTotal  += $s['logiciels']['expires'];
    }
    $newUsersCount = App\Models\User::whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)->count();
    $directionsSansAdmin = $directions->whereNull('admin_id')->count();
@endphp

<!-- Stat Cards -->
<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-lg-3">
        <a href="{{ route('directions.list-admin') }}" class="card border-0 bg-light shadow-sm p-4 position-relative text-decoration-none text-dark h-100 sa-stat-card">
            <div class="position-absolute top-0 start-0 end-0" style="height:5px; background:linear-gradient(90deg,var(--sa-orange-start),var(--sa-orange-end));"></div>
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h4 class="fw-bold text-muted mb-2" style="font-size:.8rem; text-transform:uppercase; letter-spacing:.5px;">Utilisateurs</h4>
                    <div class="fs-1 fw-bold text-dark">{{ $totalUsers }}</div>
                    <div class="small text-muted">Personnel enregistré</div>
                </div>
                <div class="d-flex align-items-center justify-content-center rounded p-3 flex-shrink-0"
                     style="background:linear-gradient(135deg,var(--sa-orange-start),var(--sa-orange-end)); width:56px; height:56px;">
                    <i class="fas fa-users fs-4 text-white"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <a href="{{ route('directions.list-direction') }}" class="card border-0 bg-light shadow-sm p-4 position-relative text-decoration-none text-dark h-100 sa-stat-card">
            <div class="position-absolute top-0 start-0 end-0" style="height:5px; background:linear-gradient(90deg,var(--sa-green-start),var(--sa-green-end));"></div>
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h4 class="fw-bold text-muted mb-2" style="font-size:.8rem; text-transform:uppercase; letter-spacing:.5px;">Directions</h4>
                    <div class="fs-1 fw-bold text-dark">{{ $directions->count() }}</div>
                    <div class="small text-muted">{{ $directionsActives }} actives</div>
                </div>
                <div class="d-flex align-items-center justify-content-center rounded p-3 flex-shrink-0"
                     style="background:linear-gradient(135deg,var(--sa-green-start),var(--sa-green-end)); width:56px; height:56px;">
                    <i class="fas fa-building fs-4 text-white"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <a href="{{ route('directions.list-admin') }}" class="card border-0 bg-light shadow-sm p-4 position-relative text-decoration-none text-dark h-100 sa-stat-card">
            <div class="position-absolute top-0 start-0 end-0" style="height:5px; background:linear-gradient(90deg,var(--sa-blue-start),var(--sa-blue-end));"></div>
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h4 class="fw-bold text-muted mb-2" style="font-size:.8rem; text-transform:uppercase; letter-spacing:.5px;">Comptes actifs</h4>
                    <div class="fs-1 fw-bold text-dark">{{ $totalConnectes }}</div>
                    <div class="small text-muted">Mots de passe changés</div>
                </div>
                <div class="d-flex align-items-center justify-content-center rounded p-3 flex-shrink-0"
                     style="background:linear-gradient(135deg,var(--sa-blue-start),var(--sa-blue-end)); width:56px; height:56px;">
                    <i class="fas fa-user-check fs-4 text-white"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 bg-light shadow-sm p-4 position-relative h-100 sa-stat-card">
            <div class="position-absolute top-0 start-0 end-0" style="height:5px; background:linear-gradient(90deg,var(--sa-purple-start),var(--sa-purple-end));"></div>
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h4 class="fw-bold text-muted mb-2" style="font-size:.8rem; text-transform:uppercase; letter-spacing:.5px;">Équipements</h4>
                    <div class="fs-1 fw-bold text-dark">{{ $totalEquipements }}</div>
                    <div class="small text-muted">Actifs recensés</div>
                </div>
                <div class="d-flex align-items-center justify-content-center rounded p-3 flex-shrink-0"
                     style="background:linear-gradient(135deg,var(--sa-purple-start),var(--sa-purple-end)); width:56px; height:56px;">
                    <i class="fas fa-laptop fs-4 text-white"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Nécessite votre attention
@if($licExpiresTotal > 0 || $licBientotTotal > 0 || $directionsInactives > 0 || $directionsSansAdmin > 0)
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h6 class="fw-bold mb-3"><i class="fas fa-bell text-warning me-2"></i>Nécessite votre attention</h6>
        <div class="row g-2">
            @if($licExpiresTotal > 0)
            <div class="col-12 col-md-6">
                <a href="{{ route('superadmin.rapport') }}" class="attention-item danger d-flex align-items-center justify-content-between text-decoration-none text-dark">
                    <span><i class="fas fa-key me-2 text-danger"></i>{{ $licExpiresTotal }} licence(s) expirée(s)</span>
                    <i class="fas fa-chevron-right small text-muted"></i>
                </a>
            </div>
            @endif
            @if($licBientotTotal > 0)
            <div class="col-12 col-md-6">
                <a href="{{ route('superadmin.rapport') }}" class="attention-item d-flex align-items-center justify-content-between text-decoration-none text-dark">
                    <span><i class="fas fa-hourglass-half me-2 text-warning"></i>{{ $licBientotTotal }} licence(s) bientôt expirée(s)</span>
                    <i class="fas fa-chevron-right small text-muted"></i>
                </a>
            </div>
            @endif
            @if($directionsSansAdmin > 0)
            <div class="col-12 col-md-6">
                <a href="{{ route('directions.list-direction') }}" class="attention-item d-flex align-items-center justify-content-between text-decoration-none text-dark">
                    <span><i class="fas fa-user-slash me-2 text-warning"></i>{{ $directionsSansAdmin }} direction(s) sans administrateur</span>
                    <i class="fas fa-chevron-right small text-muted"></i>
                </a>
            </div>
            @endif
            @if($directionsInactives > 0)
            <div class="col-12 col-md-6">
                <a href="{{ route('directions.list-direction') }}?statut=inactive" class="attention-item d-flex align-items-center justify-content-between text-decoration-none text-dark">
                    <span><i class="fas fa-pause-circle me-2 text-secondary"></i>{{ $directionsInactives }} direction(s) inactive(s)</span>
                    <i class="fas fa-chevron-right small text-muted"></i>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endif
-->
<!-- Charts -->
<div class="row g-3 mb-4">
    <!-- Utilisateurs par état -->
    <div class="col-12 col-md-4">
        <div class="chart-card h-100">
            <div class="chart-title"><i class="fas fa-users me-2 text-success"></i>Utilisateurs</div>
            <div class="chart-container">
                <canvas id="usersChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Directions actives/inactives -->
    <div class="col-12 col-md-4">
        <div class="chart-card h-100">
            <div class="chart-title"><i class="fas fa-building me-2 text-primary"></i>Directions</div>
            <div class="chart-container">
                <canvas id="directionsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Licences -->
    <div class="col-12 col-md-4">
        <div class="chart-card h-100">
            <div class="chart-title"><i class="fas fa-key me-2 text-warning"></i>Logiciels</div>
            <div class="chart-container">
                <canvas id="licencesChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Comparaison directions -->
<div class="chart-card mb-4">
    <div class="chart-title"><i class="fas fa-chart-bar me-2 text-danger"></i>Comparaison par direction</div>
    <div class="chart-container" style="height:300px;">
        <canvas id="directionComparisonChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    Chart.defaults.font.family = "'Segoe UI', sans-serif";
    Chart.defaults.color = '#555';

    const c = {
        green:  'rgba(0, 158, 96, 0.8)',
        red:    'rgba(255, 59, 48, 0.8)',
        blue:   'rgba(0, 122, 255, 0.8)',
        grey:   'rgba(142, 142, 147, 0.6)',
        orange: 'rgba(255, 149, 0, 0.8)',
        purple: 'rgba(88, 86, 214, 0.8)',
    };

    // --- Utilisateurs ---
    const userTotals = { equipes: 0, nonEquipes: 0, connectes: 0, nonConnectes: 0 };
    let totalU = 0;
    @foreach($stats as $stat)
        userTotals.equipes    += {{ $stat['utilisateurs']['equipes'] }};
        userTotals.nonEquipes += {{ $stat['utilisateurs']['non_equipes'] }};
        userTotals.connectes  += {{ $stat['utilisateurs']['connectes'] }};
        totalU += {{ $stat['utilisateurs']['total'] }};
    @endforeach
    userTotals.nonConnectes = totalU - userTotals.connectes;

    new Chart(document.getElementById('usersChart'), {
        type: 'doughnut',
        data: {
            labels: ['Équipés', 'Non équipés', 'Actifs', 'Inactifs'],
            datasets: [{ data: [userTotals.equipes, userTotals.nonEquipes, userTotals.connectes, userTotals.nonConnectes],
                backgroundColor: [c.green, c.red, c.blue, c.grey], borderWidth: 2, borderColor: '#fff' }]
        },
        options: { responsive: true, maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { font: { size: 11 }, padding: 10 } } } }
    });

    // --- Directions ---
    new Chart(document.getElementById('directionsChart'), {
        type: 'doughnut',
        data: {
            labels: ['Actives', 'Inactives'],
            datasets: [{ data: [{{ $directionsActives }}, {{ $directionsInactives }}],
                backgroundColor: [c.green, c.grey], borderWidth: 2, borderColor: '#fff' }]
        },
        options: { responsive: true, maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { font: { size: 11 }, padding: 10 } } } }
    });

    // --- Licences ---
    let licActifs = 0, licBientot = 0, licExpires = 0;
    @foreach($stats as $stat)
        licActifs   += {{ $stat['logiciels']['actifs'] }};
        licBientot  += {{ $stat['logiciels']['bientot_expires'] }};
        licExpires  += {{ $stat['logiciels']['expires'] }};
    @endforeach

    new Chart(document.getElementById('licencesChart'), {
        type: 'doughnut',
        data: {
            labels: ['Actives', 'Bientôt expirées', 'Expirées'],
            datasets: [{ data: [licActifs, licBientot, licExpires],
                backgroundColor: [c.green, c.orange, c.red], borderWidth: 2, borderColor: '#fff' }]
        },
        options: { responsive: true, maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { font: { size: 11 }, padding: 10 } } } }
    });

    // --- Comparaison directions ---
    const labels = [], dataEq = [], dataU = [], dataL = [];
    @foreach($stats as $dirId => $stat)
        labels.push("{{ $stat['direction'] }}");
        dataEq.push({{ $stat['equipements']['total'] }});
        dataU.push({{ $stat['utilisateurs']['total'] }});
        dataL.push({{ $stat['logiciels']['total'] }});
    @endforeach

    new Chart(document.getElementById('directionComparisonChart'), {
        type: 'bar',
        data: {
            labels,
            datasets: [
                { label: 'Équipements', data: dataEq, backgroundColor: c.blue,   borderRadius: 6, borderWidth: 0 },
                { label: 'Utilisateurs', data: dataU, backgroundColor: c.green,  borderRadius: 6, borderWidth: 0 },
                { label: 'Licences',     data: dataL, backgroundColor: c.purple, borderRadius: 6, borderWidth: 0 },
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)' } },
                x: { grid: { display: false } }
            },
            plugins: { legend: { position: 'top', labels: { font: { size: 12, weight: '600' }, usePointStyle: true } } }
        }
    });
});
</script>
@endsection
