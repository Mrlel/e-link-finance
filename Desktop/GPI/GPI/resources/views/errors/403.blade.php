@php
    $code    = '403';
    $icon    = 'fas fa-ban';
    $title   = 'Accès refusé';
    $message = isset($exception) && $exception->getMessage()
        ? $exception->getMessage()
        : 'Vous n\'avez pas les droits nécessaires pour accéder à cette page. Contactez votre administrateur si vous pensez qu\'il s\'agit d\'une erreur.';
@endphp
@include('errors._layout')
