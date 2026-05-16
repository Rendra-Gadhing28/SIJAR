<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{

    public function index() {
    $user = Auth::user();
    $data = User::with('jurusan')->select('id', 'name', 'kode','kelas', 'jurusan_id', 'telepon', 'profile', )->find($user->id);
    $namaJurusan = $data->jurusan ? $data->jurusan->nama_jurusan : null;
    return response()->json([
    'status' => true,
    'data' => [
        'id' => $data->id,
        'name' => $data->name,
        'kode' => $data->kode,
        'kelas' => $data->kelas,
        'telepon' => $data->telepon,
        'profile' => $data->profile,
        'jurusan_id' => $data->jurusan_id,
        'jurusan' => $namaJurusan, // ← nama jurusan
    ]
], 200 );
    }

    public function indexMobile(){
        $user = Auth::user();
        $data = User::select('id', 'name', 'kode', 'telepon', 'profile')->find($user->id);
    return response()->json([
        "status" => true,
        "message" => "Data user berhasil diambil",
        "data" => $data
    ], 200);
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        $user = $request->user()::select('id', 'name', 'email', 'telepon', 'profile')
            ->find($request->user()->id);

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
            'kode' => 'sometimes|string|unique:users,kode,' . $id,
            'telepon' => 'nullable|string|max:255',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $validated['telepon'] = $request->input('telepon');
        if ($request->hasFile('profile')) {
        // Hapus profile lama jika ada
        if ($user->profile && Storage::disk('public')->exists('avatars/' . $user->profile)) {
            Storage::disk('public')->delete('avatars/' . $user->profile);
        }
        
        $profile = time() . '_' . $request->file('profile')->getClientOriginalName();
        $request->file('profile')->storeAs('avatars', $profile, 'public');
        $hash = hash('sha256', $profile, true);
        $base64 = base64_encode($hash);
        $validated['profile'] = $base64;
    }
        unset($validated['role']); // Jangan update role dari sini // Jangan update kode dari sini
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
    $data = User::select('name', 'kode', 'telepon', 'profile')
        ->find($user->id);
        return response()->json([
            "status" => true,
            "message" => "Data user ID {$id} berhasil diupdate",
            "data" => $data// Ambil data terbaru setelah update
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
    /**
     * Update password untuk user yang sedang login.
     * Menggunakan kolom 'password' dari tabel users.
     */
        public function  gantiPassw(Request $request): JsonResponse {
        // 1. Validasi Input - perbaiki nama field
        $request->validate([
        'current_password' => ['required', 'string'],
        'new_password' => [
            'required', 
            'confirmed', 
            Password::min(8)
                ->letters()
                ->numbers()
        ],
    ], [
        'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        'new_password.min' => 'Password baru minimal harus 8 karakter.',
    ]);

    /** @var \App\Models\User $user */
    $user = auth()->user();

    // 2. Cek password lama
    if (!Hash::check($request->current_password, $user->password)) {
        return response()->json([
            "status" => false,
            "message" => "Password lama yang Anda masukkan salah.",
        ], 422);
    }

    // 3. Security Check: Password baru tidak boleh sama dengan password lama
    // PERBAIKAN: gunakan new_password, bukan password
    if (Hash::check($request->new_password, $user->password)) {
        return response()->json([
            "status" => false,
            "message" => "Password baru tidak boleh sama dengan password saat ini."
        ], 422);
    }

    // 4. Update Password - PERBAIKAN: gunakan new_password
    $user->update([
        'password' => Hash::make($request->new_password)
    ]);

    // Optional: Logout user setelah ganti password (biar login ulang)
    // auth()->logout();

    return response()->json([
        "status" => true,
        "message" => "Password untuk user '{$user->name}' berhasil diperbarui."
    ], 200);
    }
}
    