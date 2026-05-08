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
    if (!$user) {
    return response()->json([
        "status" => false,
        "message" => "Unauthenticated. Silakan login terlebih dahulu.",
    ], 401);
}
    $user->load('kategori');
    $jurusan = $user->kategori_id;
    $jurusanNama = $user->kategori->nama_kategori ?? 'Semua Jurusan';

    // Mulai dengan query builder (TANPA get)
    $item = Item::with('kategori_jurusan');

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
    
    
   
    // Filter hanya barang yang tersedia
    $item->where('status_item', 'tersedia');
    $barangjurusan = $item->count();

    // Ambil data lengkap dengan relasi kategori
   $data = $item->with('kategori_jurusan')
        ->orderBy('created_at', 'desc')
        ->paginate(9)->appends($request->only(['search', 'kategori_jurusan_id']));;
        // ->appends([
        //     'search' => $request->search,
        //     'kategori_jurusan_id' => $request->kategori_jurusan_id
        // ]);
    $AllDataJurusan = Item::with('kategori_jurusan')->orderBy('created_at', 'desc')->paginate(9);

    // Dropdown kategori
    $kategoris = Kategori::orderBy('nama_kategori')->get();
    $dataLengkap = [$data, $kategori, $kategoris, $barangjurusan, $jurusanNama];
    return response()->json([
        "status" => true,
        "message" => "berhasil mengambil data untuk jurusan ".$jurusanNama,
        "data" => $dataLengkap[0],
        "kategori" => $dataLengkap[1],
        "Totalbarangjurusan" => $dataLengkap[3],
        "jurusanNama" => $dataLengkap[4],
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
    public function showImage(Request $req)
{
    $filename = $req->input('filename');
    
    // ✅ File biasa, tidak perlu decrypt apapun!
    $gambar_barang = 'encrypted/' . $filename;
    $icons = 'icons/' . $filename;
    
    if (!Storage::disk('public')->exists($gambar_barang) && !Storage::disk('public')->exists($icons)) {
        return response()->file(public_path('images/placeholder.png'));
    }

    $file     = Storage::disk('public')->get($gambar_barang ?: $icons);
    $finfo    = new \finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->buffer($file);

    return response($file)
        ->header('Content-Type', $mimeType)
        ->header('Cache-Control', 'public, max-age=86400')
        ->header('Access-Control-Allow-Origin', 'http://localhost:5173');
}

        public function LandingPage(){
    $brg = Item::where('status_item', 'tersedia')
        ->select('id', 'nama_item', 'jenis_item', 'kode_unit', 'kategori_jurusan_id', 'foto_barang')
        ->orderBy('created_at', 'desc')
        ->limit(6)
        ->get()
        ->map(function ($item) {
            $item->foto_url = $item->foto_barang
                ? asset('storage/encrypted/' . $item->foto_barang)
                : null;
            return $item;
        });

    $jurusan = Kategori::select('id', 'nama_kategori', 'icon')
        ->get()
        ->map(function ($kat) {
            $kat->icon_url = $kat->icon
                ? asset('storage/icons/' . $kat->icon)
                : null;
            return $kat;
        });

    return response()->json([
        "status"  => true,
        "message" => "data untuk landing page",
        "data"    => ['barang' => $brg, 'jurusan' => $jurusan]
    ], 200);
}
}