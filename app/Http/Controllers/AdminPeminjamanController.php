<?php

namespace App\Http\Controllers;

use App\Models\peminjaman;
use App\Models\Kategori;
use App\Models\Item;
use App\Services\ActivityLoggerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminPeminjamanController extends Controller
{
    /**
     * Display a listing of the resource (Daftar semua peminjaman untuk admin).
     */
    public function dashboard()
    {
        //mengambil akun admin yang sedang login
        $admin = Auth::user();
        //mengambil kategori jurusan admin
        $adminKategoriId = $admin->kategori_id;

        // Filter notifikasi berdasarkan kategori_jurusan_id item
        $notifications = Peminjaman::with(['user', 'item.kategori_jurusan'])
            ->whereHas('item', function ($query) use ($adminKategoriId) {
                $query->where('kategori_jurusan_id', $adminKategoriId);
            })
            ->latest()
            ->get();

        // table peminjaman yang memiliki relasi item, dengan menggunakan function yang berisi parameter query, dan menggunakan variabel $adminKategoriId
        $totalPending = Peminjaman::whereHas('item', function ($query) use ($adminKategoriId) {
            //mengambil data kategori_jurusan_id yang sesuai dengan kategori admin
            $query->where('kategori_jurusan_id', $adminKategoriId);
        })//filter status tujuan
        ->where('status_tujuan', 'Pending')->count();

        $totalApproved = Peminjaman::whereHas('item', function ($query) use ($adminKategoriId) {
            $query->where('kategori_jurusan_id', $adminKategoriId);
        })->where('status_tujuan', 'Approved')->count();

        $totalRejected = Peminjaman::whereHas('item', function ($query) use ($adminKategoriId) {
            $query->where('kategori_jurusan_id', $adminKategoriId);
        })->where('status_tujuan', 'Rejected')->count();

        $totalDipinjam = Peminjaman::whereHas('item', function ($query) use ($adminKategoriId) {
            $query->where('kategori_jurusan_id', $adminKategoriId);
        })->where('status_pinjaman', 'dipinjam')->count();

        $totalDikembalikan = Peminjaman::whereHas('item', function ($query) use ($adminKategoriId) {
            $query->where('kategori_jurusan_id', $adminKategoriId);
        })->where('status_pinjaman', 'selesai')->count();


        //hitung item yang ada sesuai jurusan admin
        $totalItems = Item::where('kategori_jurusan_id', $adminKategoriId)->count();
        //menghitung total riwayat peminjaman
        $totalriwayat = $totalDipinjam + $totalDikembalikan;

        // Peminjaman terbaru (5 terakhir) berdasarkan kategori_jurusan_id item
        $recentPeminjaman = Peminjaman::with(['user', 'item'])
            ->whereHas('item', function ($query) use ($adminKategoriId) {
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
            ->whereHas('item', function ($q) use ($adminKategoriId) {
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

    public function index(Request $request)
    {
        $admin = Auth::user();
        $adminKategoriId = $admin->kategori_id;
        $item =

            $kategori = Kategori::all();
        $query = Peminjaman::with(['item.kategori_jurusan', 'user.kategori'])
            ->whereHas('item', function ($q) use ($adminKategoriId) {
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
        
        DB::beginTransaction();
        try {
            $item = Item::findOrFail($peminjaman->item_id);
            $item->update(['status_item' => 'dipinjam']);
            $peminjaman->update([
                'status_tujuan' => 'Approved',
                'approved_at' => now(),
                'status_pinjaman' => 'dipinjam'
            ]);
            ActivityLoggerService::logUpdated(
                'Peminjaman',
                $peminjaman->id,
                ['status_tujuan' => 'Pending', 'status_pinjaman' => 'pending'],
                ['status_tujuan' => 'Approved', 'status_pinjaman' => 'dipinjam']
            );
            $user = $peminjaman->user;
            if ($user) {
                $user->notify(new \App\Notifications\PeminjamanApprovedNotification($peminjaman));
            }
            DB::commit();
            return back()->with('success', 'Peminjaman berhasil disetujui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reject peminjaman.
     */
     public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan' => 'nullable|string|max:500',
        ]);
        
        $peminjaman = peminjaman::findOrFail($id);
        
        if ($peminjaman->status_tujuan !== 'Pending') {
            return back()->withErrors('Peminjaman sudah diproses.');
        }
        
        DB::beginTransaction();
        try {
            // Update peminjaman
            $peminjaman->update([
                'status_tujuan' => 'Rejected',
                'rejected_at' => now(),
                'alasan_reject' => $request->alasan
            ]);
            
            // Return item status
            $item = Item::find($peminjaman->item_id);
            if ($item) {
                $item->update(['status_item' => 'tersedia']);
            }
            
            // Log activity
            ActivityLoggerService::logUpdated(
                'Peminjaman',
                $peminjaman->id,
                ['status_tujuan' => 'Pending'],
                ['status_tujuan' => 'Rejected']
            );
            $user = $peminjaman->user;
            if ($user) {
                $user->notify(new \App\Notifications\PeminjamanRejectedNotification($peminjaman, $request->alasan));
            }
            
            DB::commit();
            
            return back()->with('success', 'Peminjaman ditolak.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Terjadi kesalahan: ' . $e->getMessage());
        }
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

            // Log the completion action
            ActivityLoggerService::logUpdated(
                'Peminjaman',
                $peminjaman->id,
                ['status_pinjaman' => 'dipinjam'],
                ['status_pinjaman' => 'selesai']
            );

            DB::commit();

            return back()->with('success', 'Barang berhasil dikembalikan dan status diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}