<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message de Statut</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    



        
    @if(session('success') || session('error') || session('warning') || session('message')|| session('status') || $errors->any())
    <div id="status-popup" class="message-popup-container">
        @if(session('success'))
            <div class="message-popup success">
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="text">
                    <strong>Succès !</strong>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="closePopup()" class="close-btn">&times;</button>
            </div>
        @endif

        @if(session('message'))
            <div class="message-popup success">
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="text">
                    <strong>Succès !</strong>
                    <span>{{ session('message') }}</span>
                </div>
                <button onclick="closePopup()" class="close-btn">&times;</button>
            </div>
        @endif

         @if(session('status'))
            <div class="message-popup success">
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="text">
                    <strong>Succès !</strong>
                    <span>{{ session('status') }}</span>
                </div>
                <button onclick="closePopup()" class="close-btn">&times;</button>
            </div>
        @endif

        @if(session('warning'))
            <div class="message-popup warning">
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="text">
                    <strong>Attention !</strong>
                    <span>{{ session('warning') }}</span>
                </div>
                <button onclick="closePopup()" class="close-btn">&times;</button>
            </div>
        @endif

        @if(session('error') || $errors->any())
            <div class="message-popup error">
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="text">
                    <strong>Erreur !</strong>
                    <span>{{ session('error') ?? 'Veuillez vérifier le formulaire.' }}</span>
                </div>
                <button onclick="closePopup()" class="close-btn">&times;</button>
            </div>
        @endif
    </div>

    <style>
        .message-popup-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            animation: slideIn 0.5s ease-out;
        }

        .message-popup {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            margin-bottom: 10px;
            min-width: 300px;
            color: white;
            position: relative;
        }

        .message-popup.success { background-color: #080808ff; } /* Votre vert SNAM */
        .message-popup.error { background-color: #e74c3c; }
        .message-popup.warning { background-color: #f39c12; }

        .message-popup .icon { font-size: 1.5rem; margin-right: 15px; }
        .message-popup .text { display: flex; flex-direction: column; }
        .message-popup .text strong { font-size: 1rem; }
        .message-popup .text span { font-size: 0.9rem; opacity: 0.9; }

        .close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            margin-left: auto;
            cursor: pointer;
            padding-left: 15px;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .fade-out {
            animation: slideOut 0.5s ease-in forwards;
        }

        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    </style>

    <script>
        function closePopup() {
            const popup = document.getElementById('status-popup');
            popup.classList.add('fade-out');
            setTimeout(() => popup.remove(), 500);
        }

        // Auto-fermeture après 5 secondes
        setTimeout(closePopup, 5000);
    </script>
@endif
</body>
</html>