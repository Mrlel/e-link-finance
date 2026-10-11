<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $code }} — {{ $title }}</title>
    <link rel="icon" href="/logo-gpi.png" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        *, ::before, ::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: #f5f7fa;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #1a1a2e;
        }

        /* Bande tricolore Côte d'Ivoire */
        .flag-bar {
            height: 5px;
            display: flex;
        }
        .flag-bar span:nth-child(1) { flex: 1; background: #FF7900; }
        .flag-bar span:nth-child(2) { flex: 1; background: #FFFFFF; border-top: 1px solid #eee; border-bottom: 1px solid #eee; }
        .flag-bar span:nth-child(3) { flex: 1; background: #009E60; }

        header {
            background: #1a1a2e;
            padding: 14px 32px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        header img { height: 36px; width: auto; }
        header .brand { color: #fff; }
        header .brand h1 { font-size: 14px; font-weight: 700; margin: 0; line-height: 1.2; }
        header .brand p  { font-size: 11px; opacity: .65; margin: 0; }

        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 16px;
        }

        .error-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 40px rgba(0,0,0,.08);
            overflow: hidden;
            max-width: 560px;
            width: 100%;
            text-align: center;
        }

        .error-card-top {
            padding: 48px 40px 32px;
        }

        .error-icon-wrap {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 40px;
        }

        .error-code {
            font-size: clamp(72px, 15vw, 108px);
            font-weight: 900;
            line-height: 1;
            letter-spacing: -4px;
            margin-bottom: 8px;
        }

        .error-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .error-desc {
            font-size: 15px;
            color: #6b7280;
            line-height: 1.65;
            margin-bottom: 0;
        }

        .error-card-actions {
            padding: 24px 40px 40px;
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all .2s;
            border: 2px solid #e5e7eb;
            color: #374151;
            background: #fff;
        }
        .btn-back:hover { border-color: #009E60; color: #009E60; background: #f0fdf6; }

        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all .2s;
            background: #009E60;
            color: #fff;
            border: 2px solid #009E60;
        }
        .btn-home:hover { background: #007a4a; border-color: #007a4a; color: #fff; }

        /* Variantes de couleur par code */
        .variant-404 .error-icon-wrap { background: #fef3c7; color: #d97706; }
        .variant-404 .error-code      { color: #d97706; }

        .variant-403 .error-icon-wrap { background: #fee2e2; color: #dc2626; }
        .variant-403 .error-code      { color: #dc2626; }

        .variant-500 .error-icon-wrap { background: #fce7f3; color: #9d174d; }
        .variant-500 .error-code      { color: #9d174d; }

        .variant-503 .error-icon-wrap { background: #ede9fe; color: #7c3aed; }
        .variant-503 .error-code      { color: #7c3aed; }

        .variant-419 .error-icon-wrap { background: #e0f2fe; color: #0369a1; }
        .variant-419 .error-code      { color: #0369a1; }

        .variant-429 .error-icon-wrap { background: #fff7ed; color: #ea580c; }
        .variant-429 .error-code      { color: #ea580c; }

        footer {
            background: #1a1a2e;
            color: rgba(255,255,255,.45);
            text-align: center;
            font-size: 12px;
            padding: 16px;
        }

        @media (max-width: 480px) {
            .error-card-top { padding: 32px 24px 24px; }
            .error-card-actions { padding: 16px 24px 32px; }
            .btn-back, .btn-home { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>
    <div class="flag-bar"><span></span><span></span><span></span></div>

    <header>
        <img src="/logo-gpi.png" alt="GPI Logo">
        <div class="brand">
            <h1>MINISTÈRE DU PLAN ET DU DÉVELOPPEMENT</h1>
            <p>Gestion du Patrimoine Informatique</p>
        </div>
    </header>

    <main>
        <div class="error-card variant-{{ $code }}">
            <div class="error-card-top">
                <div class="error-icon-wrap">
                    <i class="{{ $icon }}"></i>
                </div>
                <div class="error-code">{{ $code }}</div>
                <div class="error-title">{{ $title }}</div>
                <p class="error-desc">{{ $message }}</p>
            </div>

            <div class="error-card-actions">
                <a href="javascript:history.back()" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
                <a href="/" class="btn-home">
                    <i class="fas fa-home"></i> Accueil
                </a>
            </div>
        </div>
    </main>

    <footer>
        &copy; {{ date('Y') }} Ministère du Plan et du Développement — Côte d'Ivoire
    </footer>
</body>
</html>
