<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Redirigir según rol
                $user = Auth::guard($guard)->user();

                return match($user->rol) {
                    'admin' => redirect()->route('dashboard.admin'),
                    'repartidor' => redirect()->route('dashboard.delivery'),
                    default => redirect()->route('dashboard.cliente'),
                };
            }
        }

        return $next($request);
    }
}
