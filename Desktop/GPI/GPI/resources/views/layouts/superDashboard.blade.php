<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Patrimoine Informatique</title>
    <link rel="shortcut icon" href="/logo-gpi.png" type="image/png">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Semantic UI CSS -->
    <link rel="stylesheet" href="css/userDashboard.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/semantic.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.5.0/dist/semantic.min.css">
    <script src="https://cdn.jsdelivr.net/npm/semantic-ui@2.5.0/dist/semantic.min.js"></script>
</head>
@include('layouts.partials.superadmin-styles')
<body>
  @include('layouts.message')
 <header class="bg-dark text-white sticky-top">
  <!-- Top bar with flag colors -->
  <div class="d-flex" style="height:4px;">
    <div class="flex-fill" style="background:#f77f00;"></div>
    <div class="flex-fill bg-white"></div>
    <div class="flex-fill" style="background:#008000;"></div>
  </div>

  <div class="container">
    <div class="d-flex align-items-center justify-content-between py-3">
      <!-- Logo and Title -->
      <div class="d-flex align-items-center gap-3 flex-grow-1 flex-md-grow-0">
        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center overflow-hidden" style="width:45px; height:45px;">
          <span class="fw-bold"style=" overflow: hidden;" ><img src="/mepd1.jpg" height="40" width="auto" alt="Logo GPI" class="me-2"></span>
        </div>
        <div class="d-none d-sm-block">
          <h1 class="h6 fw-bold mb-0">MINISTÈRE </h1>
          <p class="small mb-0 opacity-75">du Plan et du Développement</p>
        </div>
      </div>

      <!-- Actions -->
      <div class="d-flex align-items-center gap-2">
        <!-- Notifications -->
        <button class="btn btn-outline-light position-relative">
          <i class="fas fa-bell"></i>
          <span class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle"></span>
        </button>

        <!-- Dropdown user -->
        <div class="dropdown">
          <button class="btn d-flex align-items-center gap-2 dropdown-toggle outline-none" data-bs-toggle="dropdown">
            <div class="rounded-circle bg-success d-flex align-items-center justify-content-center text-white" style="width:32px; height:32px;">
              <i class="fas fa-user"></i>
            </div>
            <span class="d-none d-md-inline text-white">{{ Auth::user()->nom }}</span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li>
                <form action="/userLog_out" method="POST" class="ms-3">
                    @csrf
                    <button type="submit" class="btn text-danger"><i class="fas fa-sign-out-alt"></i> Déconnexion</button>
                </form>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</header>

<nav class="sa-nav sticky-top shadow-sm" style="top:68px; z-index:40;">
  <div class="container">
    <ul class="navbar-nav d-flex flex-row justify-content-center gap-2 py-2 flex-wrap">
      @php
        $path = request()->path();
        $isDashboard  = $path === 'superadmin/dashboard';
        $isDirections = ! $isDashboard && (
            str_contains($path, 'list-direction')
            || $path === 'directions/create'
            || preg_match('#^directions/\d+/edit$#', $path)
            || str_contains($path, 'assign-admin')
        );
        $isUsers   = ! $isDashboard && ! $isDirections && str_contains($path, 'admin');
        $isRapport = str_contains($path, 'rapport');

        $navClass = fn (bool $active) => 'sa-nav-link' . ($active ? ' active' : '');
      @endphp
      <li class="nav-item">
        <a class="{{ $navClass($isDashboard) }}" href="/superadmin/dashboard">
          <i class="fas fa-home"></i><span>Tableau de bord</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="{{ $navClass($isDirections) }}" href="/directions/list-direction">
          <i class="fas fa-building"></i><span>Directions</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="{{ $navClass($isUsers) }}" href="/directions/list-admin">
          <i class="fas fa-users"></i><span>Utilisateurs</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="{{ $navClass($isRapport) }}" href="/superadmin/rapport">
          <i class="fas fa-chart-bar"></i><span>Rapports</span>
        </a>
      </li>
    </ul>
  </div>
</nav>



    <!-- Main Content -->
    <div class="main-content px-5" id="main-content">
        @yield('content')
    </div>

      <!-- Footer -->
  <footer class="border-top bg-white mt-auto">
    <div class="container py-3 d-flex  flex-md-row justify-content-center align-items-center text-muted small">
      <p class="mb-0">© 2025 Ministère du Plan et du Développement - Côte d'Ivoire</p>
    </div>
  </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>