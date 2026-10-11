<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DSID - Gestion du Patrimoine Informatique</title>
    <link rel="shortcut icon" href="/r.jfif">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.5.0/dist/semantic.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --orange-ci: #F77F00;
            --white-ci: #FFFFFF;
            --green-ci: #009A44;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        
        /* Ajustement HERO Section pour mobile */
        #accueil {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url("{{ asset('css/bg.jpg') }}");
            background-size: cover;
            background-position: center;
            background-attachment: scroll; /* Scroll est plus performant sur mobile que fixed */
            min-height: 80vh;
            display: flex;
            align-items: center;
            padding-top: 80px;
        }
        
        @media (min-width: 992px) {
            #accueil { background-attachment: fixed; min-height: 90vh; }
        }

        .country-banner {
            position: fixed;
            top: 0; left: 0; right: 0;
            display: flex; height: 6px; z-index: 1100;
        }
        
        .banner-orange { background-color: var(--orange-ci); flex: 1; }
        .banner-white { background-color: var(--white-ci); flex: 1; }
        .banner-green { background-color: var(--green-ci); flex: 1; }
        
        .navbar {
            background-color: rgba(255, 255, 255, 0.98);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .hero-title {
            font-size: clamp(1.8rem, 5vw, 3.5rem); /* Taille fluide selon l'écran */
            font-weight: 700;
            color: white;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5);
        }
        
        .hero-subtitle {
            font-size: clamp(1rem, 3vw, 1.5rem);
            color: #f0f0f0;
            margin-bottom: 2rem;
        }

        .feature-section { padding: 4rem 1rem; background-color: white; }
        
        .feature-card {
            height: 100%;
            transition: transform 0.3s ease;
            border-top: 4px solid transparent !important;
            margin-bottom: 15px;
        }
        
        .feature-card:hover { transform: translateY(-10px); }
        .card-orange:hover { border-top-color: var(--orange-ci) !important; }
        .card-green:hover { border-top-color: var(--green-ci) !important; }
        
        .stat-value {
            font-size: clamp(1.5rem, 4vw, 2.8rem);
            font-weight: bold;
            color: var(--orange-ci);
        }

        .section-title {
            font-size: clamp(1.5rem, 4vw, 2.2rem);
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
        }

        .section-title:after {
            content: '';
            position: absolute; bottom: -10px; left: 50%;
            transform: translateX(-50%);
            width: 60px; height: 4px;
            background: linear-gradient(to right, var(--orange-ci), var(--green-ci));
        }

        .dashboard-preview {
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-radius: 12px;
            max-width: 100%;
            height: auto;
        }

        .dashb {
            max-height: 400px;
            overflow-y: auto;
            border-radius: 12px;
        }

        footer { background-color: #1a1a1a; color: white; }
        .hover-orange:hover { color: var(--orange-ci) !important; }
        
        /* Correction Mobile pour Semantic UI */
        .ui.stackable.grid {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        .watermark {
            position: fixed; top: 50%; left: 50%;
            transform: translate(-50%, -50%) rotate(-35deg);
            width: 100%; pointer-events: none;
            opacity: 0.05; font-size: 4vw;
            color: #000; z-index: 9999; text-align: center;
        }
    </style>
</head>
<body>
    <div class="watermark">Lela gnawa dominique</div>
    
    <div class="country-banner">
        <div class="banner-orange"></div>
        <div class="banner-white"></div>
        <div class="banner-green"></div>
    </div>
    
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('MEPD.jpg') }}" alt="Logo" height="40">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto text-center">
                    <li class="nav-item"><a class="nav-link text-dark hover-orange" href="#accueil">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link text-dark hover-orange" href="#fonctionnalites">Fonctionnalités</a></li>
                    <li class="nav-item"><a class="nav-link text-dark hover-orange" href="#apercu">Aperçu</a></li>
                    <li class="nav-item"><a class="nav-link text-dark hover-orange" href="#contact">Contact</a></li>
                    <li class="nav-item mt-2 mt-lg-0 ms-lg-3">
                        <a href="/login" class="btn text-white w-100" style="background-color: var(--orange-ci)">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Connexion
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <section id="accueil">
        <div class="container text-center">
            <div class="hero-content mx-auto">
                <h1 class="hero-title">Gestion du Patrimoine Informatique</h1>
                <p class="hero-subtitle">Simplifiez la gestion de vos équipements avec la solution DSID</p>
                <a href="/login" class="btn btn-lg text-white px-5 py-3" style="background-color: var(--orange-ci);">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Se connecter
                </a>
            </div>
        </div>
    </section>
    
    <section class="feature-section" id="fonctionnalites">
        <div class="container">
            <h2 class="section-title">Fonctionnalités clés</h2>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="ui card feature-card card-orange w-100">
                        <div class="content text-center p-4">
                            <i class="desktop icon fs-1 icon-orange mb-3"></i>
                            <div class="header h4 mb-3">Inventaire Complet</div>
                            <p class="description">Suivi détaillé et centralisé de tout le parc informatique et télécom.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="ui card feature-card card-green w-100">
                        <div class="content text-center p-4">
                            <i class="chart bar icon fs-1 icon-green mb-3"></i>
                            <div class="header h4 mb-3">Tableaux de Bord</div>
                            <p class="description">Visualisation interactive de l'état global du parc en temps réel.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="ui card feature-card card-orange w-100">
                        <div class="content text-center p-4">
                            <i class="history icon fs-1 icon-orange mb-3"></i>
                            <div class="header h4 mb-3">Suivi Maintenance</div>
                            <p class="description">Optimisation de la durée de vie via le suivi préventif et curatif.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <div class="container py-5 bg-white shadow-sm rounded-4 my-5">
        <div class="row g-4 text-center">
            <div class="col-6 col-md-3">
                <div class="stat-value">{{ $equipement->count() }}+</div>
                <div class="text-muted small fw-bold">ÉQUIPEMENTS</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-value">98%</div>
                <div class="text-muted small fw-bold">DISPONIBILITÉ</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-value">{{ $user->count() }}+</div>
                <div class="text-muted small fw-bold">UTILISATEURS</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-value">35%</div>
                <div class="text-muted small fw-bold">ÉCONOMIE</div>
            </div>
        </div>
    </div>

    <section class="overview-section py-5" id="apercu">
        <div class="container">
            <h2 class="section-title">Aperçu de l'Application</h2>
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <h3 class="display-6 fw-bold mb-4">Interface intuitive</h3>
                    <p class="lead text-muted">Une solution moderne pensée pour l'efficacité administrative :</p>
                    <ul class="list-unstyled">
                        <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> État des équipements en temps réel</li>
                        <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> Gestion simplifiée des attributions</li>
                        <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> Planning de maintenance automatique</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="dashb shadow-lg">
                        <img src="/dashboard.jpeg" alt="Dashboard" class="dashboard-preview">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-5" id="contact">
        <div class="container">
            <div class="row g-5">
                <div class="col-md-6 col-lg-4">
                    <h5 class="text-orange-ci mb-4" style="color:var(--orange-ci)">À propos</h5>
                    <p class="text-secondary">La DSID du Ministère du Plan et du Développement (MEPD) assure la stratégie numérique et la gestion des infrastructures.</p>
                    <div class="mt-3">
                        <span class="badge bg-light text-dark p-2">République de Côte d'Ivoire</span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-2">
                    <h5 class="text-white mb-4">Liens</h5>
                    <ul class="list-unstyled">
                        <li><a href="#accueil" class="text-secondary text-decoration-none hover-orange">Accueil</a></li>
                        <li><a href="#fonctionnalites" class="text-secondary text-decoration-none hover-orange">Fonctionnalités</a></li>
                        <li><a href="#contact" class="text-secondary text-decoration-none hover-orange">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-6 col-lg-3">
                    <h5 class="text-white mb-4">Contact</h5>
                    <p class="text-secondary mb-1"><i class="bi bi-geo-alt me-2"></i> Immeuble SCIAM, Abidjan</p>
                    <p class="text-secondary mb-1"><i class="bi bi-telephone me-2"></i> (+225) 27 20 20 08 42</p>
                    <p class="text-secondary"><i class="bi bi-envelope me-2"></i> info.dsid@plan.gouv.ci</p>
                </div>
                <div class="col-md-6 col-lg-3">
                    <h5 class="text-white mb-4">Suivez-nous</h5>
                    <div class="d-flex gap-3 fs-4">
                        <a href="#" class="text-secondary hover-orange"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-secondary hover-orange"><i class="bi bi-linkedin"></i></a>
                        <a href="#" class="text-secondary hover-orange"><i class="bi bi-twitter-x"></i></a>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5 pt-4 border-top border-secondary text-secondary small">
                &copy; 2025 MEPD - Ministère du Plan et du Développement | Tous droits réservés.
            </div>
        </div>
    </footer>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(window).scroll(function() {
            if ($(window).scrollTop() > 50) {
                $('.navbar').addClass('py-2 shadow');
            } else {
                $('.navbar').removeClass('py-2 shadow');
            }
        });

        // Smooth scroll
        $('a[href*="#"]').on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $($(this).attr('href')).offset().top - 80
            }, 600);
            // Ferme le menu mobile après clic
            $('.navbar-collapse').collapse('hide');
        });
    </script>
</body>
</html>