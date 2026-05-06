<?php

namespace App\Http\Controllers\WEB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;



class UserController extends Controller
{
    public function gantiPassw(Request $request){
        $request->validate([
           'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)], 
        ]);

         $user = Auth::user();

        // Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('profile.index')->with('success', 'Password berhasil diubah!');
    }

    public function login(Request $request){
        
    }

    public function getKategori(){
        $kategori = User::with('kategori')->get();
        return response()->json([
            "status" => true,
            "message" => "data kategori jurusan",
            "data" => $kategori,
        ],);
    }
}
