<?php 
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Check if user has one of the allowed roles
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Redirect based on role
        if ($user->role === 'admin') {
             return $next($request);
        }

        return redirect()->route('user.homepage')->with('error', 'Akses ditolak!');
    }
}