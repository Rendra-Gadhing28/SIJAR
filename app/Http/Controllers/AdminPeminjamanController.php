<?php

namespace App\Http\Controllers;

use App\Models\peminjaman;
use App\Models\Kategori;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\PeminjamanBaruNotification;
use App\Notifications\PeminjamanApprovedNotification;
use App\Notifications\PeminjamanRejectedNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;

class AdminPeminjamanController extends Controller
{
    /**
     * Display a listing of the resource (Daftar semua peminjaman untuk admin).
     */
   public function dashboard()
    {
        $admin = Auth::user();
        $adminKategoriId = $admin->kategori_id;
        
        // Filter notifikasi: hanya peminjaman yang kategori_jurusan_id item-nya sama dengan kategori admin
        $notifications = Peminjaman::with(['user', 'item.kategori_jurusan'])
            ->whereHas('item', function($query) use ($adminKategoriId) {
                $query->where('kategori_jurusan_id', $adminKategoriId);
            })
            ->latest()
            ->get();
        
        // Filter statistik berdasarkan kategori_jurusan_id item
        $totalPending = Peminjaman::whereHas('item', function($query) use ($adminKategoriId) {
            $query->where('kategori_jurusan_id', $adminKategoriId);
        })->where('status_tujuan', 'Pending')->count();
        
        $totalApproved = Peminjaman::whereHas('item', function($query) use ($adminKategoriId) {
            $query->where('kategori_jurusan_id', $adminKategoriId);
        })->where('status_tujuan', 'Approved')->count();
        
        $totalRejected = Peminjaman::whereHas('item', function($query) use ($adminKategoriId) {
            $query->where('kategori_jurusan_id', $adminKategoriId);
        })->where('status_tujuan', 'Rejected')->count();
        
        $totalDipinjam = Peminjaman::whereHas('item', function($query) use ($adminKategoriId) {
            $query->where('kategori_jurusan_id', $adminKategoriId);
        })->where('status_pinjaman', 'dipinjam')->count();
        
        $totalDikembalikan = Peminjaman::whereHas('item', function($query) use ($adminKategoriId) {
            $query->where('kategori_jurusan_id', $adminKategoriId);
        })->where('status_pinjaman', 'selesai')->count();
        
        $totalItems = Item::where('kategori_jurusan_id', $adminKategoriId)->count();
        $totalriwayat = $totalDipinjam + $totalDikembalikan;
        
        // Peminjaman terbaru (5 terakhir) berdasarkan kategori_jurusan_id item
        $recentPeminjaman = Peminjaman::with(['user', 'item'])
            ->whereHas('item', function($query) use ($adminKategoriId) {
                $query->where('kategori_jurusan_id', $adminKategoriId);
            })
            ->latest()
            ->take(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'notifications',
            'totalPending',
            'totalApproved',
            'totalRejected',
            'totalItems',
            'recentPeminjaman',
            'totalDipinjam',
            'totalDikembalikan',
            'totalriwayat'
        ));
    }

    public function riwayat(Request $request)
    {
        $admin = Auth::user();
        $adminKategoriId = $admin->kategori_id;
        
        $query = Peminjaman::with(['item.kategori_jurusan', 'user.kategori'])
            ->whereHas('item', function($q) use ($adminKategoriId) {
                $q->where('kategori_jurusan_id', $adminKategoriId);
            });

        // Filter berdasarkan kelas (X, XI, XII)
        if ($request->filled('kelas')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('kelas', $request->kelas);
            });
        }

        // Filter status pinjaman (status_tujuan)
        if ($request->filled('status_tujuan')) {
            $query->where('status_tujuan', $request->status_tujuan);
        }

        // Filter search (nama peminjam / nama item / kode unit)
        if ($request->filled('search')) {
            $keyword = $request->search;

            $query->where(function ($q) use ($keyword) {
                $q->whereHas('user', function ($u) use ($keyword) {
                    $u->where('name', 'LIKE', "%$keyword%");
                })
                ->orWhereHas('item', function ($i) use ($keyword) {
                    $i->where('nama_item', 'LIKE', "%$keyword%")
                      ->orWhere('kode_unit', 'LIKE', "%$keyword%");
                });
            });
        }

        $peminjaman = $query->latest()
            ->paginate(10)
            ->appends($request->all());

        $kategori = Kategori::all();
        
        // Data kelas untuk dropdown
        $kelasList = ['X', 'XI', 'XII'];

        return view('admin.riwayat', compact('peminjaman', 'kategori', 'kelasList'));
    }

    public function createBarang()
    {
        $kategori = Kategori::all();
        return view('admin.barang.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_item' => 'required|string|max:255',
            'jenis_item' => 'required|string|max:255',
            'kategori_jurusan_id' => 'required|exists:kategori,id',
            'foto_barang' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $kategori = Kategori::find($validated['kategori_jurusan_id']);
        $prefix = strtoupper(substr($kategori->nama_kategori, 0, 3));

        $lastItem = Item::where('kategori_jurusan_id', $validated['kategori_jurusan_id'])
                        ->where('kode_unit', 'like', $prefix . '%')
                        ->orderBy('kode_unit', 'desc')
                        ->first();

        if ($lastItem) {
            $lastNumber = (int) substr($lastItem->kode_unit, strlen($prefix));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $kodeUnit = $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        $path = $request->file('foto_barang')->store('items', 'public');

        Item::create([
            'nama_item' => $validated['nama_item'],
            'jenis_item' => $validated['jenis_item'],
            'kode_unit' => $kodeUnit,
            'kategori_jurusan_id' => $validated['kategori_jurusan_id'],
            'foto_barang' => $path,
            'status_item' => 'tersedia',
        ]);   
        
        return redirect()->route('admin.barang.index')
            ->with('success', 'Barang berhasil ditambahkan dengan kode: ' . $kodeUnit);
    }

    public function index(Request $request)
    {
        $admin = Auth::user();
        $adminKategoriId = $admin->kategori_id;
        $item = 
        
        $kategori = Kategori::all();
        $query = Peminjaman::with(['item.kategori_jurusan', 'user.kategori'])
            ->whereHas('item', function($q) use ($adminKategoriId) {
                $q->where('kategori_jurusan_id', $adminKategoriId);
            });

        // Filter berdasarkan kelas
        if ($request->filled('kelas')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('kelas', $request->kelas);
            });
        }

        // Filter berdasarkan status
        if ($request->filled('status_tujuan')) {
            $query->where('status_tujuan', $request->status_tujuan);
        }

        $peminjaman = $query->latest()
                            ->paginate(10)
                            ->appends($request->all());

        $kelasList = ['X', 'XI', 'XII'];

        return view('admin.riwayat', compact('peminjaman', 'kategori', 'kelasList'));
    }

     public function approve($id)
    {
        $peminjaman = peminjaman::findOrFail($id);

        if ($peminjaman->status_tujuan !== 'Pending') {
            return back()->withErrors('Peminjaman sudah diproses.');
        }

         $item = Item::findOrFail($peminjaman->item_id);
         // update status item
    $item->update([
        'status_item' => 'dipinjam'
    ]);

        $peminjaman->update([
            'status_tujuan' => 'Approved',
            'approved_at' => now(),
            'status_pinjaman' => 'dipinjam'
        ]);
        

        return back()->with('success', 'Peminjaman berhasil disetujui.');
    }

    /**
     * Reject peminjaman.
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan' => 'nullable|string|max:500',  // Opsional: alasan reject
        ]);

        $peminjaman = peminjaman::findOrFail($id);

        if ($peminjaman->status_tujuan !== 'Pending') {
            return back()->withErrors('Peminjaman sudah diproses.');
        }

        $peminjaman->update([
            'status_tujuan' => 'Rejected',
            'rejected_at' => now(),
        ]);

        // Kembalikan status item
            $item = Item::find($peminjaman->item_id);
            $item->update(['status_item' => 'tersedia']);

                DB::commit();

        // Jika ada alasan, simpan di kolom tambahan (misalnya tambah kolom 'alasan_reject' di migrasi jika perlu)
        // $peminjaman->update(['alasan_reject' => $request->alasan]);

        return back()->with('success', 'Peminjaman ditolak. Item dikembalikan ke status tersedia.');
    }

     public function notifications()
{
    $user = Auth::user();
    
    // Ambil notifications langsung dari user
    $notifications = $user->notifications()
        ->orderBy('created_at', 'desc')
        ->paginate(15);
    
    return view('admin.Notifikasi', compact('notifications'));
}   

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        
        if ($notification) {
            $notification->markAsRead();
        }

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

     public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    public function selesai($id)
    {
        $peminjaman = peminjaman::findOrFail($id);

        // Validasi: hanya bisa selesai jika status Approved
        if ($peminjaman->status_tujuan !== 'Approved') {
            return back()->withErrors(['error' => 'Peminjaman belum disetujui!']);
        }

        DB::beginTransaction();
        
        try {
            // Update peminjaman
            $peminjaman->update([
                'status_pinjaman' => 'selesai',
                'finished_at' => now(),
            ]);

            // Kembalikan status item
            $item = Item::find($peminjaman->item_id);
            if ($item) {
                $item->update(['status_item' => 'tersedia']);
            }

            DB::commit();

            return back()->with('success', 'Barang berhasil dikembalikan dan status diperbarui!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}