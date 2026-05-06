<?php

namespace App\Http\Controllers\WEB;

use App\Models\Item;
use App\Models\Kategori;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    /**
     * Menampilkan list barang dengan filter kategori
     */
public function index(Request $request)
{
    $user = Auth::user();
    // $user = User::find(8)
    // ->with('kategori')->first();
    // if (!$user) {
    //     // Redirect to login or show error if user is not authenticated
    //     return redirect()->route('login')->withErrors('Anda harus login terlebih dahulu.');
    // }


    
    $jurusan = $user->kategori_id;
    $jurusanNama = $user->kategori->nama_kategori ?? 'Semua Jurusan';

    // Mulai dengan query builder (TANPA get)
    $item = Item::where('kategori_jurusan_id', $jurusan);

    // Inisialisasi kategori default
    $kategori = $jurusan;

    // Filter berdasarkan kategori (jika user memilih dropdown)
    if ($request->filled('kategori_jurusan_id')) {

        $item->where('kategori_jurusan_id', $request->kategori_jurusan_id);

        $kategoriObj = Kategori::find($request->kategori_jurusan_id);
        if ($kategoriObj) {
            $kategori = $kategoriObj->nama_kategori;
        }
    }
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
        ->paginate(9)
        ->appends([
            'search' => $request->search,
            'kategori_jurusan_id' => $request->kategori_jurusan_id
        ]);

    // Dropdown kategori
    $kategoris = Kategori::orderBy('nama_kategori')->get();
    $dataLengkap = [$data, $kategori, $kategoris, $barangjurusan, $jurusanNama];
    return response()->json([
        "status" => true,
        "message" => "berhasil mengambil data untuk jurusan ".$jurusanNama,
        "data" => $dataLengkap,
    ], 200) ;
}

    public function getBarang(){
        $item = Item::with('kategori_jurusan')
        ->lazy();

        return response()->json([
            "status" => true,
            "message" => "Data item berhasil diambil",
            "data" => $item,
        ], 200);
        
    }

    /**
     * Menampilkan gambar yang terenkripsi
     */
    public function showImage($filename)
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
    public function LandingPage(){
        $brg = Item::with('kategori_jurusan')->
        where('status_item', 'tersedia')
        ->orderBy('created_at', 'desc')
        ->limit(6)
        ->get();

        $jurusan = Kategori::get();

        $dataLandingpage = [
            'barang' => $brg,
            'jurusan' => $jurusan
        ];

        return response()->json([
            "status" => true,
            "message" => "data untuk landing page",
            "data" => $dataLandingpage
        ], 200);
    }
}