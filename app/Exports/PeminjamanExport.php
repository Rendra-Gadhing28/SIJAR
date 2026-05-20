<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;

class PeminjamanExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithTitle
    // ShouldAutoSize  ← aktifkan jika tidak pakai WithColumnWidths
{
    protected ?string $startDate;
    protected ?string $endDate;

    public function __construct(?string $startDate = null, ?string $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Data source — WAJIB eager-load relasi di sini, bukan cuma di controller
    // ─────────────────────────────────────────────────────────────────────────
    public function collection(): Collection
    {
        $query = Peminjaman::with([
            'user.jurusan',         // nama siswa + nama_jurusan
            'item.kategoriJurusan', // nama_item + nama_kategori
        ]);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal', [$this->startDate, $this->endDate]);
        }

        return $query->orderBy('tanggal', 'asc')->get();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Heading row (row #1 di Excel)
    // ─────────────────────────────────────────────────────────────────────────
    public function headings(): array
    {
        return [
            'No',
            'Nama Siswa',
            'Jurusan',
            'Nama Barang',
            'Kategori Barang',
            'Keperluan',
            'Tanggal Pinjam',
            'Tanggal Kembali',
            'Status Peminjaman',
            'Status Tujuan',
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Row mapping — tiap baris dikonversi di sini
    // ─────────────────────────────────────────────────────────────────────────
    public function map($row): array
    {
        // Nomor urut — pakai static agar increment
        static $no = 0;
        $no++;

        // Pastikan tanggal selalu string bahkan kalau model tidak punya $casts
        $tanggal        = $row->tanggal
            ? Carbon::parse($row->tanggal)->format('d/m/Y')
            : '-';

        $tanggalKembali = $row->tanggal_kembali
            ? Carbon::parse($row->tanggal_kembali)->format('d/m/Y')
            : '-';

        // Status label — jika model punya accessor `status_pinjaman_formatted`
        // gunakan itu; fallback ke raw value dengan ucfirst
        $statusPinjaman = $row->status_pinjaman_formatted
            ?? ucfirst($row->status_pinjaman ?? '-');

        $statusTujuan   = $row->status_tujuan_formatted
            ?? ucfirst($row->status_tujuan ?? '-');

        return [
            $no,
            $row->user?->name                              ?? '-',
            $row->user?->jurusan?->nama_jurusan            ?? '-',
            $row->item?->nama_item                         ?? '-',
            $row->item?->kategoriJurusan?->nama_kategori   ?? '-',
            $row->keperluan                                ?? '-',
            $tanggal,
            $tanggalKembali,
            $statusPinjaman,
            $statusTujuan,
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Sheet title
    // ─────────────────────────────────────────────────────────────────────────
    public function title(): string
    {
        return 'Laporan Peminjaman';
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Styling
    // ─────────────────────────────────────────────────────────────────────────
    public function styles(Worksheet $sheet): array
    {
        // ── Set column widths manual ──────────────────────────────────────────
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(22);
        $sheet->getColumnDimension('C')->setWidth(18);
        $sheet->getColumnDimension('D')->setWidth(24);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(22);
        $sheet->getColumnDimension('G')->setWidth(14);
        $sheet->getColumnDimension('H')->setWidth(14);
        $sheet->getColumnDimension('I')->setWidth(18);
        $sheet->getColumnDimension('J')->setWidth(16);

        // ── Header row style ──────────────────────────────────────────────────
        return [
            1 => [
                'font' => [
                    'bold'  => true,
                    'color' => ['argb' => 'FFFFFFFF'],
                    'size'  => 10,
                ],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF1A3F70'], // --blue-darker
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                    'wrapText'   => true,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => ['argb' => 'FF4A90D9'],
                    ],
                ],
            ],
        ];
    }
}