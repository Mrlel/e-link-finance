@php
    $code    = '429';
    $icon    = 'fas fa-bolt';
    $title   = 'Trop de requêtes';
    $message = 'Vous avez effectué trop de requêtes en peu de temps. Patientez quelques instants avant de réessayer.';
@endphp
@include('errors._layout')
