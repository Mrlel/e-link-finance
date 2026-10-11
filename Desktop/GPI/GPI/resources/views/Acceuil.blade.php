<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GPI - Gestion Du Parc Informatique</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #2d7d32;
            --secondary-color: #4caf50;
            --accent-color: #81c784;
            --text-dark: #2c3e50;
            --text-light: #34495e;
            --white: #ffffff;
            --gray-light: #f8f9fa;
            --gray-medium: #e9ecef;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --shadow-hover: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

    html, body {
        overflow: hidden;
        height: 100%;
    }

    /* Conservez votre style body existant en ajoutant simplement la propriété overflow */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        color: var(--text-dark);
   
        min-height: 100vh;
        overflow-x: hidden;
        overflow: hidden; /* Ajouté ici */
    }

        /* Animation d'arrière-plan */
        .background-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .floating-shape {
            position: absolute;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }

        .floating-shape:nth-child(1) {
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-shape:nth-child(2) {
            top: 20%;
            right: 20%;
            animation-delay: 2s;
        }

        .floating-shape:nth-child(3) {
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }

        .floating-shape:nth-child(4) {
            bottom: 10%;
            right: 10%;
            animation-delay: 1s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        /* Container principal */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header */
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 0;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary-color);
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        /* Section Hero */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding-top: 80px;
            position: relative;
        }

        .hero-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        .hero-text {
            z-index: 2;
        }

        .hero-text h1 {
            font-size: 3rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .hero-text .highlight {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-text p {
            font-size: 1.1rem;
            color: var(--text-light);
            margin-bottom: 2rem;
            line-height: 1.8;
        }

        .cta-button {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 12px 40px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }

        .cta-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .cta-button:hover::before {
            left: 100%;
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-hover);
        }

        /* Illustration Hero */
        .hero-illustration {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .computer-setup {
            position: relative;
            transform: perspective(1000px) rotateY(-15deg);
            animation: gentle-float 4s ease-in-out infinite;
        }

        @keyframes gentle-float {
            0%, 100% { transform: perspective(1000px) rotateY(-15deg) translateY(0px); }
            50% { transform: perspective(1000px) rotateY(-15deg) translateY(-10px); }
        }

        .monitor {
            width: 300px;
            height: 200px;
            background: linear-gradient(145deg, #2c3e50, #34495e);
            border-radius: 15px;
            padding: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            position: relative;
            margin-bottom: 20px;
        }

        .screen {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .screen::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 40%, rgba(255,255,255,0.1) 50%, transparent 60%);
            animation: screen-shine 3s linear infinite;
        }

        @keyframes screen-shine {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .screen-content {
            color: white;
            text-align: center;
            z-index: 1;
        }

        .screen-content i {
            font-size: 3rem;
            margin-bottom: 10px;
            opacity: 0.9;
        }

        .screen-content p {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .monitor-stand {
            width: 60px;
            height: 30px;
            background: linear-gradient(145deg, #2c3e50, #34495e);
            border-radius: 5px;
            margin: 0 auto 10px;
            position: relative;
        }

        .monitor-base {
            width: 120px;
            height: 15px;
            background: linear-gradient(145deg, #2c3e50, #34495e);
            border-radius: 20px;
            margin: 0 auto;
        }

        .cpu {
            position: absolute;
            right: -80px;
            top: 50px;
            width: 80px;
            height: 120px;
            background: linear-gradient(145deg, #2c3e50, #34495e);
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .cpu::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 10px;
            right: 10px;
            height: 8px;
            background: var(--primary-color);
            border-radius: 4px;
            box-shadow: 0 0 10px var(--primary-color);
        }

        .cpu-lights {
            position: absolute;
            top: 40px;
            left: 15px;
            right: 15px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .cpu-light {
            width: 8px;
            height: 8px;
            background: var(--secondary-color);
            border-radius: 50%;
            animation: blink 2s infinite;
        }

        .cpu-light:nth-child(2) { animation-delay: 0.5s; }
        .cpu-light:nth-child(3) { animation-delay: 1s; }

        @keyframes blink {
            0%, 50% { opacity: 1; box-shadow: 0 0 10px var(--secondary-color); }
            51%, 100% { opacity: 0.3; box-shadow: none; }
        }

        .keyboard {
            position: absolute;
            bottom: -40px;
            left: 50%;
            transform: translateX(-50%);
            width: 180px;
            height: 60px;
            background: linear-gradient(145deg, #2c3e50, #34495e);
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .keyboard::before {
            content: '';
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            background: repeating-linear-gradient(
                90deg,
                transparent,
                transparent 8px,
                rgba(255,255,255,0.1) 8px,
                rgba(255,255,255,0.1) 10px
            );
            border-radius: 4px;
        }

        .mouse {
            position: absolute;
            bottom: -30px;
            right: 20px;
            width: 30px;
            height: 45px;
            background: linear-gradient(145deg, #2c3e50, #34495e);
            border-radius: 15px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
        }

        .mouse::before {
            content: '';
            position: absolute;
            top: 8px;
            left: 50%;
            transform: translateX(-50%);
            width: 2px;
            height: 10px;
            background: var(--accent-color);
            border-radius: 2px;
        }

        /* Particules décoratives */
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: var(--accent-color);
            border-radius: 50%;
            animation: particle-float 8s linear infinite;
        }

        .particle:nth-child(1) { left: 10%; animation-delay: 0s; }
        .particle:nth-child(2) { left: 20%; animation-delay: 2s; }
        .particle:nth-child(3) { left: 30%; animation-delay: 4s; }
        .particle:nth-child(4) { left: 40%; animation-delay: 6s; }
        .particle:nth-child(5) { left: 50%; animation-delay: 1s; }
        .particle:nth-child(6) { left: 60%; animation-delay: 3s; }
        .particle:nth-child(7) { left: 70%; animation-delay: 5s; }
        .particle:nth-child(8) { left: 80%; animation-delay: 7s; }

        @keyframes particle-float {
            0% { transform: translateY(100vh) scale(0); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-100px) scale(1); opacity: 0; }
        }

        /* Section Features */
        .features {
            padding: 100px 0;
            background: var(--white);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            margin-top: 60px;
        }

        .feature-card {
            background: var(--white);
            padding: 40px 30px;
            border-radius: 20px;
            text-align: center;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            border: 1px solid var(--gray-medium);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-hover);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem;
            color: white;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: var(--text-dark);
        }

        .feature-card p {
            color: var(--text-light);
            line-height: 1.6;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 20px;
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.2rem;
            color: var(--text-light);
            max-width: 600px;
            margin: 0 auto;
        }


        /* Responsive */
        @media (max-width: 768px) {
            .hero-content {
                grid-template-columns: 1fr;
                gap: 40px;
                text-align: center;
            }

            .hero-text h1 {
                font-size: 1.5rem;
            }

            .hero-text p {
                font-size: 1rem;
            }

            .computer-setup {
                transform: scale(0.8);
            }

            .features-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .section-title {
                font-size: 2rem;
            }

            .nav {
                flex-direction: column;
                gap: 20px;
            }

            .header {
                padding: 20px 0;
            }

            .hero {
                padding-top: 140px;
            }
        }

        @media (max-width: 480px) {
            .hero-text h1 {
                font-size: 2rem;
            }

            .cta-button {
                padding: 15px 30px;
                font-size: 1rem;
            }

            .computer-setup {
                transform: scale(0.6);
            }
        }
    </style>
</head>
<body>
    <!-- Animation d'arrière-plan -->
    <div class="background-animation">
        <div class="floating-shape">
            <i class="fas fa-microchip" style="font-size: 3rem; color: var(--primary-color);"></i>
        </div>
        <div class="floating-shape">
            <i class="fas fa-server" style="font-size: 2.5rem; color: var(--secondary-color);"></i>
        </div>
        <div class="floating-shape">
            <i class="fas fa-network-wired" style="font-size: 2rem; color: var(--accent-color);"></i>
        </div>
        <div class="floating-shape">
            <i class="fas fa-desktop" style="font-size: 2.5rem; color: var(--primary-color);"></i>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>
                        La Direction Des Systèmes d'Information Et De La 
                        Digitalisation
                    </h1>
                    <p>
                        Gérez efficacement votre parc informatique avec notre solution complète. 
                        Suivez vos équipements, optimisez vos ressources et modernisez votre infrastructure IT.
                    </p>
                    <a href="/login" class="cta-button" data-bs-toggle="modal" data-bs-target="#userModal">
                        <i class="fas fa-sign-in-alt"></i>
                        Connexion
                    </a>
                </div>
                <div class="hero-illustration">
                    <div class="computer-setup">
                        <div class="monitor">
                            <div class="screen">
                                <div class="screen-content">
                                    <i class="fas fa-user-circle"></i>
                                    <p>Gestion du Patrimoine </br> Informatique</p>
                                </div>
                            </div>
                        </div>
                        <div class="monitor-stand"></div>
                        <div class="monitor-base"></div>
                        <div class="cpu">
                            <div class="cpu-lights">
                                <div class="cpu-light"></div>
                                <div class="cpu-light"></div>
                                <div class="cpu-light"></div>
                            </div>
                        </div>
                        <div class="keyboard"></div>
                        <div class="mouse"></div>
                    </div>
                    <div class="particles">
                        <div class="particle"></div>
                        <div class="particle"></div>
                        <div class="particle"></div>
                        <div class="particle"></div>
                        <div class="particle"></div>
                        <div class="particle"></div>
                        <div class="particle"></div>
                        <div class="particle"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- User Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title " id="userModalLabel"></span>Connexion <span uk-icon="lock"></span></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="/loginUser/traitement">
                        @csrf
                        <div class="mb-3">
                            <label for="userMatricule" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" id="userMatricule" required>
                        </div>
                        <div class="mb-3">
                            <label for="userPassword" class="form-label">Mot de passe</label>
                            <input type="password" name="password" class="form-control" id="userPassword" required>
                        </div>
                        <button type="submit" class="btn" style="background-color:  rgba(213, 234, 253, 0.9);">Se connecter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    

    <script>
        // Animation du header au scroll
        window.addEventListener('scroll', () => {
            const header = document.querySelector('.header');
            if (window.scrollY > 100) {
                header.style.background = 'rgba(255, 255, 255, 0.98)';
                header.style.backdropFilter = 'blur(20px)';
            } else {
                header.style.background = 'rgba(255, 255, 255, 0.95)';
                header.style.backdropFilter = 'blur(10px)';
            }
        });

        // Animation d'apparition des éléments
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observer les cartes de fonctionnalités
        document.querySelectorAll('.feature-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });

        // Effet de parallaxe léger
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallaxElements = document.querySelectorAll('.floating-shape');
            const speed = 0.5;

            parallaxElements.forEach(element => {
                const yPos = -(scrolled * speed);
                element.style.transform = `translateY(${yPos}px)`;
            });
        });
    </script>
</body>
</html>