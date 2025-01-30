<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    private $roleHierarchy = [
        'guest' => 0,
        'subscriber' => 1,
        'theme_manager' => 2,
        'admin' => 3
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $minimumRole): Response
    {
        if (!Auth::check()) {
            return redirect('/')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        $userRole = Auth::user()->role;
        
        // Si le rôle de l'utilisateur n'existe pas dans la hiérarchie
        if (!isset($this->roleHierarchy[$userRole])) {
            return redirect('/')->with('error', 'Rôle utilisateur invalide.');
        }

        // Si le rôle minimum requis n'existe pas dans la hiérarchie
        if (!isset($this->roleHierarchy[$minimumRole])) {
            return redirect('/')->with('error', 'Configuration de rôle invalide.');
        }

        // Vérifier si le niveau du rôle de l'utilisateur est suffisant
        if ($this->roleHierarchy[$userRole] >= $this->roleHierarchy[$minimumRole]) {
            return $next($request);
        }

        return redirect('/')->with('error', 'Accès non autorisé.');
    }
}
