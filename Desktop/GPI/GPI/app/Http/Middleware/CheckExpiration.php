<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CheckExpiration
{
    public function handle($request, Closure $next)
    {
        $expirationFile = 'system/expiration.txt';

        if (Storage::disk('local')->exists($expirationFile)) {
            $expirationDate = Carbon::parse(
                Storage::disk('local')->get($expirationFile)
            );

            if (Carbon::now()->gt($expirationDate)) {
                return response()->view('layouts.app', [
                    'expirationDate' => $expirationDate->format('d/m/Y')
                ], 403);
            }
        }

        return $next($request);
    }
}