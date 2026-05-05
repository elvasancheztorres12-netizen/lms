<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect('/login');
        }

        $userRole = optional($user->roles->first())->name;

        if ($userRole !== $role) {
            abort(403, 'No autorizado');
        }

        return $next($request);
    }
}