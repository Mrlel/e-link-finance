<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>QR Code Équipement</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin-top: 40px; }
        .qr-container { margin: 0 auto; width: 220px; }
        .info { margin-top: 20px; font-size: 16px; }
    </style>
</head>
<body>
    <div class="qr-container">
        {!! $qrCode !!}
    </div>
    <div class="info">
        <strong>Équipement :</strong> {{ $equipement->des_equipement }}<br>
        <strong>Numéro de série :</strong> {{ $equipement->numero_serie }}
    </div>
</body>
</html>
