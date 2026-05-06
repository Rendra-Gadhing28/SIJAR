<?php

namespace App\Http\Controllers\api\auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Services\ActivityLoggerService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

class LoginController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

                $request->session()->regenerate();

        ActivityLoggerService::logLogin();
        if(!Auth::attempt($request->only('email', 'password'))){
                return response()->json([
                    "message" => "login gagal",
                ],401);
        }

        $user = Auth::user();
        $token = $user->createToken("token",['*'], 
        now())->plainTextToken;

        // return response()->json([
        //     "token" => $token,
        //     "user" => $user,
        // ], 200);

        $redirect = match($user->role) {
            'admin' => route('admin.dashboard'),
            default => route('user.homepage'),
        };

        return response()->json([
            'token' => $token,
            'status'   => true,
            'message'  => 'Login berhasil',
            'redirect' => $redirect,
            'user'     => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
        ]);
    }
}
