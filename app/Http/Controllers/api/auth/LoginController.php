<?php

namespace App\Http\Controllers\api\auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Services\ActivityLoggerService;
use Illuminate\Http\JsonResponse;
use  Illuminate\Validation\Validator;

class LoginController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'kode'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($request->only('kode', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'kode' => __('auth.failed'),
            ]);
        }

                $request->session()->regenerate();

        ActivityLoggerService::logLogin();
        if(!Auth::attempt($request->only('kode', 'password'))){
                return response()->json([
                    "message" => "login gagal",
                ],401);
        }

        $user = Auth::user();
        $token = $user->createToken("token",['*'], 
        now()->addHours(24))->plainTextToken;

        // return response()->json([
        //     "token" => $token,
        //     "user" => $user,
        // ], 200);

        // $redirect = match($user->role) {
        //     'admin' => route('admin.dashboard'),
        //     default => route('user.homepage'),
        // };

        return response()->json([
            'token' => $token,
            'status'   => true,
            'message'  => 'Login berhasil',
            // 'redirect' => $redirect,
            'user'     => [
                'id'    => $user->id,
                'name'  => $user->name,
                'kode' => $user->kode,
                'role'  => $user->role,
            ],
        ]);
    }
}
