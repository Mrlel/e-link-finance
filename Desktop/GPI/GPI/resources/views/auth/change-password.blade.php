<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>SIGEP – Initialisation du mot de passe</title>
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
        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: row;
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
            max-width: 430px;
            width: 100%;
            margin: 60px auto;
            animation: fadeUp .7s ease both;
        }

        .login-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(232, 96, 26, 0.07);
            border-radius: 50px;
            padding: 6px 16px;
            margin-bottom: 24px;
            font-size: 0.75rem; font-weight: 600; letter-spacing: .04em; text-transform: uppercase;
            color: var(--orange);
        }
        .badge-secure-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: var(--orange);
        }

        .login-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.4rem;
            font-weight: 700;
            line-height: 1.2;
            color: var(--text-dark);
            margin-bottom: 12px;
        }
        .login-title em {
            color: var(--teal);
            font-style: italic;
        }

        .login-subtitle {
            font-size: 0.95rem;
            color: var(--text-muted);
            margin-bottom: 32px;
            font-weight: 300;
            line-height: 1.6;
        }

        /* Form Custom Elements */
        .form-label {
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 4px;
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

        /* Modification pour gérer l'état d'erreur natif Laravel */
        .form-control-custom.is-invalid {
            border-color: #dc3545;
            background-color: rgba(220, 53, 69, 0.02);
        }
        .form-control-custom.is-invalid:focus {
            box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.1);
        }

        /* Consignes de sécurité discrètes */
        .password-hint {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 6px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-submit {
            width: 100%;
            display: inline-flex; align-items: center; justify-content: center; gap: 10px;
            background: linear-gradient(135deg, var(--teal) 0%, var(--teal-mid) 100%);
            color: white; font-size: 0.95rem; font-weight: 600;
            padding: 14px; border-radius: 12px; border: none;
            box-shadow: 0 8px 24px rgba(15,74,67,.2);
            transition: transform .25s ease, box-shadow .25s ease;
            margin-top: 12px;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(15,74,67,.28);
        }

        /* Notifications Alertes Stylisées */
        .alert-custom {
            padding: 14px 16px;
            border-radius: 12px;
            font-size: 0.9rem;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid transparent;
        }
        .alert-custom-danger {
            background-color: #fdf2f2;
            border-color: #fae7e7;
            color: #9b1c1c;
        }
        .alert-custom-success {
            background-color: #f3faf7;
            border-color: #def7ec;
            color: #03543f;
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

        /* ── RESPONSIVITÉ PERFECT ── */
        @media (max-width: 991px) {
            .login-container { flex-direction: column; }
            .login-side-visual { display: none; }
            .login-side-form { padding: 32px 24px; min-height: 100vh; justify-content: center; }
            .brand-header { position: absolute; top: 32px; left: 24px; }
            .form-wrapper { margin: 80px auto 40px; }
            .footer-text { margin-top: 20px; }
        }
    </style>
</head>
<body>

<main class="login-container">
    <section class="login-side-form">
        <div class="form-wrapper">
            <div class="login-badge">
                <div class="badge-secure-dot"></div>
                Première Connexion
            </div>
            <p class="login-subtitle">Pour des raisons de sécurité, vous devez définir un nouveau mot de passe avant d'accéder au système.</p>

            @if (session('error'))
                <div class="alert-custom alert-custom-danger">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            @if (session('status'))
                <div class="alert-custom alert-custom-success">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                    <div>{{ session('status') }}</div>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <div class="mb-2">
                    <label for="password" class="form-label">Nouveau mot de passe</label>
                    <div class="input-group-custom">
                        <input type="password" 
                               class="form-control-custom @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               required 
                               placeholder="••••••••••••">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                    @enderror
                    <div class="password-hint">
                        <i class="bi bi-info-circle"></i> Minimum 8 caractères recommandés.
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                    <div class="input-group-custom">
                        <input type="password" 
                               class="form-control-custom" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               required 
                               placeholder="••••••••••••">
                        <i class="bi bi-shield-check"></i>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="bi bi-arrow-repeat"></i> Mettre à jour et continuer
                </button>
            </form>
        </div>
    </section>

</main>

</body>
</html>