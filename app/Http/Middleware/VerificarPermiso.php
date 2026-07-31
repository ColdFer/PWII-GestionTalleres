<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarPermiso
{
    public function handle(
        Request $request,
        Closure $next,
        string ...$permisos
    ): Response {
        $usuario = $request->user();

        if (!$usuario) {
            return redirect()->route('login');
        }

        foreach ($permisos as $permiso) {
            if ($usuario->tienePermiso($permiso)) {
                return $next($request);
            }
        }

        abort(
            403,
            'No tiene permiso para acceder a esta sección.'
        );
    }
}