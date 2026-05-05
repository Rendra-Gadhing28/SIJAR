<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PeminjamanExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $starDate;
    protected $endDate;

    public function __construct($starDate = null, $endDate = null){
        $this->starDate = $starDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $q = Peminjaman::with(['user', 'item']);
        if($this->starDate && $this->endDate){
            $q->whereBetween('tanggal', [$this->starDate, $this->endDate]);
        }
        return $q->get();
    }

    public function headings():array{
        return [
            'No',
            'ID Peminjaman',
            'Peminjam',
            'Barang',
            'Keperluan',
            'Disetujui Pada',
            'Ditolak Pada',
            'Tanggal Kembali',
            'Status Peminjaman',
            'Status Pinjaman',
            'Bukti',
            'Jam Pembelajaran',
            "Dibuat Pada"
        ];
    }

    public function map($peminjaman):array{
        static $no = 0;
        $no++;

        return [
            $no,
            $peminjaman->id,
            $peminjaman->user->name ?? "-",
            $peminjaman->item->nama_item ?? "-",
            $peminjaman->keperluan ?? "-",
            $peminjaman->approved_at ?? "-",
            $peminjaman->rejected_at ?? "-",
            $peminjaman->finished_at ?? "-",
            $peminjaman->status_tujuan ?? "-",
            $peminjaman->status_pinjaman ?? "-",
            $peminjaman->gambar_bukti?? "-",
            $peminjaman->jam_pembelajaran ?? "-",
            $peminjaman->created_at ?? "-",
        ];
    }
}
