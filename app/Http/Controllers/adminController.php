<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Kategori;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class adminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->withErrors('Anda harus login terlebih dahulu.');
        }

        // Redirect admin ke halaman admin
        if ($user->role === 'admin') {
            return redirect()->route('admin.items.index');
        }

        $jurusan = $user->kategori_id;

        // Mulai dengan query builder
        $item = Item::where('kategori_jurusan_id', $jurusan);

        // Inisialisasi kategori default
        $kategori = $jurusan;

        // Filter berdasarkan kategori
        if ($request->filled('kategori_jurusan_id')) {
            $item->where('kategori_jurusan_id', $request->kategori_jurusan_id);

            $kategoriObj = Kategori::find($request->kategori_jurusan_id);
            if ($kategoriObj) {
                $kategori = $kategoriObj->nama_kategori;
            }
        }
        
        // Search functionality
        if ($request->search) {
            $keyword = $request->search;

            $item->where(function ($q) use ($keyword) {
                $q->where('nama_item', 'LIKE', "%$keyword%")
                  ->orWhere('kode_unit', 'LIKE', "%$keyword%");
            });
        }

        $barangjurusan = $item->count();
        
        // Filter hanya barang yang tersedia
        $item->where('status_item', 'tersedia');

        // Ambil data lengkap dengan relasi kategori
        $data = $item->with('kategori_jurusan')
            ->orderBy('created_at', 'desc')
            ->simplePaginate(10)
            ->appends([
                'search' => $request->search,
                'kategori_jurusan_id' => $request->kategori_jurusan_id
            ]);

        // Dropdown kategori
        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('user.listbarang', compact('data', 'kategoris', 'kategori', 'barangjurusan'));
    }

    /**
     * Menampilkan gambar yang terenkripsi
     */
    public function showImage($filename)
    {
        try {
            if (Storage::exists('encrypted/' . $filename)) {
                $encryptedImage = Storage::get('encrypted/' . $filename);
                $decryptedImage = Crypt::decrypt($encryptedImage);
            } else {
                $decryptedImage = Crypt::decrypt($filename);
            }

            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($decryptedImage);

            return response($decryptedImage)
                ->header('Content-Type', $mimeType)
                ->header('Cache-Control', 'public, max-age=3600');

        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return response()->file(public_path('images/placeholder.png'));
        } catch (\Exception $e) {
            return response()->file(public_path('images/placeholder.png'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function adminIndex(Request $request)
    {
        $user = Auth::user();
        
        // Pastikan hanya admin yang bisa akses
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('items.index')->withErrors('Anda tidak memiliki akses ke halaman ini.');
        }

        $jurusan = $user->kategori_id;

        // Mulai dengan query builder
        $item = Item::where('kategori_jurusan_id', $jurusan);

        // Inisialisasi kategori default
        $kategori = $jurusan;

        // Filter berdasarkan kategori
        if ($request->filled('kategori_jurusan_id')) {
            $item->where('kategori_jurusan_id', $request->kategori_jurusan_id);

            $kategoriObj = Kategori::find($request->kategori_jurusan_id);
            if ($kategoriObj) {
                $kategori = $kategoriObj->nama_kategori;
            }
        }
        
        // Search functionality
        if ($request->search) {
            $keyword = $request->search;

            $item->where(function ($q) use ($keyword) {
                $q->where('nama_item', 'LIKE', "%$keyword%")
                  ->orWhere('kode_unit', 'LIKE', "%$keyword%");
            });
        }

        $barangjurusan = $item->count();
        
        // Admin bisa lihat semua status barang (tidak hanya 'tersedia')
        $data = $item->with('kategori_jurusan')
            ->orderBy('created_at', 'desc')
            ->simplePaginate(10)
            ->appends(['search' => $request->search, 'kategori_jurusan_id' => $request->kategori_jurusan_id]);

        // Dropdown kategori
        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('admin.listbarang', compact('data', 'kategoris', 'kategori', 'barangjurusan'));
    }

    /**
     * Menampilkan gambar yang terenkripsi
     */
    public function gambar($filename)
    {
        try {
            // Cek apakah filename sudah terenkripsi atau masih path biasa
            // Jika foto_barang berisi path file (bukan encrypted data)
            if (Storage::exists('encrypted/' . $filename)) {
                $encryptedImage = Storage::get('encrypted/' . $filename);
                $decryptedImage = Crypt::decrypt($encryptedImage);
            }
            // Jika foto_barang berisi encrypted data langsung di database
            else {
                // Dekripsi langsung dari data
                $decryptedImage = Crypt::decrypt($filename);
            }

            // Deteksi MIME type
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($decryptedImage);

            // Return response dengan cache
            return response($decryptedImage)
                ->header('Content-Type', $mimeType)
                ->header('Cache-Control', 'public, max-age=3600');

        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            // Jika gagal decrypt, kembalikan placeholder
            return response()->file(public_path('images/placeholder.png'));
        } catch (\Exception $e) {
            return response()->file(public_path('images/placeholder.png'));
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
