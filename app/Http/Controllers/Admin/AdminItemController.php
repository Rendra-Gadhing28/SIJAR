<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Auth;
use Illuminate\Http\Request;
use App\Models\Item;

class AdminItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $admin = Auth::user();
        $jurusan = $admin->kategori_id;

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
         $itemTersedia = Item::where('status_item', 'tersedia')->count();
         $itemDipinjam = Item::where('status_item', 'dipinjam')->count();
        // Dropdown kategori
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('admin.listbarang', compact('data', 'kategoris', 'kategori','barangjurusan','itemTersedia', 'itemDipinjam'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = Kategori::all();
        return view('admin.create', compact('kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_item' => 'required',
            'jenis_item' => 'required',
            'kode_unit' => 'required',
            'kategori_jurusan_id' => 'required',
            'foto_barang' => 'required|image',
            'status_item' => 'required',
        ]);

        $foto = $request->file('foto_barang')->store('items', 'public');

        Item::create([
            'nama_item' => $request->nama_item,
            'jenis_item' => $request->jenis_item,
            'kode_unit' => $request->kode_unit,
            'kategori_jurusan_id' => $request->kategori_jurusan_id,
            'foto_barang' => $foto,
            'status_item' => $request->status_item,
        ]);

        return redirect()->route('admin.listbarang')->with('success', 'Barang ditambah.');
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
