<?php

namespace App\Http\Controllers\Admin;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Exports\PeminjamanExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class UnduLaporan extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    // GET /api/v1/admin/laporan/data
    // Query params (opsional): start_date, end_date  (format: Y-m-d)
    // ─────────────────────────────────────────────────────────────────────────
    public function getData(Request $req)
    {
        $startDate = $req->input('start_date');
        $endDate   = $req->input('end_date');

        // ── Base query ────────────────────────────────────────────────────────
        $base = Peminjaman::with([
            'user.jurusan',          // → Jurusan.nama_jurusan
            'item.kategoriJurusan',  // → Kategori.nama_kategori (tabel: kategori_jurusan)
        ]);

        if ($startDate && $endDate) {
            $base->whereBetween('tanggal', [$startDate, $endDate]);
        }

        $all = $base->orderBy('tanggal', 'asc')->get();

        // ── 1. SUMMARY ────────────────────────────────────────────────────────
        $totalPeminjaman = $all->count();
        $dikembalikan    = $all->where('status_pinjaman', 'selesai')->count();
        $terlambat       = $all->where('status_pinjaman', 'telat')->count();
        $siswaAktif      = $all->pluck('user_id')->unique()->count();
        $jenisBarang     = $all->pluck('item_id')->unique()->count();
        $rasioKembali    = $totalPeminjaman > 0
            ? round(($dikembalikan / $totalPeminjaman) * 100) . '%'
            : '0%';

        $summary = [
            'totalPeminjaman' => $totalPeminjaman,
            'dikembalikan'    => $dikembalikan,
            'terlambat'       => $terlambat,
            'siswaAktif'      => $siswaAktif,
            'jenisBarang'     => $jenisBarang,
            'rasioKembali'    => $rasioKembali,
            // Label trend — bisa dikembangkan: bandingkan ke periode sebelumnya
            'trendPeminjaman' => 'Total',
            'trendKembali'    => 'Selesai',
            'trendTelat'      => 'Terlambat',
            'trendSiswa'      => 'Unik',
            'trendBarang'     => 'Jenis',
            'trendRasio'      => 'Tepat waktu',
        ];

        // ── 2. MONTHLY — rekap per bulan ──────────────────────────────────────
        $bulanId = [
            1=>'Jan', 2=>'Feb', 3=>'Mar', 4=>'Apr', 5=>'Mei', 6=>'Jun',
            7=>'Jul', 8=>'Agu', 9=>'Sep', 10=>'Okt', 11=>'Nov', 12=>'Des',
        ];

        $monthly = $all
            ->groupBy(fn($p) => Carbon::parse($p->tanggal)->format('Y-m'))
            ->map(function ($group, $key) use ($bulanId) {
                $dt      = Carbon::createFromFormat('Y-m', $key);
                $pinjam  = $group->count();
                $kembali = $group->where('status_pinjaman', 'selesai')->count();
                $telat   = $group->where('status_pinjaman', 'telat')->count();
                $aktif   = $group->where('status_pinjaman', 'dipinjam')->count();

                return [
                    'bulan'   => ($bulanId[$dt->month] ?? $dt->format('M')) . ' ' . $dt->year,
                    'pinjam'  => $pinjam,
                    'kembali' => $kembali,
                    'telat'   => $telat,
                    'aktif'   => $aktif,
                ];
            })
            ->values();

        // ── 3. JURUSAN — rekap per jurusan ───────────────────────────────────
        $jurusan = $all
            ->groupBy(fn($p) => $p->user?->jurusan?->nama_jurusan ?? 'Tidak Diketahui')
            ->map(function ($group, $nama) {
                $total   = $group->count();
                $kembali = $group->where('status_pinjaman', 'selesai')->count();
                $telat   = $group->where('status_pinjaman', 'telat')->count();
                $aktif   = $group->where('status_pinjaman', 'dipinjam')->count();

                return [
                    'jurusan' => $nama,
                    'total'   => $total,
                    'kembali' => $kembali,
                    'telat'   => $telat,
                    'aktif'   => $aktif,
                ];
            })
            ->sortByDesc('total')
            ->values();

        // ── 4. TOP BARANG — barang paling sering dipinjam ────────────────────
        $topBarang = $all
            ->groupBy(fn($p) => $p->item_id)
            ->map(function ($group) {
                $first    = $group->first();
                $kategori = $first->item?->kategoriJurusan?->nama_kategori ?? '-';
                $nama     = $first->item?->nama_item ?? 'Item #' . $first->item_id;

                return [
                    'nama'     => $nama,
                    'kategori' => $kategori,
                    'count'    => $group->count(),
                ];
            })
            ->sortByDesc('count')
            ->take(10)
            ->values();

        // ── 5. KATEGORI — distribusi per kategori barang ─────────────────────
        $kategori = $all
            ->groupBy(fn($p) => $p->item?->kategoriJurusan?->nama_kategori ?? 'Lainnya')
            ->map(function ($group, $nama) {
                return [
                    'kategori' => $nama,
                    'total'    => $group->count(),
                ];
            })
            ->sortByDesc('total')
            ->values();

        // ── 6. RECENT — 10 peminjaman terbaru ────────────────────────────────
        $recent = $all
            ->sortByDesc('tanggal')
            ->take(10)
            ->map(fn($p) => [
                'id'              => $p->id,
                'nama_siswa'      => $p->user?->name ?? '-',
                'jurusan'         => $p->user?->jurusan?->nama_jurusan ?? '-',
                'nama_barang'     => $p->item?->nama_item ?? '-',
                'kategori'        => $p->item?->kategoriJurusan?->nama_kategori ?? '-',
                'tanggal'         => $p->tanggal?->format('d M Y') ?? '-',
                'status_pinjaman' => $p->status_pinjaman_formatted,
                'status_tujuan'   => $p->status_tujuan_formatted,
                'keperluan'       => $p->keperluan,
            ])
            ->values();

        // ── Response ─────────────────────────────────────────────────────────
        return response()->json([
            'success' => true,
            'message' => 'Data laporan berhasil dimuat.',
            'filter'  => [
                'start_date' => $startDate,
                'end_date'   => $endDate,
            ],
            'data' => [
                'summary'   => $summary,
                'monthly'   => $monthly,
                'jurusan'   => $jurusan,
                'topBarang' => $topBarang,
                'kategori'  => $kategori,
                'recent'    => $recent,
            ],
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // GET /api/v1/admin/laporan/excel
    // Query params (opsional): start_date, end_date
    // ─────────────────────────────────────────────────────────────────────────
    public function exportExcel(Request $req)
    {
        $startDate = $req->input('start_date');
        $endDate   = $req->input('end_date');

        $filename = 'laporan-peminjaman';
        if ($startDate && $endDate) {
            $filename .= "_{$startDate}_sd_{$endDate}";
        }
        $filename .= '.xlsx';

        return Excel::download(new PeminjamanExport($startDate, $endDate), $filename);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // GET /api/v1/admin/laporan/pdf
    // Query params (opsional): start_date, end_date
    // ─────────────────────────────────────────────────────────────────────────
    public function exportPDF(Request $req)
    {
        $startDate = $req->input('start_date');
        $endDate   = $req->input('end_date');

        $q = Peminjaman::with([
            'user.jurusan',
            'item.kategoriJurusan',
        ]);

        if ($startDate && $endDate) {
            $q->whereBetween('tanggal', [$startDate, $endDate]);
        }

        // FIX: orderBy yang benar — dua kolom terpisah, bukan satu orderBy dengan dua param
        $peminjaman = $q->orderBy('tanggal', 'asc')
                        ->orderBy('keperluan', 'asc')
                        ->get();

        $filename = 'laporan-peminjaman';
        if ($startDate && $endDate) {
            $filename .= "_{$startDate}_sd_{$endDate}";
        }
        $filename .= '.pdf';

        $pdf = Pdf::loadView('pdf.laporan-peminjaman', [
            'peminjaman' => $peminjaman,
            'start_date' => $startDate,
            'end_date'   => $endDate,
        ]);

        return $pdf->download($filename);
    }
}