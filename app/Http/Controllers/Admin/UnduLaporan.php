<?php

namespace App\Http\Controllers\Admin;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\PeminjamanExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class UnduLaporan extends Controller
{
    public function getData(Request $req){
        $q = Peminjaman::with(['user', 'item']);
        if($req->start_date && $req->end_date){
            $q->whereBetween('tanggal', [$req->start_date, $req->end_date]);
        }

        $peminjaman = $q->orderBy('tanggal', 'keperluan')->get();

        return response()->json([
            'success' => true,
            'message' => "Unduh Laporan Berhasil",
            'data' => $peminjaman
        ], 200) ;
    }


    public function exportExcel(Request $req){
        $starDate = $req->input('start_date');
        $endDate = $req->input('end_date');

        return Excel::download(new PeminjamanExport($starDate, $endDate), 'laporan-peminjaman.xlsx');
    }

    public function exportPDF(Request $req){
        $starDate = $req->input('start_date');
        $endDate = $req->input('end_date');
        $q = Peminjaman::with(['user', 'item']);
        if($starDate && $endDate){
            $q->whereBetween('tanggal', [$starDate, $endDate]);
        }

        $peminjaman = $q->orderBy('tanggal', 'keperluan')->get();

        $pdf = Pdf::loadView('pdf.laporan-peminjaman', [
            'peminjaman' => $peminjaman,
            'start_date' => $starDate,
            'end_date' => $endDate
            ]);

        return $pdf->download('laporan-peminjaman.pdf');
    }
}