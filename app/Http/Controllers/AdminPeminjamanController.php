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
        $notifications = peminjaman::get();
        $totalPending = peminjaman::where('status_tujuan', 'Pending')->count();
        $totalApproved = peminjaman::where('status_tujuan', 'Approved')->count();
        $totalRejected = peminjaman::where('status_tujuan', 'Rejected')->count();
        $totalItems = Item::count();
        $itemTersedia = Item::where('status_item', 'tersedia')->count();
        
        // Peminjaman terbaru (5 terakhir)
        $recentPeminjaman = peminjaman::with(['user', 'item'])
            ->latest()
            ->take(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'notifications',
            'totalPending',
            'totalApproved',
            'totalRejected',
            'totalItems',
            'itemTersedia',
            'recentPeminjaman'
        ));
    }
     public function riwayat(Request $request)
    {
        $query = peminjaman::with(['item', 'user']);
        
        // Filter berdasarkan kategori_jurusan (jika dipilih)
        if ($request->has('kategori_jurusan') && $request->kategori_jurusan !== '') {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('kategori_id', $request->kategori_jurusan);
            });
        }
        
        // Filter status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status_tujuan', $request->status);
        }
        
        $peminjaman = $query->latest()->paginate(10);
        $kategori = Kategori::all();  // Untuk dropdown filter jurusan
        
        return view('admin.riwayat', compact('peminjaman', 'kategori'));
    }
    public function createBarang()
    {
        $kategori = Kategori::all();  // Untuk dropdown kategori
        return view('admin.barang.create', compact('kategori'));
    }

    public function storeBarang(Request $request)
    {
        $validated = $request->validate([
            'nama_item' => 'required|string|max:255',
            'jenis_item' => 'required|string|max:255',
            'kode_unit' => 'required|string|max:255',
            'kategori_jurusan_id' => 'required|exists:kategori,id',
            'foto_barang' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'status_item' => 'required|in:tersedia,tidak_tersedia',
        ]);
        // Upload foto
        $path = $request->file('foto_barang')->store('items', 'public');
        
        Item::create([
            'nama_item' => $validated['nama_item'],
            'jenis_item' => $validated['jenis_item'],
            'kode_unit' => $validated['kode_unit'],
            'kategori_jurusan_id' => $validated['kategori_jurusan_id'],
            'foto_barang' => $path,
            'status_item' => $validated['status_item'],
        ]);
        return redirect()->route('admin.dashboard')->with('success', 'Barang berhasil ditambahkan.');
    }

   public function index()
    {
        $peminjaman = peminjaman::with(['user', 'item', 'slot_peminjaman.waktu'])
            ->latest()
            ->get();
        
        return view('admin.peminjaman.index', compact('peminjaman'));
    }

    /**
     * Display the specified resource (Detail peminjaman).
     */
 public function show($id)
    {
        $peminjaman = peminjaman::with(['user', 'item', 'slot_peminjaman.waktu'])
            ->findOrFail($id);
        
        return view('admin.peminjaman.show', compact('peminjaman'));
    }


    /**
     * Approve peminjaman.
     */
    public function approve($id)
    {
        $peminjaman = peminjaman::findOrFail($id);

        if ($peminjaman->status_tujuan !== 'Pending') {
            return back()->withErrors('Peminjaman sudah diproses.');
        }

        $peminjaman->update([
            'status_tujuan' => 'Approved',
            'approved_at' => now(),
            'status_pinjaman' => 'dipinjam'
        ]);
          $peminjaman->user->notify(new PeminjamanApprovedNotification($peminjaman));

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
            $item->update(['status' => 'tersedia']);

             $peminjaman->user->notify(new PeminjamanRejectedNotification($peminjaman));

                DB::commit();

        // Jika ada alasan, simpan di kolom tambahan (misalnya tambah kolom 'alasan_reject' di migrasi jika perlu)
        // $peminjaman->update(['alasan_reject' => $request->alasan]);

        return back()->with('success', 'Peminjaman ditolak. Item dikembalikan ke status tersedia.');
    }

     public function notifications()
    {
        $user = Auth::user()->get();
        $notifications = $user->where('role','admim')
            ->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.notifikasi', compact('notifications'));
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
                $item->update(['status' => 'tersedia']);
            }

            DB::commit();

            return back()->with('success', 'Barang berhasil dikembalikan dan status diperbarui!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }


    // Tambahkan metode lain jika perlu, seperti destroy untuk hapus peminjaman
}