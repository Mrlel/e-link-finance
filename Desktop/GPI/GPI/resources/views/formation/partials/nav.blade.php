<nav class="card border-0 mb-4" aria-label="Navigation des modules">
  <div class="card-body py-3 px-4">
    <div class="d-flex flex-wrap align-items-center gap-3">

      <!-- Bouton Accueil -->
      <a href="{{ route('formation.index') }}" 
         class="btn btn-sm d-flex align-items-center gap-2 rounded-pill px-3" style="background-color: #f0b01bff;color:white">
        <i class="bi bi-house-door-fill fs-5"></i>
        <span class="fw-semibold">Accueil</span>
      </a>

      <!-- Séparateur -->
      <div class="vr mx-2 d-none d-sm-block" style="height: 28px;"></div>
@php
// Idéalement, déplacez ceci dans un Controller ou un service
$modules = [
    ['slug' => 'tableau-de-bord',       'titre' => 'Tableau de bord',       'icon' => 'bi-grid-1x2-fill'],
    ['slug' => 'utilisateurs',          'titre' => 'Utilisateurs',          'icon' => 'bi-people-fill'],
    ['slug' => 'materiel-informatique', 'titre' => 'Matériel informatique', 'icon' => 'bi-pc-display-horizontal'],
    ['slug' => 'parc-auto',             'titre' => 'Parc automobile',       'icon' => 'bi-car-front-fill'],
    ['slug' => 'mobilier',              'titre' => 'Mobilier & bureau',     'icon' => 'bi-journal-album'],
    ['slug' => 'logiciels',             'titre' => 'Logiciels',             'icon' => 'bi-box-seam-fill'],
    ['slug' => 'inventaire',            'titre' => 'Inventaire',            'icon' => 'bi-journal-text'],
    ['slug' => 'demandes',              'titre' => 'Demandes',              'icon' => 'bi-envelope-fill'],
    ['slug' => 'activites',             'titre' => 'Activités',             'icon' => 'bi-clock-history'],
];
$current = $currentSlug ?? '';
@endphp

      <!-- Modules -->
      @foreach($modules as $m)
        @php $isActive = ($current === $m['slug']); @endphp
        <a href="{{ route('formation.module', $m['slug']) }}"
           class="btn btn-sm d-flex align-items-center gap-2 rounded-pill px-3 
                  {{ $isActive ? 'btn-success shadow-sm fw-bold' : 'btn-outline-secondary' }}">
          <i class="bi {{ $m['icon'] }} fs-6"></i>
          <span>{{ $m['titre'] }}</span>
        </a>
      @endforeach

    </div>
  </div>
</nav>
