<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
 public function handle(Request $request, Closure $next)
    {

        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->role === 'admin') {
            return $next($request);
        }
        elseif(auth()->user()->role === 'user'){
            return redirect()->route('user.homepage');
        }

        return redirect('/landing')->with('error', 'Anda tidak memiliki akses.');
    }

}
