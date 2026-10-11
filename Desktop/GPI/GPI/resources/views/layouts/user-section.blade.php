<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Patrimoine Informatique</title>
    <link rel="shortcut icon" href="/gpi.png" type="image/x-icon">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css">

    <!-- Semantic UI CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/semantic.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.5.0/dist/semantic.min.css">
    <script src="https://cdn.jsdelivr.net/npm/semantic-ui@2.5.0/dist/semantic.min.js"></script>
</head>
<style>
       body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }
        .stat-card {
            background: #fafafaf6;
            border-radius: 7px;
            padding: 30px 20px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 44px;
        }
        
        .icon-purple {
            color: #9333ea;
        }
        
        .icon-green {
            color: #ea580c;
        }
        
        .icon-orange {
            color:  #16a34a;
        }
        
        .icon-blue {
            color: #2563eb;
        }
        
        .stat-number {
            font-size: 42px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 8px;
            line-height: 1;
        }
        
        .stat-label {
            font-size: 15px;
            color: #6b7280;
            font-weight: 400;
        }
            .nav-item :hover {
        color : #f77f00;
        transition : 0.3s ease-in-out;
        
    }
</style>
<body>
 <header class="bg-light sticky-top">
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
        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center overflow-hidden border border-success" style="width:40px; height:40px;">
          <img src="{{ asset('storage/' . Auth::user()->direction->logo) }}" class="img-fluid" alt="Logo" style="max-height: 40px; width: auto; me-4">
        </div>
        <div class="d-none d-sm-block">
          <h1 class="h6 fw-bold mb-0 uppercase">{{ Auth::user()->role}} • {{ Auth::user()->direction->code_direction }}</h1>
          <p class="small mb-0 opacity-75">{{ Auth::user()->direction->nom_direction }}</p>
        </div>
      </div>

      <!-- Search (hidden on mobile) -->
      <div class="d-none d-md-flex flex-grow-1 mx-4" style="max-width:400px;">
        <div class="input-group w-100">
          <span class="input-group-text bg-white border-end-0">
            <i class="fas fa-search text-muted"></i>
          </span>
          <input type="text" class="form-control border-start-0" placeholder="Rechercher...">
        </div>
      </div>

      <!-- Actions -->
      <div class="d-flex align-items-center gap-4">
        <!-- Notifications -->
        <button class="btn btn-outline-dark position-relative">
          <a class="nav-link" href="/userNotifications">
                     <i class="bi bi-bell"></i>
                          @if(auth()->user()->unreadNotifications->count() > 0)
                              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">
                                {{ auth()->user()->unreadNotifications->count() }}
                              </span>
                          @endif
                      </a>
        </button>

        <!-- Dropdown user -->
        <div class="dropdown">
          <button class="btn d-flex align-items-center gap-2 dropdown-toggle outline-none" data-bs-toggle="dropdown">
            <span class="d-none d-md-inline">{{ Auth::user()->nom }} </span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="/profile/edit"><i class="fas fa-user"></i> Mon Profil</a></li>
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
<nav class="bg-success border-bottom sticky-top shadow-sm" style="top:68px; z-index:40;">
  <div class="container">
    <ul class="navbar-nav text-white d-flex flex-row justify-content-center gap-5 py-2">

      <!-- Tableau de bord -->
       <li class="nav-item">
                <a href="/userDashboard" class="nav-link">
                    <i class="bi bi-grid fs-5"></i>
                    <span class="menu-text">Tableau de bord</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/usersEquipement/{{ Auth::user()->id }}" class="nav-link">
                    <i class="bi bi-laptop fs-5"></i>
                    <span class="menu-text">Mes équipements</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="/aide" class="nav-link">
                    <i class="bi bi-question-circle fs-5"></i>
                    <span class="menu-text">Guide d'Utilisation</span>
                </a>
            </li>
    </ul>
  </div>
</nav>
  @include('layouts.message')
    <!-- Main Content -->
    <div class="main-content px-5" id="main-content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Semantic UI components
            $('.ui.dropdown').dropdown();
            
            // Tab switching functionality
            $('.nav-tabs .nav-link').on('click', function(e) {
                e.preventDefault();
                
                // Hide all tab contents
                $('.tab-content').hide();
                
                // Show the selected tab content
                $($(this).attr('href')).show();
                
                // Toggle active class
                $('.nav-tabs .nav-link').removeClass('active');
                $(this).addClass('active');
            });
            
            
        });
    </script>
</body>
</html>