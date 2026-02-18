<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // 1. Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = Auth::user();

        // 2. Validasi user punya property 'role'
        if (!isset($user->role)) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'User role tidak valid');
        }

        // 3. Check apakah role user sesuai dengan yang diizinkan
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // 4. Redirect ke halaman yang sesuai dengan role user (TIDAK ABORT!)
        return $this->redirectBasedOnRole($user->role);
    }

    /**
     * Redirect user berdasarkan role mereka
     */
    protected function redirectBasedOnRole(string $role): Response
    {
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut'),
            'user' => redirect()->route('user.homepage')
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut'),
            default => redirect()->route('login')
                ->with('error', 'Role tidak dikenali, silakan login kembali'),
        };
    }
}