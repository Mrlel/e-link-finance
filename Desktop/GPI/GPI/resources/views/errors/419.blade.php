@php
    $code    = '419';
    $icon    = 'fas fa-clock';
    $title   = 'Session expirée';
    $message = 'Votre session a expiré ou le jeton de sécurité est invalide. Veuillez recharger la page et réessayer. Si le problème persiste, reconnectez-vous.';
@endphp
@include('errors._layout')
