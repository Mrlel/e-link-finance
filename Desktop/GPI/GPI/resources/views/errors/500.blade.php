@php
    $code    = '500';
    $icon    = 'fas fa-triangle-exclamation';
    $title   = 'Erreur serveur';
    $message = 'Une erreur interne s\'est produite sur le serveur. L\'équipe technique a été informée. Veuillez réessayer dans quelques instants.';
@endphp
@include('errors._layout')
