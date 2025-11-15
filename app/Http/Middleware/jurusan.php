<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class jurusan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         $jurusanUser = Auth::user()->jurusan;
    $jurusanRoute = $request->route('jurusan');

    if ($jurusanUser !== $jurusanRoute) {
        abort(403, 'Tidak boleh akses');
    }

    return $next($request);
    }
}
