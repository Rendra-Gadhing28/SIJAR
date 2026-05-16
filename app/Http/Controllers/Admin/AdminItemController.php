<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\ActivityLoggerService;


class AdminItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
            public function index(Request $request)
{
    $user = Auth::user();

    if (!$user) {
        return response()->json([
            "status"  => false,
            "message" => "Unauthenticated. Silakan login terlebih dahulu.",
        ], 401);
    }

    $user->load('kategori');
    $jurusanNama = $user->kategori->nama_kategori ?? 'Semua Jurusan';

    $item = Item::with('kategoriJurusan');

    // Filter by kategori_jurusan_id
    if ($request->filled('kategori_jurusan_id')) {
        $item->where('kategori_jurusan_id', $request->kategori_jurusan_id);
    }

    // Filter by nama jurusan (string dari tab)
    if ($request->filled('jurusan') && $request->jurusan !== 'Semua') {
        $item->whereHas('kategoriJurusan', function ($q) use ($request) {
            $q->where('nama_kategori', $request->jurusan);
        });
    }

    // Filter search
    if ($request->filled('search')) {
        $keyword = $request->search;
        $item->where(function ($q) use ($keyword) {
            $q->where('nama_item', 'LIKE', "%$keyword%")
              ->orWhere('kode_unit', 'LIKE', "%$keyword%");
        });
    }
        $baseQuery = $item; // query builder dengan semua filter yang sudah diterapkan

$Tersedia      = (clone $baseQuery)->where('status_item', 'tersedia')->get();
$Dipinjam      = (clone $baseQuery)->where('status_item', 'dipinjam')->get();
$Rusak         = (clone $baseQuery)->where('status_item', 'rusak')->get();
$BarangJurusan = (clone $baseQuery)->get();

$barangTersedia     = $Tersedia->count();
$barangDipinjam     = $Dipinjam->count();
$barangRusak        = $Rusak->count();
$totalBarangJurusan = $BarangJurusan->count();

$data = (clone $baseQuery)
    ->orderBy('created_at', 'desc')
    ->paginate(8)
    ->appends($request->only(['search', 'kategori_jurusan_id', 'jurusan']));
    // Tambah foto_url ke setiap item
    $data->getCollection()->transform(function ($item) {
        $item->foto_url = $item->foto_barang
            ? asset('storage/encrypted/' . $item->foto_barang)
            : null;
        return $item;
    });

    return response()->json([
        "status"             => true,
        "message"            => "Berhasil mengambil data untuk jurusan " . $jurusanNama,
        "data"               => $data,               // ✅ PagingData (current_page, data[], last_page, total)
        "Totalbarangjurusan" => $totalBarangJurusan,  // ✅ Int langsung
        "BarangTersedia" => $barangTersedia,  // ✅ Int langsung
        "BarangDipinjam" => $barangDipinjam,  // ✅ Int langsung
        "BarangRusak" => $barangRusak,  // ✅ Int langsung
        "jurusanNama"        => $jurusanNama,         // ✅ String
    ], 200);
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = Kategori::all();
        return view('admin.create', compact('kategori'));
    }

    public function store(Request $request)
{
    // Validasi input
    $validated = $request->validate([
        'nama_item' => 'required|string|max:255',
        'jenis_item' => 'required|string|max:255',
        'kategori_jurusan_id' => 'required|exists:kategori_jurusan,id',
        'foto_barang' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // Ambil kategori
   // Ambil kategori (misal: PPLG)
$kategori = Kategori::find($validated['kategori_jurusan_id']);
$LK = strtoupper(substr($kategori->nama_kategori, 0, 4)); // PPLG

// Buat prefix dari nama item (ambil huruf saja)
$prefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $validated['nama_item']), 0, 4)); 
// PROY dari PROYEKTOR

// Cari item terakhir sesuai kategori & prefix
$lastItem = Item::where('kategori_jurusan_id', $validated['kategori_jurusan_id'])
    ->where('kode_unit', 'like', "$LK-$prefix-%")
    ->orderBy('kode_unit', 'desc')
    ->first();

if ($lastItem) {
    // Ambil angka di belakang
    $parts = explode('-', $lastItem->kode_unit); 
    $lastNumber = intval(end($parts));
    $newNumber = $lastNumber + 1;
} else {
    $newNumber = 1;
}

// Format akhir → PPLG-PROY-0001
$kodeUnit = sprintf('%s-%s-%04d', $LK, $prefix, $newNumber);

    // Ambil isi file asli
$content = file_get_contents($request->file('foto_barang')->getRealPath());

// Ambil ekstensi asli file
$extension = $request->file('foto_barang')->getClientOriginalExtension();

// Nama file dengan hash
$encrypt = hash('sha256', $content . time()) . '.' . $extension;

// Simpan file ke folder encrypted
Storage::disk('public')->put('encrypted/' . $encrypt, $content);




   

    // Simpan ke database
    $item = Item::create([
        'nama_item' => $validated['nama_item'],
        'jenis_item' => $validated['jenis_item'],
        'kode_unit' => $kodeUnit,
        'kategori_jurusan_id' => $validated['kategori_jurusan_id'],
        'foto_barang' => $encrypt,
        'status_item' => 'tersedia',
    ]);   
    ActivityLoggerService::logCreated('Item', $item->id, $item->toArray());
    return redirect()->route('admin.barang.index')
        ->with('success', 'Barang berhasil ditambahkan dengan kode: ' . $kodeUnit);
}

    /**
     * Store a newly created resource in storage.
     */
   

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

   public function setTersedia($id){
    try{
        $barang = Item::findOrFail($id);
        if($barang->status_item == 'dipinjam'){
            return response()->json([
                'success' => false,
                'message' => 'Barang masih dipinjam, tidak bisa diupdate'
            ], 400);
        }
        elseif($barang->status_item == 'tersedia'){
            return response()->json([
                'success' => false,
                'message' => 'Barang sudah dalam status tersedia!'
            ], 400);
        }

        $barang->status_item = 'tersedia';
        $barang->save();

        ActivityLoggerService::logUpdated(
            'Item',
            $barang->id,
            ['status_Item' => 'rusak'],
            ['status_Item' => 'tersedia']
        );

        return response()->json([
            'success' => true,
            'message' => "Barang '{$barang->nama_item}' berhasil diubah menjadi tersedia"
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengubah status: ' . $e->getMessage()
        ], 500);
    }
}

public function setRusak($id)
{
    try {
        $barang = Item::findOrFail($id);

        if ($barang->status_item == 'dipinjam') {
            return response()->json([
                'success' => false,
                'message' => 'Barang sedang dipinjam, tidak bisa diubah menjadi rusak!'
            ], 400);
        }
        elseif($barang->status_item == 'rusak'){
            return response()->json([
                'success' => false,
                'message' => 'Barang sudah dalam status rusak!'
            ], 400);
        }

        $barang->status_item = 'rusak';
        $barang->save();

        ActivityLoggerService::logUpdated(
            'Item',
            $barang->id,
            ['status_Item' => 'tersedia'],
            ['status_Item' => 'rusak']
        );

        return response()->json([
            'success' => true,
            'message' => "Barang '{$barang->nama_item}' berhasil ditandai sebagai rusak"
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengubah status: ' . $e->getMessage()
        ], 500);
    }
}
}
