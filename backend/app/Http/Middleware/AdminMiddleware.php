<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // $request->user() = l'utilisateur identifié par le token
        $user = $request->user();

        // Si ce n'est pas un admin, on refuse l'accès (erreur 403)
        if (!$user || !$user->estAdmin()) {
            return response()->json([
                'message' => 'Accès réservé à l\'administrateur',
            ], 403);
        }

        // Sinon, la requête continue vers le contrôleur
        return $next($request);
    }
}