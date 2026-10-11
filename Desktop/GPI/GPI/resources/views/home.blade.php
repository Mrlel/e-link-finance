<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>SIGEP – Portail Administratif</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700;1,600;1,700&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --orange:       #E8601A;
            --orange-light: #F5874A;
            --orange-pale:  #FFF2EA;
            --teal:         #0F4A43;
            --teal-mid:     #1A6158;
            --teal-light:   #E6F0EE;
            --cream:        #FAF6F1;
            --warm-white:   #FFFDF9;
            --text-dark:    #111827;
            --text-muted:   #6B7280;
            --border:       #E5DDD3;
            --shadow-sm:    0 2px 8px rgba(15,74,67,.06);
            --shadow-md:    0 8px 32px rgba(15,74,67,.10);
            --shadow-lg:    0 24px 64px rgba(15,74,67,.13);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Outfit', sans-serif;
            background: var(--warm-white);
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* ── NOISE TEXTURE OVERLAY ── */
        body::before {
            content: '';
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
            opacity: .4;
        }
        
        /* ── HERO ── */
        .hero {
            position: relative; z-index: 1;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
            background: var(--warm-white);
        }

        /* Gradient mesh background */
        .hero-bg {
            position: absolute; inset: 0; z-index: 0;
            background:
                radial-gradient(ellipse 70% 60% at 15% 20%, rgba(232,96,26,.09) 0%, transparent 65%),
                radial-gradient(ellipse 60% 50% at 85% 75%, rgba(15,74,67,.10) 0%, transparent 65%),
                radial-gradient(ellipse 50% 40% at 50% 50%, rgba(15,74,67,.04) 0%, transparent 70%),
                var(--warm-white);
        }

        /* Decorative ring shapes */
        .hero-ring {
            position: absolute; border-radius: 50%;
            border: 1px solid rgba(15,74,67,.06);
            animation: slowRotate 40s linear infinite;
        }
        .hero-ring:nth-child(1) { width: 600px; height: 600px; top: -200px; left: -200px; }
        .hero-ring:nth-child(2) { width: 400px; height: 400px; bottom: -150px; right: -100px; border-color: rgba(232,96,26,.06); animation-direction: reverse; animation-duration: 55s; }
        .hero-ring:nth-child(3) { width: 250px; height: 250px; top: 10%; right: 8%; border-color: rgba(232,96,26,.05); animation-duration: 30s; }

        @keyframes slowRotate { to { transform: rotate(360deg); } }

        /* Grid dots */
        .hero-dots {
            position: absolute; inset: 0;
            background-image: radial-gradient(circle, rgba(15,74,67,.07) 1px, transparent 1px);
            background-size: 36px 36px;
            mask-image: radial-gradient(ellipse 80% 60% at 50% 50%, black 30%, transparent 80%);
        }

        .hero-inner {
            position: relative; z-index: 2;
            width: 100%;
        }

        /* COMPOSITION GÉOMÉTRIQUE POUR IMAGES */
        .hero-geometry-container {
            position: relative;
            width: 100%;
            height: 480px;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeUp .9s ease .4s both;
        }

        .geo-shape {
            position: absolute;
            overflow: hidden;
            background: var(--teal-light);
            box-shadow: var(--shadow-md);
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            border: 2px solid var(--warm-white);
        }

        .geo-shape img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: grayscale(15%);
            transition: transform 0.5s ease, filter 0.5s ease;
        }

        .geo-shape:hover {
            transform: scale(1.03) translateY(-5px);
            z-index: 10 !important;
            box-shadow: var(--shadow-lg);
        }
        
        .geo-shape:hover img {
            filter: grayscale(0%);
            transform: scale(1.05);
        }

        /* Forme 1: Le grand rectangle adouci asymétrique */
        .shape-main {
            width: 60%;
            height: 75%;
            top: 5%;
            left: 5%;
            border-radius: 40px 100px 40px 40px;
            z-index: 2;
        }

        /* Forme 2: Le cercle parfait en superposition */
        .shape-circle {
            width: 42%;
            aspect-ratio: 1 / 1;
            bottom: 2%;
            right: 5%;
            border-radius: 50%;
            z-index: 3;
            border: 4px solid var(--warm-white);
        }

        /* Forme 3: Le polygone oblique en arrière-plan */
        .shape-polygon {
            width: 45%;
            height: 50%;
            top: 0;
            right: 1%;
            clip-path: polygon(25% 0%, 100% 0%, 75% 100%, 0% 100%);
            border-radius: 12px;
            z-index: 1;
            opacity: 0.85;
        }

        /* Elements de décoration géométriques abstraits sans image */
        .geo-dot-deco {
            position: absolute;
            width: 70px;
            height: 70px;
            background-image: radial-gradient(var(--orange) 2px, transparent 2px);
            background-size: 10px 10px;
            bottom: 10%;
            left: 0;
            z-index: 1;
        }

        /* Ministry badge */
        .hero-badge {
            display: inline-flex; align-items: center; gap: 10px;
            background: rgba(255,255,255,.95);
            border: 1px solid var(--border);
            border-radius: 50px;
            padding: 9px 20px;
            box-shadow: var(--shadow-sm), 0 0 0 4px rgba(15,74,67,.04);
            margin-bottom: 32px;
            font-size: 0.78rem; font-weight: 600; letter-spacing: .04em; text-transform: uppercase;
            color: var(--teal); animation: fadeUp .7s ease both;
        }
        .badge-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: var(--orange);
            box-shadow: 0 0 0 3px rgba(232,96,26,.2);
            animation: pulse 2s ease infinite;
        }
        @keyframes pulse { 0%,100% { box-shadow: 0 0 0 3px rgba(232,96,26,.2); } 50% { box-shadow: 0 0 0 6px rgba(232,96,26,.08); } }

        .hero-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(1.4rem, 4.5vw, 3rem);
            font-weight: 700;
            line-height: 1.15;
            color: var(--text-dark);
            margin-bottom: 24px;
            animation: fadeUp .8s ease .1s both;
            text-align: justify;
        }
        .hero-title em {
            color: var(--orange); font-style: italic;
            position: relative; display: inline-block;
        }
        .hero-title em::after {
            content: '';
            position: absolute; left: 0; bottom: 2px; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--orange), transparent);
            border-radius: 2px; transform-origin: left;
            animation: expandLine .8s ease .7s both;
            transform: scaleX(0);
        }
        @keyframes expandLine { to { transform: scaleX(1); } }

        .hero-text {
            font-size: 1rem; font-weight: 300; line-height: 1.9;
            color: var(--text-muted); max-width: 580px; margin: 0 auto 40px;
            animation: fadeUp .8s ease .2s both;
        }
        
        @media (min-width: 992px) {
            .hero-text { margin: 0 0 40px; }
        }

        /* CTA Button */
        .btn-hero {
            display: inline-flex; align-items: center; gap: 10px;
            background: linear-gradient(135deg, var(--teal) 0%, var(--teal-mid) 100%);
            color: white; font-size: 0.9rem; font-weight: 600; letter-spacing: .02em;
            padding: 15px 34px; border-radius: 14px; border: none;
            text-decoration: none;
            box-shadow: 0 8px 24px rgba(15,74,67,.28), 0 2px 8px rgba(15,74,67,.15);
            transition: transform .25s ease, box-shadow .25s ease;
            animation: fadeUp .8s ease .3s both;
            position: relative; overflow: hidden;
        }
        .btn-hero::before {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,.1) 0%, transparent 60%);
            border-radius: inherit;
        }
        .btn-hero:hover { transform: translateY(-3px); box-shadow: 0 14px 36px rgba(15,74,67,.32), 0 4px 12px rgba(15,74,67,.18); color: white; }

        /* ── ANIMATIONS ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(22px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 991px) {
            .hero { padding: 100px 0 60px; text-align: center; }
            .hero-geometry-container { margin-top: 40px; height: 380px; }
        }
    </style>
</head>
<body>

<section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-ring"></div>
    <div class="hero-ring"></div>
    <div class="hero-ring"></div>
    <div class="hero-dots"></div>

    <div class="container">
        <div class="hero-inner">
            <div class="row align-items-center g-5">
                
                <div class="col-lg-6 text-center text-lg-start">
                    <!-- <div class="hero-badge">
                        <div class="badge-dot"></div>
                        Ministère du Plan et du Développement
                    </div>-->

                    <h1 class="hero-title">
                        Système Integré de Gestion Electronique du <em>Patrimoine</em><br>du Ministère du Plan et du Développement
                    </h1>

                    <p class="hero-text">
                        Une plateforme moderne pour la gestion centralisée des réssources informatiques, du mobiliers, materiel & fourniture de bureau et de reporting des mouvements.
                    </p>

                    <a href="/login" class="btn-hero">
                        <i class="bi bi-box-arrow-in-right fs-5"></i> Se connecter
                    </a>
                </div>

                <div class="col-lg-6">
                    <div class="hero-geometry-container">
                        <div class="geo-dot-deco"></div>
                        
                        <div class="geo-shape shape-polygon">
                            <img src="/auto.jpg" alt="parc auto">
                        </div>
                        
                        <div class="geo-shape shape-main">
                            <img src="/bg.jpg" alt="Administration Moderne">
                        </div>
                        
                        <div class="geo-shape shape-circle">
                            <img src="/bureau.jpg" alt="mobilier">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

</body>
</html>