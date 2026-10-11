@php
    $code    = '404';
    $icon    = 'fas fa-search';
    $title   = 'Page introuvable';
    $message = 'La page que vous recherchez n\'existe pas, a été déplacée ou son URL a changé. Vérifiez l\'adresse saisie ou revenez à l\'accueil.';
@endphp
@include('errors._layout')
