@extends('layouts.main-board')
@section('title', 'Tableau de bord — Fin de vie')
@section('content')

<style>
    :root {
        --glass-bg: #ffffff;
        --body-bg: #f8fafc;
        --accent-color: #6366f1;
    }

    /* Cartes Statistiques "Glass" sans bordure */
    .premium-card {
        border: none !important;
        border-radius: 8px;
        background: var(--glass-bg);
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }
    .premium-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.06) !important;
    }
    .icon-shape {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
    }

    /* Tabs Minimalistes type "Boutons flottants" */
    .nav-segment {
        background: #e2e8f0;
        padding: 4px;
        border-radius: 12px;
        display: inline-flex;
        border: none;
    }
    .nav-segment .nav-link {
        border: none !important;
        border-radius: 10px !important;
        color: #64748b;
        font-weight: 600;
        padding: 8px 20px;
        font-size: 0.9rem;
        transition: 0.2s;
    }
    .nav-segment .nav-link.active {
        background: white !important;
        color: #0f172a !important;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    /* Table Moderne */
    .table-wrapper {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }
    .table-modern thead th {
        background-color: #000000ff;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.05em;
        color: #94a3b8;
        padding: 16px;
        border-bottom: 1px solid #f1f5f9;
    }
    .table-modern tbody td { 
        padding: 16px; 
        border-bottom: 1px solid #f8fafc; 
        vertical-align: middle;
    }

    /* Badges Soft (Pastels) */
    .badge-soft {
        padding: 5px 10px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.75rem;
    }
    .bg-soft-danger { background: #fee2e2; color: #dc2626; }
    .bg-soft-warning { background: #fef3c7; color: #b45309; }
    .bg-soft-info { background: #e0f2fe; color: #0369a1; }
    .bg-soft-dark { background: #f1f5f9; color: #475569; }

    /* Custom Select */
    .select-horizon {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-weight: 600;
        color: #475569;
    }
</style>

<div class="container-fluid py-4">
    
    {{-- En-tête --}}
    <div class="row align-items-center mb-5">
        <div class="col-md-7">
            <h1 class="h3 fw-bold text-dark mb-1">Obsolescence & Fin de vie</h1>
            <p class="text-muted mb-0 small">Suivi des actifs arrivant à expiration de maintenance.</p>
        </div>
        <div class="col-md-5 mt-3 mt-md-0">
            <form method="GET" action="{{ route('fin-vie.index') }}" class="d-flex align-items-center justify-content-md-end gap-3">
                <span class="small fw-semibold text-muted">Horizon :</span>
                <select name="jours" class="form-select select-horizon shadow-sm w-auto" onchange="this.form.submit()">
                    @foreach([30=>'30 jours', 90=>'3 mois', 180=>'6 mois', 365=>'1 an'] as $val => $label)
                        <option value="{{ $val }}" {{ $jours == $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    @include('layouts.message')

    {{-- Cartes Stats --}}
    <div class="row g-4 mb-5">
        @php
        $cards = [
            ['lbl' => 'Expirés', 'v' => $stats['expires'], 'c' => '#f41010', 'i' => 'bi-x-octagon'],
            ['lbl' => 'Critiques (30j)', 'v' => $stats['critiques'], 'c' => '#f4da14f8', 'i' => 'bi-exclamation-triangle'],
            ['lbl' => 'À venir', 'v' => $stats['proches'], 'c' => '#2067daff', 'i' => 'bi-clock-history'],
        ];
        @endphp
        @foreach($cards as $c)

<div class="col-12 col-sm-6 col-lg-4">
    <div class="card border-0 bg-light shadow-sm p-5 position-relative stat-card text-decoration-none text-dark">
      <!-- Gradient bar -->
      <div class="position-absolute top-0 start-0 end-0"
           style="height:5px; background:linear-gradient(90deg,{{ $c['c'] }},{{ $c['c'] }});"></div>

      <div class="d-flex justify-content-between align-items-start">
        <div>
          <h4 class="fw-bold text-muted mb-2">{{ $c['lbl'] }}</h4>
          <div class="d-flex align-items-baseline gap-2">
            <span class="fs-1 fw-bold text-dark">{{ number_format($c['v'], 0, ',', ' ') }}</span>
          </div>
        </div>

        <!-- Icône -->
        <div class="d-flex align-items-center justify-content-center rounded p-3"
             style="background:linear-gradient(135deg,{{ $c['c'] }},{{ $c['c'] }}); width:64px; height:64px;">
          <i class="bi {{ $c['i'] }} fs-3 text-white"></i>
        </div>
      </div>

      <!-- Pattern -->
      <div class="position-absolute bottom-0 end-0 opacity-25" style="width:100px; height:100px;">
        <i class="bi {{ $c['i'] }}" style="font-size:100px;"></i>
      </div>
    </div>
  </div>
        @endforeach
    </div>

    {{-- Tabs & Tableaux --}}
    <div class="mb-4">
        <ul class="nav nav-segment" id="pills-tab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-it">
                    <i class="bi bi-laptop me-2"></i>Informatique ({{ $equipements->count() }})
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-mob">
                    <i class="bi bi-lamp me-2"></i>Mobilier ({{ $mobiliers->count() }})
                </button>
            </li>
        </ul>
    </div>

    <div class="table-wrapper shadow-sm">
        <div class="tab-content" id="pills-tabContent">
            {{-- Onglet Informatique --}}
            <div class="tab-pane fade show active" id="tab-it">
                @include('fin-vie.partials-table', ['items' => $equipements, 'type' => 'it'])
            </div>
            {{-- Onglet Mobilier --}}
            <div class="tab-pane fade" id="tab-mob">
                @include('fin-vie.partials-table', ['items' => $mobiliers, 'type' => 'mob'])
            </div>
        </div>
    </div>

</div>

@endsection