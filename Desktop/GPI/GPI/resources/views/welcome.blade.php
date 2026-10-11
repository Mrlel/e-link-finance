<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>SIGEP – Connexion au Portail</title>
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
        body {
            font-family: 'Outfit', sans-serif;
            background: var(--warm-white);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        /* ── NOISE TEXTURE OVERLAY ── */
        body::before {
            content: '';
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
            opacity: .4;
        }

        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: row;
        }

        /* ── SECTION VISUELLE ÉRICHE (À GAUCHE) ── */
        .login-side-visual {
             background: linear-gradient(rgba(0, 0, 0, 0.32), rgba(0, 0, 0, 0.29)), url('bg.jpg');
            background-size: cover;
            background-position: center;
            flex: 1.2;
            position: relative;
            background-color: #1A6158 !important;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-right: 1px solid var(--border);
        }

     
        /* Forme 1: Polygone / Losange oblique en arrière-plan */
        .v-shape-back-poly {
            width: 40%; height: 45%;
            top: 2%; left: 15%;
            clip-path: polygon(15% 0%, 100% 0%, 85% 100%, 0% 100%);
            z-index: 1;
            opacity: 0.7;
        }

        /* Forme 2: Le grand rectangle principal asymétrique */
        .v-shape-main {
            width: 58%; height: 62%;
            top: 15%; left: 25%;
            border-radius: 30px 80px 30px 30px;
            z-index: 2;
        }

        /* Forme 3: Le cercle parfait en superposition */
        .v-shape-circle {
            width: 38%; aspect-ratio: 1/1;
            bottom: 5%; right: 5%;
            border-radius: 50%;
            z-index: 4;
            border: 4px solid var(--warm-white);
        }

        /* Forme 4: Nouveau petit carré adouci en débordement bas-gauche */
        .v-shape-sub-square {
            width: 28%; aspect-ratio: 1/1;
            bottom: 12%; left: 10%;
            border-radius: 20px;
            z-index: 3;
            border: 3px solid var(--warm-white);
        }

        /* Points déco abstraits */
        .v-deco-dots-1 {
            position: absolute;
            width: 60px; height: 60px;
            background-image: radial-gradient(var(--orange) 2px, transparent 2px);
            background-size: 10px 10px;
            top: 8%; right: 18%;
            z-index: 1;
        }
        .v-deco-dots-2 {
            position: absolute;
            width: 50px; height: 50px;
            background-image: radial-gradient(var(--teal) 2px, transparent 2px);
            background-size: 10px 10px;
            bottom: 8%; left: 5%;
            z-index: 1;
        }


        /* ── SECTION FORMULAIRE (À DROITE) ── */
        .login-side-form {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 40px;
            background: var(--warm-white);
        }

        .brand-header {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        .brand-logo-panel {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--teal) 0%, var(--teal-mid) 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
        }
        .brand-name {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: 1.2rem;
            color: var(--text-dark);
            letter-spacing: 0.05em;
        }

        .form-wrapper {
            max-width: 420px;
            width: 100%;
            margin: 60px auto;
            animation: fadeUp .7s ease both;
        }

        .login-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(15, 74, 67, 0.05);
            border-radius: 50px;
            padding: 6px 16px;
            margin-bottom: 24px;
            font-size: 0.75rem; font-weight: 600; letter-spacing: .04em; text-transform: uppercase;
            color: var(--teal);
        }
        .badge-secure-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: var(--teal);
        }

        .login-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1.2;
            color: var(--text-dark);
            margin-bottom: 12px;
        }
        .login-title em {
            color: var(--orange);
            font-style: italic;
        }

        .login-subtitle {
            font-size: 0.95rem;
            color: var(--text-muted);
            margin-bottom: 36px;
            font-weight: 300;
        }

        /* Inputs Form */
        .form-label {
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 24px;
        }
        .input-group-custom i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1.1rem;
            transition: color 0.3s;
            z-index: 10;
        }
        .form-control-custom {
            width: 100%;
            padding: 14px 16px 14px 48px;
            background: rgba(245, 241, 235, 0.4);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            font-size: 0.95rem;
            font-family: 'Outfit', sans-serif;
            color: var(--text-dark);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .form-control-custom:focus {
            outline: none;
            background: white;
            border-color: var(--teal);
            box-shadow: 0 0 0 4px rgba(15, 74, 67, 0.06);
        }
        .form-control-custom:focus + i {
            color: var(--teal);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.88rem;
            margin-bottom: 32px;
        }
        .form-check-input:checked {
            background-color: var(--teal);
            border-color: var(--teal);
        }
        .forgot-password {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s;
        }
        .forgot-password:hover {
            color: var(--orange);
        }

        .btn-submit {
            width: 100%;
            display: inline-flex; align-items: center; justify-content: center; gap: 10px;
            background: linear-gradient(135deg, var(--teal) 0%, var(--teal-mid) 100%);
            color: white; font-size: 0.95rem; font-weight: 600;
            padding: 14px; border-radius: 12px; border: none;
            box-shadow: 0 8px 24px rgba(15,74,67,.2);
            transition: transform .25s ease, box-shadow .25s ease;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(15,74,67,.28);
        }

        .footer-text {
            font-size: 0.8rem;
            color: var(--text-muted);
            font-weight: 300;
        }

        /* ── ANIMATIONS ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── RESPONSIVITÉ PARFAITE ── */
        @media (max-width: 991px) {
            .login-container {
                flex-direction: column; /* Force le formulaire à s'empiler proprement */
            }
            .login-side-visual {
                display: none; /* Masque les images complexes sur mobile pour une ergonomie optimale */
            }
            .login-side-form {
                padding: 32px 24px;
                min-height: 100vh;
                justify-content: center; /* Centre verticalement le contenu sur petits écrans */
            }
            .brand-header {
                position: absolute;
                top: 32px;
                left: 24px;
            }
            .form-wrapper {
                margin: 80px auto 40px;
            }
            .footer-text {
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>

<main class="login-container">
    
    <section class="login-side-visual" >
       
    </section>

    <section class="login-side-form">
        <div class="form-wrapper">
            <div class="login-badge">
                <div class="badge-secure-dot"></div>
                Espace Sécurisé Professionnel
            </div>

            <h1 class="login-title">SIGEP-<em>MPD</em></h1>
            <p class="login-subtitle">Connectez-vous à votre portail de gestion du patrimoine.</p>

            <form action="/loginUser/traitement" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Adresse email professionnelle</label>
                    <div class="input-group-custom">
                        <input type="email" name="email" class="form-control-custom" placeholder="nom@ministere.ci" required autocomplete="username">
                        <i class="bi bi-envelope"></i>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mot de passe</label>
                    <div class="input-group-custom">
                        <input type="password" name="password" class="form-control-custom" placeholder="••••••••••••" required autocomplete="current-password">
                        <i class="bi bi-lock"></i>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    Accéder au tableau de bord <i class="bi bi-arrow-right"></i>
                </button>
            </form>
        </div>

        <footer class="footer-text text-center text-lg-start">
            &copy; 2026 Ministère du Plan et du Développement. Tous droits réservés.
        </footer>
    </section>

</main>

</body>
</html>