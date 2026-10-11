<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPasswordChange
{
    // Routes exemptées pour ne pas boucler sur elles-mêmes tant que le mot de passe n'est pas changé.
    protected array $except = ['password.change', 'password.update'];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Si l'utilisateur n'a jamais changé son mot de passe, on le redirige vers la page de modification
            if (!$user->password_changed_at && !$request->routeIs(...$this->except)) {
                return redirect()->route('password.change')->with('warning', 'Veuillez changer votre mot de passe.');
            }
        }

        return $next($request);
    }
}
