@php
    $code    = '503';
    $icon    = 'fas fa-wrench';
    $title   = 'Service indisponible';
    $message = isset($exception) && $exception->getMessage()
        ? $exception->getMessage()
        : 'Le service est temporairement indisponible pour maintenance. Nous reviendrons bientôt. Merci de votre compréhension.';
@endphp
@include('errors._layout')
