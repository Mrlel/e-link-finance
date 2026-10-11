<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Direction;

class AdminMiddleware
{
  
        public function handle(Request $request, Closure $next)
        {
            if (auth()->user()->role === 'superadmin') {
                // Superadmin : voit toutes les directions
                $directions = Direction::all();
            } else {
                // Admin et utilisateurs : voient leur direction uniquement
                $directions = Direction::where('id', auth()->user()->direction_id)->get();
            }
        
            // Partager les directions avec toutes les vues
            view()->share('directions', $directions);
        
            return $next($request);
        }

    }

