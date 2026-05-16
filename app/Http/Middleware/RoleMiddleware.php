<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * URL dashboard per role — sesuaikan dengan route React kamu
     */
    protected array $dashboardByRole = [
        'admin' => '/admin/dashboard',
        'user'  => '/user/dashboard',
    ];

    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // 1. Pastikan user sudah login
        if (!Auth::check()) {
            return $this->respondWithError(
                $request,
                'Silakan login terlebih dahulu',
                'unauthorized',
                401
            );
        }

        $user = Auth::user();

        // 2. Validasi user punya property 'role'
        if (!isset($user->role)) {
            Auth::logout();
            return $this->respondWithError(
                $request,
                'User role tidak valid',
                'invalid_role',
                401
            );
        }

        // 3. Cek apakah role user sesuai yang diizinkan
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // 4. Role tidak sesuai — arahkan ke dashboard yang benar
        return $this->respondWithError(
            $request,
            'Anda tidak memiliki akses ke halaman ini',
            'forbidden',
            403,
            ['required_roles' => $roles, 'user_role' => $user->role]
        );
    }

    protected function respondWithError(
        Request $request,
        string $message,
        string $code,
        int $statusCode,
        array $additional = []
    ): Response {
        // API / React (Inertia / Axios / Fetch)
        if ($request->expectsJson() || $request->is('api/*')) {
            $payload = [
                'status'     => false,
                'message'    => $message,
                'error_code' => $code,
                'data'       => null,
                ...$additional,
            ];

            // Kalau forbidden, kasih tahu FE harus redirect ke mana
            if ($code === 'forbidden' && isset($additional['user_role'])) {
                $payload['redirect_to'] = $this->dashboardByRole[$additional['user_role']] ?? '/';
            }

            return response()->json($payload, $statusCode);
        }

        // Fallback Blade / web
        return match ($code) {
            'unauthorized', 'invalid_role' => redirect()->route('login')
                ->with('error', $message),

            'forbidden' => isset($additional['user_role'])
                ? redirect($this->dashboardByRole[$additional['user_role']] ?? '/')
                    ->with('error', $message)
                : redirect('/')->with('error', $message),

            default => redirect()->back()->with('error', $message),
        };
    }
}