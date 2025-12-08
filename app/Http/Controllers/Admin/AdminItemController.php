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
    $admin = Auth::user();
    $jurusan = $admin->kategori_id;

    // Query utama berdasarkan kategori admin
    $query = Item::where('kategori_jurusan_id', $jurusan);

    // Inisialisasi kategori default
    $kategori = $admin->kategori->nama_kategori ?? 'Semua Kategori';

 // Filter status
    if ($request->has('status_item') && $request->status_item != '') {
        $query->where('status_item', $request->status_item);
    }

    // Filter search (nama item atau kode unit)
    if ($request->filled('search')) {
        $keyword = $request->search;
        
        $query->where(function ($q) use ($keyword) {
            $q->where('nama_item', 'LIKE', "%$keyword%")
              ->orWhere('kode_unit', 'LIKE', "%$keyword%");
        });
    }

    // Hitung total barang yang sesuai filter (SEBELUM filter status)
    $barangjurusan = $query->count();

    // Clone query untuk statistik berdasarkan status
    $itemTersedia = (clone $query)->where('status_item', 'tersedia')->count();
    $itemDipinjam = (clone $query)->where('status_item', 'dipinjam')->count();
    $itemRusak = (clone $query)->where('status_item', 'rusak')->count();

    // Filter hanya barang yang tersedia untuk ditampilkan di list
  

    // Ambil data lengkap dengan relasi kategori
    $data = $query->with('kategori_jurusan')
        ->orderBy('created_at', 'desc')
        ->paginate(9)
        ->appends([
            'search' => $request->search,
            'kategori_jurusan_id' => $request->kategori_jurusan_id
        ]);

    // Dropdown kategori
    $kategoris = Kategori::orderBy('nama_kategori')->get();

    return view('admin.listbarang', compact(
        'data', 
        'kategoris', 
        'kategori', 
        'barangjurusan',
        'itemTersedia', 
        'itemDipinjam',
        'itemRusak'
    ));
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
                return redirect()->back()->with('error', 'Barang masih dipinjam, tidak bisa diupdate');
            }
            elseif($barang->status_item == 'tersedia'){
                return redirect()->back()->with('error', 'Barang sudah dalam status tersedia!');
            }


            $barang->status_item = 'tersedia';
            $barang->save();

             ActivityLoggerService::logUpdated(
                'Item',
                $barang->id,
                ['status_Item' => 'rusak'],
                ['status_Item' => 'tersedia']
            );
            return redirect()->back()->with('success', "Barang '{$barang->nama_item}' berhasil diubah menjadi tersedia");
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }
    public function setRusak($id)
{
    try {
        $barang = Item::findOrFail($id);
        
        // Cek apakah barang sedang dipinjam
        if ($barang->status_item == 'dipinjam') {
            return redirect()->back()->with('error', 'Barang sedang dipinjam, tidak bisa diubah menjadi rusak!');
        }
        elseif($barang->status_item == 'rusak'){
            return redirect()->back()->with('error', 'Barang sudah dalam status rusak!');
        }

        $barang->status_item = 'rusak';
        $barang->save();
        ActivityLoggerService::logUpdated(
                'Item',
                $barang->id,
                ['status_Item' => 'tersedia'],
                ['status_Item' => 'rusak']
            );
        return redirect()->back()->with('success', "Barang '{$barang->nama_item}' berhasil ditandai sebagai rusak");
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal mengubah status: ' . $e->getMessage());
    }
}

    
}
