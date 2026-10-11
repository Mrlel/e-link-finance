<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'authentification</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            
        }

        .login-container {
            background-color: white;
            border-radius: 10px;
            padding: 40px;
            width: 380px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #333;
        }

        .subtitle {
            color: #888;
            font-size: 14px;
            margin-bottom: 25px;
        }

        input {
            width: 100%;
            padding: 15px;
            margin-bottom: 15px;
            border: none;
            border-radius: 5px;
            background-color: #f5f5f5;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            background-color: #f0f0f0;
        }

        button {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 5px;
            background-color: #009E60;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color:rgba(0, 158, 95, 0.73);
        }
    </style>
</head>
<body>
    @include('layouts.message')

    <div class="login-container">
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <div class="icon">
           <img src="/logo-gpi.png" width="130" height="130" alt="Logo">
        </div>
        <h1>Modifier votre mot de passe</h1>
       
                
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="password" name="old_password" placeholder="Ancien mot de passe">
            <input type="password" name="new_password" placeholder="Nouveau mot de passe">
            <input type="password" name="new_password_confirmation" placeholder="Confirmer le mot de passe">
            <button type="submit">Mettre à jour</button>
        </form>
        
        <a href="/adminDashboard">Retour au tableau de bord</a>
    </div>
</body>
</html>