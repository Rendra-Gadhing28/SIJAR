<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Carbon\Carbon;

class PeminjamanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    protected $startDate;
    protected $endDate;
    
    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }
    
    public function collection()
    {
        return Peminjaman::with(['user', 'item'])
            ->whereBetween('tanggal', [$this->startDate, $this->endDate])
            ->orderBy('tanggal', 'desc')
            ->get();
    }
    
    public function headings(): array
    {
        return [
            'ID',
            'Keperluan',
            'Peminjam',
            'Barang',
            'Tanggal Peminjaman',
            'Tanggal Selesai',
            'Status Tujuan',
            'Status Peminjaman',
            'Jam Pembelajaran',
            'Dibuat Pada',
        ];
    }
    
    public function map($peminjaman): array
    {
        // Parse jam_pembelajaran
        $jamPembelajaran = '';
        if ($peminjaman->jam_pembelajaran) {
            $jamData = json_decode($peminjaman->jam_pembelajaran, true);
            if (is_array($jamData)) {
                $jamList = [];
                foreach ($jamData as $jam) {
                    $jamList[] = "Jam {$jam['jam_ke']}: {$jam['start_time']} - {$jam['end_time']}";
                }
                $jamPembelajaran = implode("\n", $jamList);
            }
        }
        
        return [
            $peminjaman->id,
            $peminjaman->keperluan,
            $peminjaman->user ? $peminjaman->user->name : 'N/A',
            $peminjaman->item ? $peminjaman->item->nama_item : 'N/A',
            Carbon::parse($peminjaman->tanggal)->format('d/m/Y'),
            $peminjaman->finished_at ? Carbon::parse($peminjaman->finished_at)->format('d/m/Y H:i') : '-',
            $this->getStatusLabel($peminjaman->status_tujuan),
            $this->getStatusPinjamanLabel($peminjaman->status_pinjaman),
            $jamPembelajaran,
            Carbon::parse($peminjaman->created_at)->format('d/m/Y H:i'),
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
    
    public function title(): string
    {
        return 'Laporan Peminjaman';
    }
    
    private function getStatusLabel($status)
    {
        $labels = [
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak'
        ];
        return $labels[$status] ?? $status;
    }
    
    private function getStatusPinjamanLabel($status)
    {
        $labels = [
            'dipinjam' => 'Dipinjam',
            'selesai' => 'Selesai',
            'telat' => 'Terlambat'
        ];
        return $labels[$status] ?? $status;
    }
}