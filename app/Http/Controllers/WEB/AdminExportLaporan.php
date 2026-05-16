<?php

namespace App\Http\Controllers\WEB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Peminjaman;
use App\Models\Item;
use App\Models\User;
use App\Exports\PeminjamanExport;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class AdminExportLaporan extends Controller
{
      public function getPeminjamanPerBulan(Request $request)
    {
        try {
            // Jika ada request tanggal, gunakan itu, jika tidak mulai dari hari ini
            $startDate = $request->start_date 
                ? Carbon::parse($request->start_date)->startOfDay() 
                : Carbon::today()->startOfDay();
            
            $endDate = $request->end_date 
                ? Carbon::parse($request->end_date)->endOfDay() 
                : $startDate->copy()->addMonth()->endOfDay();
            
            // Ambil semua peminjaman dalam rentang tanggal
            $peminjaman = Peminjaman::with(['user', 'item'])
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->orderBy('tanggal', 'asc')
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Data statistik
            $statistics = [
                'total' => $peminjaman->count(),
                'dipinjam' => $peminjaman->where('status_pinjaman', 'dipinjam')->count(),
                'selesai' => $peminjaman->where('status_pinjaman', 'selesai')->count(),
                'telat' => $peminjaman->where('status_pinjaman', 'telat')->count(),
                'pending' => $peminjaman->where('status_tujuan', 'pending')->count(),
                'approved' => $peminjaman->where('status_tujuan', 'approved')->count(),
                'rejected' => $peminjaman->where('status_tujuan', 'rejected')->count(),
            ];
            
            // Kelompokkan berdasarkan tanggal
            $groupedByDate = $peminjaman->groupBy(function($item) {
                return Carbon::parse($item->tanggal)->format('Y-m-d');
            });
            
            return response()->json([
                'success' => true,
                'data' => [
                    'periode' => [
                        'start_date' => $startDate->format('Y-m-d'),
                        'end_date' => $endDate->format('Y-m-d'),
                    ],
                    'statistics' => $statistics,
                    'peminjaman' => $peminjaman,
                    'grouped_by_date' => $groupedByDate,
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data peminjaman',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Mendapatkan riwayat peminjaman berdasarkan user atau semua
     */
    public function getRiwayatPeminjaman(Request $request)
    {
        try {
            $query = Peminjaman::with(['user', 'item']);
            
            // Filter berdasarkan user_id jika ada
            if ($request->user_id) {
                $query->where('user_id', $request->user_id);
            }
            
            // Filter berdasarkan status
            if ($request->status_pinjaman) {
                $query->where('status_pinjaman', $request->status_pinjaman);
            }
            
            if ($request->status_tujuan) {
                $query->where('status_tujuan', $request->status_tujuan);
            }
            
            // Filter berdasarkan rentang tanggal
            if ($request->start_date) {
                $query->where('tanggal', '>=', Carbon::parse($request->start_date));
            }
            
            if ($request->end_date) {
                $query->where('tanggal', '<=', Carbon::parse($request->end_date));
            }
            
            // Pagination
            $perPage = $request->per_page ?? 15;
            $riwayat = $query->orderBy('created_at', 'desc')->paginate($perPage);
            
            return response()->json([
                'success' => true,
                'data' => $riwayat
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil riwayat peminjaman',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Generate laporan otomatis per bulan
     */
    public function generateLaporanBulanan(Request $request)
    {
        try {
            // Tentukan bulan dan tahun
            $month = $request->month ?? Carbon::now()->month;
            $year = $request->year ?? Carbon::now()->year;
            
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfDay();
            $endDate = $startDate->copy()->endOfMonth()->endOfDay();
            
            // Ambil data peminjaman
            $peminjaman = Peminjaman::with(['user', 'item'])
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->orderBy('tanggal', 'asc')
                ->get();
            
            // Format nama file
            $fileName = "laporan_peminjaman_{$year}_{$month}_" . Carbon::now()->format('Ymd_His');
            
            // Tentukan format output
            $format = $request->format ?? 'excel'; // excel atau pdf
            
            if ($format === 'pdf') {
                $pdf = PDF::loadView('laporan.peminjaman-pdf', [
                    'peminjaman' => $peminjaman,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'statistics' => $this->calculateStatistics($peminjaman)
                ]);
                
                return $pdf->download($fileName . '.pdf');
                
            } else {
                return Excel::download(
                    new PeminjamanExport($startDate, $endDate), 
                    $fileName . '.xlsx'
                );
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate laporan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Schedule untuk generate laporan otomatis setiap bulan
     * (Panggil ini dari cron job atau scheduler)
     */
    public function generateLaporanOtomatis()
    {
        try {
            // Generate laporan untuk bulan sebelumnya
            $lastMonth = Carbon::now()->subMonth();
            
            // Cek apakah laporan sudah pernah dibuat
            $laporanPath = storage_path("app/laporan/peminjaman_{$lastMonth->format('Y_m')}.xlsx");
            
            if (!file_exists($laporanPath)) {
                // Buat direktori jika belum ada
                if (!is_dir(storage_path('app/laporan'))) {
                    mkdir(storage_path('app/laporan'), 0777, true);
                }
                
                // Generate Excel
                $startDate = $lastMonth->copy()->startOfMonth();
                $endDate = $lastMonth->copy()->endOfMonth();
                
                Excel::store(
                    new PeminjamanExport($startDate, $endDate),
                    "laporan/peminjaman_{$lastMonth->format('Y_m')}.xlsx"
                );
                
                // Bisa juga generate PDF
                $peminjaman = Peminjaman::with(['user', 'item'])
                    ->whereBetween('tanggal', [$startDate, $endDate])
                    ->get();
                
                $pdf = PDF::loadView('laporan.peminjaman-pdf', [
                    'peminjaman' => $peminjaman,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'statistics' => $this->calculateStatistics($peminjaman)
                ]);
                
                $pdf->save(storage_path("app/laporan/peminjaman_{$lastMonth->format('Y_m')}.pdf"));
                
                return response()->json([
                    'success' => true,
                    'message' => 'Laporan otomatis berhasil dibuat untuk bulan ' . $lastMonth->format('F Y'),
                    'file_excel' => "laporan/peminjaman_{$lastMonth->format('Y_m')}.xlsx",
                    'file_pdf' => "laporan/peminjaman_{$lastMonth->format('Y_m')}.pdf"
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Laporan untuk bulan ' . $lastMonth->format('F Y') . ' sudah ada'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error generating automatic report: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate laporan otomatis',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Helper untuk menghitung statistik
     */
    private function calculateStatistics($peminjaman)
    {
        return [
            'total' => $peminjaman->count(),
            'dipinjam' => $peminjaman->where('status_pinjaman', 'dipinjam')->count(),
            'selesai' => $peminjaman->where('status_pinjaman', 'selesai')->count(),
            'telat' => $peminjaman->where('status_pinjaman', 'telat')->count(),
            'pending' => $peminjaman->where('status_tujuan', 'pending')->count(),
            'approved' => $peminjaman->where('status_tujuan', 'approved')->count(),
            'rejected' => $peminjaman->where('status_tujuan', 'rejected')->count(),
            'total_items' => $peminjaman->unique('item_id')->count(),
            'total_users' => $peminjaman->unique('user_id')->count(),
        ];
    }
    
    /**
     * Update status peminjaman menjadi telat jika melewati tanggal
     * (Panggil ini setiap hari via scheduler)
     */
    public function updateStatusTelat()
    {
        $today = Carbon::today();
        
        $updated = Peminjaman::where('status_pinjaman', 'dipinjam')
            ->where('tanggal', '<', $today)
            ->update(['status_pinjaman' => 'telat']);
        
        return response()->json([
            'success' => true,
            'message' => "Berhasil mengupdate {$updated} peminjaman menjadi telat"
        ]);
    }
}
