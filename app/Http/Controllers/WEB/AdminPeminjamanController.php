<?php

namespace App\Http\Controllers\WEB;

use App\Models\peminjaman;
use App\Models\Kategori;
use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Services\ActivityLoggerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse; // <-- TAMBAHKAN USE SCRIPT INI

class AdminPeminjamanController extends Controller
{
    /**
     * Display a listing of the resource (Daftar semua peminjaman untuk admin).
     */
    public function dashboard(): JsonResponse
    {
        $admin = Auth::user();
        $adminKategoriId = $admin->kategori_id;

        // Base query
        $basePeminjaman = Peminjaman::whereHas('item', function ($query) use ($adminKategoriId) {
            $query->where('kategori_jurusan_id', $adminKategoriId);
        });

        // Ambil semua statistik dalam 1 query pakai aggregate
        $stats = (clone $basePeminjaman)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status_tujuan = 'Pending' THEN 1 ELSE 0 END) as total_pending,
                SUM(CASE WHEN status_tujuan = 'Approved' THEN 1 ELSE 0 END) as total_approved,
                SUM(CASE WHEN status_tujuan = 'Rejected' THEN 1 ELSE 0 END) as total_rejected,
                SUM(CASE WHEN status_pinjaman = 'dipinjam' THEN 1 ELSE 0 END) as total_dipinjam,
                SUM(CASE WHEN status_pinjaman = 'selesai' THEN 1 ELSE 0 END) as total_dikembalikan
            ")
            ->first();

        // Notifikasi
        $notifications = (clone $basePeminjaman)
            ->with([
                'user:id,name,kategori_id',
                'item:id,nama_item,kategori_jurusan_id',
                'item.kategoriJurusan:id,nama_kategori'
            ])
            ->latest()
            ->take(10)
            ->get()
            ->only(['id', 'keperluan', 'status_tujuan', 'user', 'item']);

        // Recent peminjaman
        $recentPeminjaman = (clone $basePeminjaman)
            ->with([
                'user:id,name,kategori_id',
                'item:id,nama_item,kode_unit,foto_barang'
            ])
            ->latest()
            ->take(5)
            ->get();

        // Total item jurusan
        $totalItems = Item::where('kategori_jurusan_id', $adminKategoriId)->count();

        return response()->json([
            "status" => true,
            "message" => "Data dashboard berhasil diambil",
            "data" => [
                "statistik" => [
                    "total_pending"      => $stats->total_pending,
                    "total_approved"     => $stats->total_approved,
                    "total_rejected"     => $stats->total_rejected,
                    "total_dipinjam"     => $stats->total_dipinjam,
                    "total_dikembalikan" => $stats->total_dikembalikan,
                    "total_riwayat"      => $stats->total_dipinjam + $stats->total_dikembalikan,
                    "total_items"        => $totalItems,
                ],
                "notifications"     => $notifications,
                "recent_peminjaman" => $recentPeminjaman,
            ]
        ], 200);
    }

    public function riwayat(Request $request): JsonResponse
    {
        $admin = Auth::user();
        $adminKategoriId = $admin->kategori_id;

        $query = Peminjaman::with(['item.kategori_jurusan', 'user.kategori'])
            ->whereHas('item', function ($q) use ($adminKategoriId) {
                $q->where('kategori_id', $adminKategoriId);
            });

        if ($request->filled('kelas')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('kelas', $request->kelas);
            });
        }

        if ($request->filled('status_tujuan')) {
            $query->where('status_tujuan', $request->status_tujuan);
        }

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

        $kategori = Kategori::lazy();
        $kelasList = ['X', 'XI', 'XII'];
        $dataLengkap = [$peminjaman, $kategori, $kelasList];

        return response()->json([
            "status" => true,
            "message" => "data berhasil diambil",
            "data" => $dataLengkap,
        ], 200);
    }

    public function createBarang(): JsonResponse
    {
        $kategori = Kategori::lazy();
        return response()->json([
            "status" => true,
            "message" => "membuat barang jurusan",
            "data" => $kategori,
        ], 201);
    }

    public function index(Request $request): JsonResponse
    {
        $admin = Auth::user();
        $adminKategoriId = $admin->kategori_id;

        if (!$adminKategoriId) {
            return response()->json([
                "status"  => false,
                "message" => "Admin tidak memiliki kategori_id",
                "debug"   => $admin,
            ], 422);
        }

        $kategori = Kategori::find($adminKategoriId);

        if (!$kategori) {
            return response()->json([
                "status"  => false,
                "message" => "Kategori dengan id {$adminKategoriId} tidak ditemukan",
            ], 404);
        }

        $totalTanpaFilter = Peminjaman::count();
        $totalDenganFilter = Peminjaman::whereHas('item', function ($q) use ($adminKategoriId) {
            $q->where('kategori_jurusan_id', $adminKategoriId);
        })->count();

        $query = Peminjaman::with(['item.kategoriJurusan', 'user.kategori'])
            ->whereHas('item', function ($q) use ($adminKategoriId) {
                $q->where('kategori_jurusan_id', $adminKategoriId);
            });

        if ($request->filled('kelas')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('kelas', $request->kelas);
            });
        }

        if ($request->filled('status_tujuan')) {
            $query->where('status_tujuan', $request->status_tujuan);
        }

        if ($request->filled('kategori_id')) {
            $query->whereHas('user.kategori', function ($q) use ($request) {
                $q->where('id', $request->kategori_id);
            });
        }

        if ($request->filled('nama_jurusan')) {
            $query->whereHas('user.kategori', function ($q) use ($request) {
                $q->where('nama_kategori', 'like', '%' . $request->nama_jurusan . '%');
            });
        }

        $peminjaman = $query->latest()
            ->paginate(10)
            ->appends($request->all());

        $kelasList = ['X', 'XI', 'XII'];
        $semuaKategori = Kategori::all(['id', 'nama_kategori']);

        return response()->json([
            "status"  => true,
            "message" => "Data berhasil diambil",
            "debug"   => [
                "admin_kategori_id"   => $adminKategoriId,
                "total_tanpa_filter"  => $totalTanpaFilter,
                "total_dengan_filter" => $totalDenganFilter,
            ],
            "data"    => [
                "peminjaman"     => $peminjaman,
                "kategori_admin" => $kategori,
                "semua_kategori" => $semuaKategori,
                "kelas_list"     => $kelasList,
            ],
        ], 200);
    }

    public function approve($id): JsonResponse
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        // Cek strtolower/case agar validasi string tidak sensitif huruf kapital
        if (strtolower($peminjaman->status_tujuan) !== 'pending') {
            return response()->json([
                "status"  => false,
                "message" => "Peminjaman sudah diproses sebelumnya."
            ], 422);
        }

        DB::beginTransaction();
        try {
            $item = Item::findOrFail($peminjaman->item_id);
            $item->update(['status_item' => 'dipinjam']);
            
            $peminjaman->update([
                'status_tujuan' => 'approved',
                'approved_at' => now(),
                'status_pinjaman' => 'dipinjam'
            ]);

            ActivityLoggerService::logUpdated(
                'Peminjaman',
                $peminjaman->id,
                ['status_tujuan' => 'pending', 'status_pinjaman' => 'pending'],
                ['status_tujuan' => 'approved', 'status_pinjaman' => 'dipinjam']
            );

            $user = $peminjaman->user;
            if ($user) {
                $user->notify(new \App\Notifications\PeminjamanApprovedNotification($peminjaman));
            }

            DB::commit();
            return response()->json([
                "status"  => true,
                "message" => "Peminjaman berhasil disetujui."
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "status"  => false,
                "message" => "Terjadi kesalahan backend: " . $e->getMessage()
            ], 500);
        }
    }

    public function reject(Request $request, $id): JsonResponse
    {
        $request->validate([
            'alasan' => 'nullable|string|max:500',
        ]);
        
        $peminjaman = Peminjaman::findOrFail($id);
        
        if (strtolower($peminjaman->status_tujuan) !== 'pending') {
            return response()->json([
                "status"  => false,
                "message" => "Peminjaman sudah diproses."
            ], 422);
        }
        
        DB::beginTransaction();
        try {
            $peminjaman->update([
                'status_tujuan' => 'rejected',
                'rejected_at' => now(),
                'alasan_reject' => $request->alasan
            ]);
            
            $item = Item::find($peminjaman->item_id);
            if ($item) {
                $item->update(['status_item' => 'tersedia']);
            }
            
            ActivityLoggerService::logUpdated(
                'Peminjaman',
                $peminjaman->id,
                ['status_tujuan' => 'pending'],
                ['status_tujuan' => 'rejected']
            );

            $user = $peminjaman->user;
            if ($user) {
                $user->notify(new \App\Notifications\PeminjamanRejectedNotification($peminjaman, $request->alasan));
            }
            
            DB::commit();
            return response()->json([
                "status"  => true,
                "message" => "Peminjaman berhasil ditolak."
            ], 200);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "status"  => false,
                "message" => "Terjadi kesalahan backend: " . $e->getMessage()
            ], 500);
        }
    }

    public function selesai($id): JsonResponse
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if (strtolower($peminjaman->status_tujuan) !== 'approved') {
            return response()->json([
                "status"  => false,
                "message" => "Peminjaman belum disetujui, tidak bisa diselesaikan!"
            ], 422);
        }

        DB::beginTransaction();
        try {
            $peminjaman->update([
                'status_pinjaman' => 'selesai',
                'finished_at' => now(),
            ]);

            $item = Item::find($peminjaman->item_id);
            if ($item) {
                $item->update(['status_item' => 'tersedia']);
            }

            ActivityLoggerService::logUpdated(
                'Peminjaman',
                $peminjaman->id,
                ['status_pinjaman' => 'dipinjam'],
                ['status_pinjaman' => 'selesai']
            );

            DB::commit();
            return response()->json([
                "status"  => true,
                "message" => "Barang berhasil dikembalikan dan status diperbarui!"
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "status"  => false,
                "message" => "Terjadi kesalahan backend: " . $e->getMessage()
            ], 500);
        }
    }
}