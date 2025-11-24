<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Compara el rol del usuario con el rol requerido por la ruta
        if (Auth::user()->role !== $role) {
            abort(403, 'No tienes permiso para acceder a esta área.');
        }

        return $next($request);
    }
}