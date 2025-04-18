<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request and ensure the user has the expected role.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): \Symfony\Component\HttpFoundation\Response $next
     * @param string $role // e.g. 'teacher' or 'student'
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (!$user || !$user->schools()->wherePivot('role', $role)->exists()) {
            abort(403, 'Unauthorized. You need the role: ' . $role);
        }

        return $next($request);
    }
}
