<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ActivityLoggerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\waktu_pembelajaran;
use App\Models\peminjaman;
use App\Models\Kategori;
use App\Models\slot_peminjaman;
use App\Notifications\PeminjamanBaruNotification;


class peminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {   //membuat variabel peminjaman yang berisi data dari table peminjaman
        //data peminjaman yang diambil hanya milik user yang sedang login
        $peminjaman = peminjaman::where("user_id", Auth::id())
            ->with("item")//relasi table item
            ->latest()// mengambil data yang terbaru
            ->paginate(10);//menampilkan 10 data per halaman

        return view('user.riwayat', compact("peminjaman"));
    }

    /**
     * Show the form for creating a new resource.
     */
 public function create(Request $request)
{
    $user = Auth::user();
    if (!$user) {
        return redirect()->route('login')->withErrors('Anda harus login terlebih dahulu.');
    }

    $jurusan_user = $user->kategori_nama_kategori;

    // DEFAULT: tampilkan SEMUA item
    $itm = Item::query();

    // FILTER: Search
    if ($request->filled('search')) {
        $searchTerm = $request->search;
        $itm->where('nama_item', 'like', '%' . $searchTerm . '%');
    }

    // FILTER: Jenis
    if ($request->filled('jenis')) {
        $itm->where('jenis_item', $request->jenis);
    }

    // FILTER: Status
    if ($request->filled('status')) {
        $itm->where('status_item', $request->status);
    }

    // FILTER: Jurusan berdasarkan nama_kategori
    if ($request->filled('jurusan')) {
        $itm->join('kategori_jurusan as kj', 'item.kategori_jurusan_id', '=', 'kj.id')
            ->where('kj.nama_kategori', $request->jurusan)
            ->select('item.*');
    }

    $itm->orderBy('created_at', 'desc');

    $items = $itm->paginate(12)->withQueryString();

    // Data untuk dropdown filter
    $jenis_items = Item::select('jenis_item')->distinct()->pluck('jenis_item');
    $status_item = Item::select('status_item')->distinct()->pluck('status_item');
    
    // Ambil semua kategori jurusan untuk dropdown
    $jurusan = Kategori::select('id', 'nama_kategori')
        ->distinct()
        ->orderBy('nama_kategori')
        ->get();

    $waktu = waktu_pembelajaran::orderBy('jam_ke')->get();

    return view('user.pinjam', compact(
        'items', 
        'waktu', 
        'jenis_items', 
        'status_item', 
        'jurusan',
        'jurusan_user'
    ));
}


    public function store(Request $request)
    {
        $validated = $request->validate([
            'keperluan' => 'required|string|max:255',
            'item_id' => 'required|exists:item,id',
            'kode_unit' => 'nullable|string',
            'waktu_ids' => 'required|array|min:1',
            'waktu_ids.*' => 'string',  // Array of JSON strings
            'bukti' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'waktu_ids.required' => 'Pilih minimal 1 waktu pembelajaran',
            'waktu_ids.array' => 'Format waktu tidak valid',
            'bukti.required' => 'Bukti peminjaman wajib diupload',
        ]);

        DB::beginTransaction();

        try {
            // Upload bukti
            $path = $request->file('bukti')->store('bukti_peminjaman', 'public');

            // Proses waktu_ids: Decode, validasi, dan siapkan array untuk jam_pembelajaran
            $jamPembelajaran = [];
            foreach ($validated['waktu_ids'] as $waktuJson) {
                // Decode JSON string ke array
                $waktuData = json_decode($waktuJson, true);

                // Validasi struktur JSON
                if (!is_array($waktuData) || !isset($waktuData['jam_ke'], $waktuData['start_time'], $waktuData['end_time'])) {
                    throw new \Exception('Format waktu tidak valid: ' . $waktuJson);
                }

                // Validasi apakah waktu ada di DB (opsional, tapi disarankan untuk keamanan)
                $waktu = waktu_pembelajaran::where('jam_ke', $waktuData['jam_ke'])
                    ->where('start_time', $waktuData['start_time'])
                    ->where('end_time', $waktuData['end_time'])
                    ->first();

                if (!$waktu) {
                    throw new \Exception('Waktu pembelajaran tidak ditemukan: Jam ' . $waktuData['jam_ke'] . ', ' . $waktuData['start_time'] . ' - ' . $waktuData['end_time']);
                }

                // Tambahkan ke array jam_pembelajaran
                $jamPembelajaran[] = $waktuData;
            }

            // Buat peminjaman dengan jam_pembelajaran
            $peminjaman = peminjaman::create([
                'keperluan' => $validated['keperluan'],
                'user_id' => Auth::id(),
                'item_id' => $validated['item_id'],
                'tanggal' => now()->toDateString(),
                'status_tujuan' => 'Pending',
                'status_pinjaman' => 'dipinjam',
                'gambar_bukti' => $path,
                'jam_pembelajaran' => json_encode($jamPembelajaran),  // Simpan sebagai JSON array
            ]);

            // Update status item
            $item = Item::find($validated['item_id']);
            $item->update(['status' => 'tidak_tersedia']);

            // Kirim notifikasi ke admin
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new PeminjamanBaruNotification($peminjaman));
            }

            // Log the creation action
            ActivityLoggerService::logCreated(
                'Peminjaman',
                $peminjaman->id,
                ['keperluan' => $peminjaman->keperluan, 'item_id' => $peminjaman->item_id, 'status_tujuan' => 'Pending']
            );

            DB::commit();

            return redirect()->route('peminjaman.index')
                ->with('success', 'Peminjaman berhasil diajukan! Menunggu persetujuan admin.');

        } catch (\Exception $e) {
            DB::rollBack();

            if (isset($path)) {
                Storage::disk('public')->delete($path);
            }

            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function selesai($id)
    {
        $peminjaman = peminjaman::findOrFail($id);

        // Update status peminjaman
        $peminjaman->update([
            'status_pinjaman' => 'selesai',
            'finished_at' => now()
        ]);

        // Kembalikan barang menjadi tersedia
        $item = Item::find($peminjaman->item_id);
        $item->update(['status_item' => 'tersedia']);

        // Log the completion action
        ActivityLoggerService::logUpdated(
            'Peminjaman',
            $peminjaman->id,
            ['status_pinjaman' => 'dipinjam'],
            ['status_pinjaman' => 'selesai']
        );

        return back()->with('success', 'Barang berhasil dikembalikan dan status diperbarui!');
    }
    public function rusak($id)
    {
        $item = Item::findOrFail($id);

        $item->update(['status_item' => 'rusak']);

        return back()->with('success', 'Status barang diubah menjadi rusak.');
    }



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $peminjaman = Peminjaman::with(['barang', 'slotPeminjaman.waktu', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('user.show', compact('peminjaman'));
    }

    public function edit(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user) {
            // Redirect to login or show error if user is not authenticated
            return redirect()->route('login')->withErrors('Anda harus login terlebih dahulu.');
        }
        $jurusan = $user->kategori_id;
        //filter berdasarkan jurusan 
        $itm = Item::where('kategori_jurusan_id', $jurusan);
        //Filter Search
        if ($request->has('search') && $request->search !== '') {
            $itm->where('nama_item', 'like', '%' . $request->search . '%');
        }
        //Filter jenis item
        if ($request->has('jenis') && $request->jenis !== '') {
            $itm->where('jenis_item', $request->jenis);
        }
        //pagination per halaman
        $items = $itm->with('kategori_jurusan')
            ->orderBy('created_at', 'desc')
            ->paginate(9)->withQueryString();


        ///Daftar jenis barang
        $jenis_items = Item::where('kategori_jurusan_id', $jurusan)
            ->select('jenis_item')
            ->distinct()
            ->pluck('jenis_item');

        // untuk waktu, kalau mau semua cukup ambil all(), atau paginate jika banyak
        $waktu = waktu_pembelajaran::orderBy('jam_ke')->get();
        $peminjaman = peminjaman::find($id);
        // kirim variabel sesuai nama yang digunakan blade (blade pakai $items & $waktu)
        return view('user.editpinjam', compact('items', 'waktu', 'jenis_items', 'peminjaman', 'jurusan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function update(Request $request, $id)
    {
        $peminjaman = Peminjaman::where('user_id', Auth::id())
            ->where('status_tujuan', 'Pending')
            ->findOrFail($id);

        $validated = $request->validate([
            'keperluan' => 'required|string|max:255',
            'item_id' => 'required|exists:item,id',
            'kode_unit' => 'nullable|string',
            'waktu_ids' => 'required|array|min:1',
            'waktu_ids.*' => 'string',
            'bukti' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',  // Nullable untuk update
        ], [
            'waktu_ids.required' => 'Pilih minimal 1 waktu pembelajaran',
            'waktu_ids.array' => 'Format waktu tidak valid',
        ]);

        DB::beginTransaction();

        try {
            // Upload bukti baru jika ada
            $path = $peminjaman->gambar_bukti;  // Gunakan yang lama jika tidak ada upload baru
            if ($request->hasFile('bukti')) {
                if ($path) {
                    Storage::disk('public')->delete($path);  // Hapus yang lama
                }
                $path = $request->file('bukti')->store('bukti_peminjaman', 'public');
            }

            // Validasi dan siapkan array JSON untuk jam_pembelajaran (sama seperti store)
            $jamPembelajaran = [];
            foreach ($validated['waktu_ids'] as $waktuJson) {
                $waktuData = json_decode($waktuJson, true);
                if (!is_array($waktuData) || !isset($waktuData['jam_ke'], $waktuData['start_time'], $waktuData['end_time'])) {
                    throw new \Exception('Format waktu tidak valid: ' . $waktuJson);
                }
                $waktu = waktu_pembelajaran::where('jam_ke', $waktuData['jam_ke'])
                    ->where('start_time', $waktuData['start_time'])
                    ->where('end_time', $waktuData['end_time'])
                    ->first();
                if (!$waktu) {
                    throw new \Exception('Waktu pembelajaran tidak ditemukan: Jam ' . $waktuData['jam_ke'] . ', ' . $waktuData['start_time'] . ' - ' . $waktuData['end_time']);
                }
                $jamPembelajaran[] = $waktuData;
            }

            // Update peminjaman
            $peminjaman->update([
                'keperluan' => $validated['keperluan'],
                'item_id' => $validated['item_id'],
                'gambar_bukti' => $path,
                'jam_pembelajaran' => json_encode($jamPembelajaran),
            ]);

            $item = item::findOrFail($id);
            $item->update([
                'status_item' => 'dipinjam'
            ]);

            // Log the update action
            ActivityLoggerService::logUpdated(
                'Peminjaman',
                $peminjaman->id,
                ['keperluan' => $peminjaman->getOriginal('keperluan'), 'item_id' => $peminjaman->getOriginal('item_id')],
                ['keperluan' => $validated['keperluan'], 'item_id' => $validated['item_id']]
            );

            DB::commit();

            return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            if ($path && $path !== $peminjaman->gambar_bukti) {
                Storage::disk('public')->delete($path);
            }
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function beranda()
    {
        $userId = Auth::id();
        $peminjaman = peminjaman::where("user_id", Auth::id())
            ->with(["item"])
            ->latest()
            ->paginate(10);


        $dipinjam = Peminjaman::where('user_id', $userId)
            ->where('status_pinjaman', 'dipinjam')
            ->count();

        $selesai = Peminjaman::where('user_id', $userId)
            ->where('status_pinjaman', 'selesai')
            ->count();


        return view('user.homepage', compact('peminjaman', 'dipinjam', 'selesai'));
    }

    public function destroy($id)
    {
        $pinjam = Peminjaman::findOrFail($id);

        // Jika ada file bukti, hapus juga
        if ($pinjam->bukti && Storage::exists($pinjam->bukti)) {
            Storage::delete($pinjam->bukti);
        }

        $pinjam->delete();

        return redirect()->back()->with('success', 'Peminjaman berhasil dihapus.');
    }
}


/**
 * Update the specified resource in storage.
 */

