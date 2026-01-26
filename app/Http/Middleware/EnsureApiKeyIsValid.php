<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiKeyIsValid extends Authenticate
{
    /**
     * Allow access if the request is authenticated with Sanctum or a valid API key.
     */
    /**
     * @param  \Illuminate\Http\Request  $request
     */
    public function handle($request, Closure $next, ...$guards)
    {
        /** @var Request $request */
        $guards = empty($guards) ? ['sanctum'] : $guards;

        try {
            // Primero, intenta la autenticacion por guard (Sanctum por defecto).
            $this->authenticate($request, $guards);

            return $next($request);
        } catch (AuthenticationException) {
            // Fallback: valida el header API-KEY y suplantar admin si es valido.
            if ($this->authenticateUsingApiKey($request)) {
                return $next($request);
            }

            return response()->json([
                'message' => 'No autorizado. Proporciona un token Sanctum válido o una API-KEY.',
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Try to replace the authenticated user with the admin when a valid API key is provided.
     */
    protected function authenticateUsingApiKey(Request $request): bool
    {
        // Usa la clave de la app como API key esperada.
        $expectedKey = config('app.key');
        $providedKey = $request->header('API-KEY');

        // Comprobaciones basicas antes de comparar.
        if (! is_string($expectedKey) || $expectedKey === '') {
            return false;
        }

        if (! is_string($providedKey) || $providedKey === '') {
            return false;
        }

        // Comparacion segura ante timing attacks.
        if (! hash_equals($expectedKey, $providedKey)) {
            return false;
        }

        // Resuelve el usuario admin para actuar como principal autenticado.
        $adminUser = User::query()
            ->whereHas('role', function ($query) {
                $query->where('name', 'admin');
            })
            ->first();

        if (! $adminUser) {
            return false;
        }

        // Inyecta el usuario en el contexto de request/auth.
        $request->setUserResolver(fn () => $adminUser);
        Auth::shouldUse('sanctum');
        Auth::setUser($adminUser);

        return true;
    }
}
