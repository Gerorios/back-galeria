<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Verificar si el usuario está autenticado y es administrador
        if (Auth::guard('sanctum')->check() && $request->user()->is_admin) {
            return $next($request);
        }

        return response()->json(['error' => 'Unauthorized access'], 403);
    }
}

