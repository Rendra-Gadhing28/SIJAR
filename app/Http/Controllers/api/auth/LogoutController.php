<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Services\ActivityLoggerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
   public function destroy(Request $request): JsonResponse
{
    // 1. Ambil data user sebelum logout agar tidak null
    $user = $request->user();
    $userName = $user ? $user->name : 'Pengguna';

    // 2. Log aktivitas (pastikan service ini menangkap data sebelum auth hilang)
    ActivityLoggerService::logLogout();

    // 3. Proses Logout
    Auth::guard('web')->logout();

    // 4. Invalidate & Regenerate Token (Standar Keamanan Laravel)
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return response()->json([
        'status'  => true,
        'message' => "Logout berhasil, semoga harimu menyenangkan " . $userName . "!",
    ], 200);
}
}