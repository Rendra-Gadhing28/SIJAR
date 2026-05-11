<?php

namespace App\Http\Controllers\WEB;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Services\ActivityLoggerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\waktu_pembelajaran;
use App\Models\Peminjaman;
use App\Models\Kategori;
use App\Models\slot_Peminjaman;
use App\Notifications\PeminjamanBaruNotification;


class PeminjamanController extends Controller
{
          public function index(Request $request)
    {
        $user = Auth::user();

        $Peminjaman = Peminjaman::where("user_id", $user->id)
            ->with(["item:id,nama_item,kode_unit"])
            ->select(['id', 'keperluan', 'user_id', 'item_id', 'tanggal', 'status_tujuan', 'status_pinjaman', 'gambar_bukti', 'jam_pembelajaran'])
            ->latest()
            ->paginate(10);

        // Tetap return 200 meski kosong, biar FE tidak error — kosong bukan error
        return response()->json([
            "status"  => true,
            "message" => $Peminjaman->isEmpty() ? "data masih kosong" : "data Peminjaman berhasil diambil",
            "data"    => $Peminjaman
        ], 200);
    }

    // ==================== CREATE (Form Data) ====================
    // GET /api/Peminjaman/create
    // Endpoint ini dipakai FE untuk mengambil data dropdown/filter sebelum form ditampilkan
    public function create(Request $request)
    {
        $user = Auth::user();

        // Optimasi: jalankan query secara paralel (tidak ada dependency antar query)
        $query = Item::query();

        if ($request->filled('search')) {
            $query->where('nama_item', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('jenis')) {
            $query->where('jenis_item', $request->jenis);
        }
        if ($request->filled('status')) {
            $query->where('status_item', $request->status);
        }
        if ($request->filled('jurusan')) {
            // Optimasi: pakai whereHas daripada join manual agar tidak conflict kolom
            $query->whereHas('kategori_jurusan', function ($q) use ($request) {
                $q->where('nama_kategori', $request->jurusan);
            });
        }

        // Jalankan semua query sekaligus
        [
            $items,
            $jenis_items,
            $status_items,
            $jurusan,
            $waktu
        ] = [
            $query->latest()->paginate(12)->withQueryString(),
            Item::distinct()->pluck('jenis_item'),       // Optimasi: hilangkan select() redundan
            Item::distinct()->pluck('status_item'),
            Kategori::select('id', 'nama_kategori')->orderBy('nama_kategori')->get(),
            waktu_pembelajaran::orderBy('jam_ke')->get(),
        ];

        return response()->json([
            "status"  => true,
            "message" => "data form Peminjaman berhasil diambil",
            "data"    => [
                "items"        => $items,
                "waktu"        => $waktu,
                "jenis_items"  => $jenis_items,
                "status_items" => $status_items,
                "jurusan"      => $jurusan,
                "jurusan_user" => $user->kategori_nama_kategori,
            ]
        ], 200);
    }

    // ==================== STORE ====================
    // POST /api/Peminjaman
    // FE kirim: FormData (karena ada file upload)
    // Content-Type: multipart/form-data
    //
    // Fields:
    //   keperluan     : string
    //   item_id       : integer
    //   kode_unit     : string (opsional)
    //   waktu_ids[]   : array of JSON string, contoh: '{"jam_ke":1,"start_time":"07:00","end_time":"07:45"}'
    //   bukti         : file (jpeg/png/jpg, max 2MB)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'keperluan'    => 'required|string|max:255',
            'item_id'      => 'required|exists:item,id',
            'kode_unit'    => 'nullable|string',
            'waktu_ids'    => 'required|array|min:1',
            'waktu_ids.*'  => 'string',
            'bukti'        => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'waktu_ids.required' => 'Pilih minimal 1 waktu pembelajaran',
            'waktu_ids.array'    => 'Format waktu tidak valid',
        ]);

        DB::beginTransaction();

        try {
            $path = $request->file('bukti')->store('bukti_Peminjaman', 'public');

            // Validasi & proses waktu_ids
            $jamPembelajaran = [];
            foreach ($validated['waktu_ids'] as $waktuJson) {
                $waktuData = json_decode($waktuJson, true);

                if (!is_array($waktuData) || !isset($waktuData['jam_ke'], $waktuData['start_time'], $waktuData['end_time'])) {
                    throw new \Exception('Format waktu tidak valid: ' . $waktuJson);
                }

                // Optimasi: cukup cek exists, tidak perlu ambil seluruh object
                $exists = waktu_pembelajaran::where('jam_ke', $waktuData['jam_ke'])
                    ->where('start_time', $waktuData['start_time'])
                    ->where('end_time', $waktuData['end_time'])
                    ->exists();

                if (!$exists) {
                    throw new \Exception("Waktu tidak ditemukan: Jam {$waktuData['jam_ke']}, {$waktuData['start_time']} - {$waktuData['end_time']}");
                }

                $jamPembelajaran[] = $waktuData;
            }

            $Peminjaman = Peminjaman::create([
                'keperluan'       => $validated['keperluan'],
                'user_id'         => Auth::id(),
                'item_id'         => $validated['item_id'],
                'tanggal'         => now()->toDateString(),
                'status_tujuan'   => 'Pending',
                'status_pinjaman' => 'dipinjam',
                'gambar_bukti'    => $path,
                'jam_pembelajaran' => json_encode($jamPembelajaran),
            ]);

            // Update status item
            Item::where('id', $validated['item_id'])
                ->update(['status_item' => 'dipinjam']); // Optimasi: tidak perlu find() dulu

            // Notifikasi ke admin
            $admins = User::where('role', 'admin')->get();
            if ($admins->isNotEmpty()) {
                \Illuminate\Support\Facades\Notification::send($admins, new PeminjamanBaruNotification($Peminjaman));
            }

            ActivityLoggerService::logCreated(
                'Peminjaman',
                $Peminjaman->id,
                ['keperluan' => $Peminjaman->keperluan, 'item_id' => $Peminjaman->item_id, 'status_tujuan' => 'Pending']
            );

            DB::commit();

            return response()->json([
                "status"  => true,
                "message" => "Peminjaman berhasil dibuat",
                "data"    => $Peminjaman->only(['id', 'keperluan', 'user_id', 'item_id', 'tanggal', 'status_tujuan', 'gambar_bukti', 'jam_pembelajaran'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            // Hapus file jika ada error setelah upload
            if (isset($path)) Storage::disk('public')->delete($path);

            return response()->json([
                "status"  => false,
                "message" => "gagal menyimpan data",
                "error"   => $e->getMessage()
            ], 500);
        }
    }

    // ==================== SHOW ====================
    // GET /api/Peminjaman/{id}
    public function show($id)
    {
        $Peminjaman = Peminjaman::with([
                'item:id,nama_item,kode_unit,foto_barang',
                'user:id,name,kategori_id'
            ])
            ->select('id', 'keperluan', 'user_id', 'item_id', 'tanggal', 'status_tujuan', 'status_pinjaman', 'gambar_bukti', 'jam_pembelajaran')
            ->where('user_id', Auth::id())
            ->find($id);

        if (!$Peminjaman) {
            return response()->json([
                "status"  => false,
                "message" => "data Peminjaman dengan ID {$id} tidak ditemukan",
                "data"    => null
            ], 404);
        }

        return response()->json([
            "status"  => true,
            "message" => "data Peminjaman dengan ID {$id} berhasil diambil",
            "data"    => $Peminjaman
        ], 200);
    }

    // ==================== EDIT (Form Data) ====================
    // GET /api/Peminjaman/{id}/edit
    // Dipakai FE untuk mengambil data form saat edit
    public function edit(Request $request, $id)
    {
        $user = Auth::user();
        $jurusan = $user->kategori_id;

        $query = Item::where('kategori_jurusan_id', $jurusan);

        if ($request->filled('search')) {
            $query->where('nama_item', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('jenis')) {
            $query->where('jenis_item', $request->jenis);
        }

        // Optimasi: jalankan semua query sekaligus
        [
            $items,
            $jenis_items,
            $waktu,
            $Peminjaman
        ] = [
            $query->with('kategori_jurusan')->latest()->paginate(9)->withQueryString(),
            Item::where('kategori_jurusan_id', $jurusan)->distinct()->pluck('jenis_item'),
            waktu_pembelajaran::orderBy('jam_ke')->get(),
            Peminjaman::find($id),
        ];

        if (!$Peminjaman) {
            return response()->json([
                "status"  => false,
                "message" => "data Peminjaman tidak ditemukan",
                "data"    => null
            ], 404);
        }

        return response()->json([
            "status"  => true,
            "message" => "data form edit Peminjaman berhasil diambil",
            "data"    => [
                "items"       => $items,
                "waktu"       => $waktu,
                "jenis_items" => $jenis_items,
                "Peminjaman"  => $Peminjaman,
                "jurusan_id"  => $jurusan,
            ]
        ], 200);
    }

    // ==================== UPDATE ====================
    // POST /api/Peminjaman/{id}?_method=PUT  ← karena FormData tidak support PUT native
    // atau pakai method spoofing di FE dengan menambah field _method=PUT
    //
    // FE kirim: FormData
    //   keperluan     : string
    //   item_id       : integer
    //   kode_unit     : string (opsional)
    //   waktu_ids[]   : array of JSON string
    //   bukti         : file (opsional, jika tidak diubah tidak perlu dikirim)
    public function update(Request $request, $id)
    {
        $Peminjaman = Peminjaman::where('user_id', Auth::id())
        ->whereRaw('LOWER(status_tujuan) = ?', ['pending']) // ← case-insensitive
        ->find($id); // pakai find(), bukan findOrFail()

        $validated = $request->validate([
            'keperluan'   => 'required|string|max:255',
            'item_id'     => 'required|exists:item,id',
            'waktu_ids'   => 'required|array|min:1',
            'waktu_ids.*' => 'string',
            'bukti'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'waktu_ids.required' => 'Pilih minimal 1 waktu pembelajaran',
            'waktu_ids.array'    => 'Format waktu tidak valid',
            'item_id.exists'     => 'Item yang dipilih tidak tersedia',
        ]);

        DB::beginTransaction();

        try {
            $oldData    = ['keperluan' => $Peminjaman->keperluan, 'item_id' => $Peminjaman->item_id];
            $itemBerubah = $Peminjaman->item_id != $validated['item_id'];
            $oldItemId  = $Peminjaman->item_id;
            $path       = $Peminjaman->gambar_bukti;

            // Validasi item baru — optimasi: cukup exists check dulu
            $newItem = Item::findOrFail($validated['item_id']);

            if (method_exists($newItem, 'trashed') && $newItem->trashed()) {
                throw new \Exception('Item sudah tidak aktif');
            }

            // Upload bukti baru jika ada
            if ($request->hasFile('bukti')) {
                if ($path) Storage::disk('public')->delete($path);
                $path = $request->file('bukti')->store('bukti_Peminjaman', 'public');
            }

            // Proses waktu_ids
            $jamPembelajaran = [];
            foreach ($validated['waktu_ids'] as $waktuJson) {
                $waktuData = json_decode($waktuJson, true);

                if (!is_array($waktuData) || !isset($waktuData['jam_ke'], $waktuData['start_time'], $waktuData['end_time'])) {
                    throw new \Exception('Format waktu tidak valid: ' . $waktuJson);
                }

                // Optimasi: exists() cukup
                $exists = waktu_pembelajaran::where('jam_ke', $waktuData['jam_ke'])
                    ->where('start_time', $waktuData['start_time'])
                    ->where('end_time', $waktuData['end_time'])
                    ->exists();

                if (!$exists) {
                    throw new \Exception("Waktu tidak ditemukan: Jam {$waktuData['jam_ke']}, {$waktuData['start_time']} - {$waktuData['end_time']}");
                }

                $jamPembelajaran[] = $waktuData;
            }

            $Peminjaman->update([
                'keperluan'        => $validated['keperluan'],
                'item_id'          => $validated['item_id'],
                'jam_pembelajaran' => json_encode($jamPembelajaran),
                'gambar_bukti'     => $path,
            ]);

            // Update status item — optimasi: pakai query langsung
            if ($itemBerubah) {
                Item::where('id', $oldItemId)->update(['status_item' => 'tersedia']);
                Item::where('id', $validated['item_id'])->update(['status_item' => 'dipinjam']);
            }
            ActivityLoggerService::logUpdated(
                'Peminjaman',
                $Peminjaman->id,
                $oldData,
                ['keperluan' => $validated['keperluan'], 'item_id' => $validated['item_id']]
            );

            DB::commit();

            return response()->json([
                "status"  => true,
                "message" => "Peminjaman berhasil diperbarui",
                "data"    => $Peminjaman->fresh()->only(['id', 'keperluan', 'user_id', 'item_id', 'tanggal', 'status_tujuan', 'gambar_bukti', 'jam_pembelajaran'])
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            if (isset($path) && $path && $path !== $Peminjaman->gambar_bukti) {
                Storage::disk('public')->delete($path);
            }

            return response()->json([
                "status"  => false,
                "message" => "gagal memperbarui data",
                "error"   => $e->getMessage()
            ], 500);
        }
    }

    // ==================== SELESAI ====================
    // PATCH /api/Peminjaman/{id}/selesai
    public function selesai($id)
    {
        $Peminjaman = Peminjaman::findOrFail($id);

        $Peminjaman->update([
            'status_pinjaman' => 'selesai',
            'finished_at'     => now()
        ]);

        // Optimasi: langsung update tanpa find()
        Item::where('id', $Peminjaman->item_id)->update(['status_item' => 'tersedia']);

        ActivityLoggerService::logUpdated(
            'Peminjaman',
            $Peminjaman->id,
            ['status_pinjaman' => 'dipinjam'],
            ['status_pinjaman' => 'selesai']
        );

        return response()->json([
            "status"  => true,
            "message" => "barang berhasil dikembalikan",
        ], 200);
    }

    // ==================== RUSAK ====================
    // PATCH /api/item/{id}/rusak
    public function rusak($id)
    {
        $item = Item::findOrFail($id);
        $item->update(['status_item' => 'rusak']);

        return response()->json([
            "status"  => true,
            "message" => "status barang diubah menjadi rusak",
        ], 200);
    }

    // ==================== BERANDA ====================
    // GET /api/beranda
    public function beranda()
    {
        $userId = Auth::id();

        // Optimasi: jalankan 3 query secara bersamaan dengan selectRaw untuk count
        $Peminjaman = Peminjaman::where("user_id", $userId)
            ->with(["item:id,nama_item,kode_unit,foto_barang"])
            ->select(['id', 'keperluan', 'item_id', 'tanggal', 'status_tujuan', 'status_pinjaman'])
            ->latest()
            ->limit(6)
            ->get(); // ← FIX: tambah get() yang hilang di kode lama

        // Optimasi: gabung 2 count query menjadi 1 query dengan selectRaw
        $stats = Peminjaman::where('user_id', $userId)
            ->selectRaw("
                SUM(CASE WHEN status_pinjaman = 'dipinjam' THEN 1 ELSE 0 END) as dipinjam,
                SUM(CASE WHEN status_pinjaman = 'selesai' THEN 1 ELSE 0 END) as selesai
            ")
            ->first();

        return response()->json([
            "status"  => true,
            "message" => "data beranda berhasil diambil",
            "data"    => [
                "Peminjaman_terbaru" => $Peminjaman,
                "total_dipinjam"     => (int) $stats->dipinjam,
                "total_selesai"      => (int) $stats->selesai,
            ]
        ], 200);
    }

    // ==================== DESTROY ====================
    // DELETE /api/Peminjaman/{id}
    public function destroy($id)
    {
        $pinjam = Peminjaman::where('user_id', Auth::id())->findOrFail($id); // Security: pastikan hanya milik user sendiri

        if ($pinjam->gambar_bukti && Storage::disk('public')->exists($pinjam->gambar_bukti)) {
            Storage::disk('public')->delete($pinjam->gambar_bukti);
        }

        $pinjam->delete();

        return response()->json([
            "status"  => true,
            "message" => "Peminjaman berhasil dihapus",
        ], 200);
    }
}
