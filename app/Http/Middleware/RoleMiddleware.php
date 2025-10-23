<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Check role
        if ($role === 'admin' && !$user->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }
        
        if ($role === 'trainer' && !$user->isTrainer()) {
            abort(403, 'Unauthorized access.');
        }
        
        if ($role === 'customer' && !$user->isCustomer()) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}