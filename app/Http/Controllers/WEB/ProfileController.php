<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{

    public function index(){
        // $user = Auth::user();
        // $user->where('role' != 'Admin')->get();
        $data = User::lazy();
         return response()->json([
            "status" => true,
            "message" => "data peminjaman berhasil diambil",
            "data" => $data
        ], 200);

        if(!$data){
            return response()->json([
            "status" => false,
            "message" => "data tidak ada",
            "data" => $data
        ], 400);
        }
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        $user = $request->user();

         return response()->json([
            "status" => true,
            "message" => "data peminjaman berhasil diambil",
            "data" => $user
        ], 200);
    }

    /**
     * Update the user's profile information.
     */
    public function update($id, Request $request): \Illuminate\Http\JsonResponse
{
    $user = User::find($id);
    
    if (!$user) {
        return response()->json([
            "status" => false,
            "message" => "User dengan ID {$id} tidak ditemukan",
            "data" => null
        ], 404);
    }
    
    try {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'telepon' => 'nullable|string|max:255',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $validated['telepon'] = $request->input('telepon');
        if ($request->hasFile('profile')) {
        // Hapus profile lama jika ada
        if ($user->profile && Storage::disk('public')->exists('avatars/' . $user->profile)) {
            Storage::disk('public')->delete('avatars/' . $user->profile);
        }
        
        $profile = time() . '_' . $request->file('profile')->getClientOriginalName();
        $request->file('profile')->storeAs('avatars', $profile, 'public');
        $validated['profile'] = $profile;
    }
        unset($validated['role']); // Jangan update role dari sini
        unset($validated['jurusan_id']);
        unset($validated['kategori_id']);
        unset($validated['kelas']);
    

        // Hapus 3 baris ini - TIDAK PERLU karena sudah ada di $validated
    // $validated['name'] = $request->name;
    // $validated['email'] = $request->email;
    // $validated['telepon'] = $request->telepon;
      // Remove fields that shouldn't be updated
       
        $user->update($validated);
        
        // $user->update($validated);
        
        return response()->json([
            "status" => true,
            "message" => "Data user ID {$id} berhasil diupdate",
            "data" => $user->fresh()
        ], 200);
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            "status" => false,
            "message" => "Validasi gagal",
            "errors" => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            "status"  => false,
            "message" => "Terjadi kesalahan: " . $e->getMessage()
        ], 500);
    }
    
}
    
    
    
    
    
    // HAPUS baris ini - Tidak perlu dan akan error
    // $request->user()->save();
    
    // return response()->json([
    //     "status" => true,
    //     "message" => "Data user ID {$id} berhasil diupdate",
    //     "data" => $user->fresh()  // Ambil data terbaru setelah update
    // ], 200);


    /**
     * Delete the user's account.
     */
   public function destroy(Request $request): \Illuminate\Http\JsonResponse
{
    $request->validate([
        'password' => ['required', 'current_password'], 
    ]);

    $user = $request->user();

    // Optional: Hapus profile image jika ada
    if ($user->profile && Storage::disk('public')->exists('avatars/' . $user->profile)) {
        Storage::disk('public')->delete('avatars/' . $user->profile);
    }

    // Logout
    Auth::logout();
    
    // Hapus user
    $user->delete();

    // Invalidate session
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return response()->json([
        "status" => true,
        "message" => "Akun berhasil dihapus",
        "data" => null
    ], 200);
}
    public function gantiPassw(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();

        // Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                "status" => false,
                "message" => "Password lama tidak sesuai"
            ], 422);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            "status" => true,
            "message" => "Password berhasil diubah"
        ], 200);
    }
}
    