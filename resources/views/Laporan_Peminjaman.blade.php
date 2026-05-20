<div>
    <!-- Simplicity is an acquired taste. - Katharine Gerould -->
</div><!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman</title>
    <style>
        /* ── Reset & Base ─────────────────────────────────────────────── */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --blue-primary:  #4A90D9;
            --blue-light:    #7BB8E8;
            --blue-lighter:  #D6EAFA;
            --blue-dark:     #2563A8;
            --blue-darker:   #1A3F70;
            --sky:           #F0F7FF;
            --white:         #FFFFFF;
            --text-main:     #1E3A5F;
            --text-muted:    #6B8BAD;
            --green-soft:    #3DBD8F;
            --yellow-soft:   #F5C842;
        }

        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 9px;
            color: var(--text-main);
            background: var(--white);
            line-height: 1.4;
        }

        /* ── Page ─────────────────────────────────────────────────────── */
        .page {
            padding: 0;
            width: 100%;
        }

        /* ── Header ───────────────────────────────────────────────────── */
        .header {
            background: linear-gradient(135deg, var(--blue-darker) 0%, var(--blue-dark) 55%, var(--blue-primary) 100%);
            padding: 24px 32px 20px 32px;
            position: relative;
            overflow: hidden;
        }

        .header-accent {
            position: absolute;
            top: -20px;
            right: -20px;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: rgba(74, 144, 217, 0.25);
        }

        .header-accent-2 {
            position: absolute;
            bottom: -30px;
            right: 80px;
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: rgba(123, 184, 232, 0.15);
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .header-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-icon {
            width: 42px;
            height: 42px;
            background: rgba(255,255,255,0.15);
            border: 1.5px solid rgba(255,255,255,0.3);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .header-icon svg {
            width: 24px;
            height: 24px;
        }

        .header-title-group h1 {
            font-size: 16px;
            font-weight: 700;
            color: var(--white);
            letter-spacing: 0.3px;
        }

        .header-title-group p {
            font-size: 8px;
            color: rgba(255,255,255,0.7);
            margin-top: 2px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .header-meta {
            text-align: right;
        }

        .header-meta .badge-period {
            display: inline-block;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.25);
            color: var(--white);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 7.5px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .header-meta .generated-at {
            font-size: 7px;
            color: rgba(255,255,255,0.55);
            margin-top: 4px;
        }

        /* ── Divider strip ────────────────────────────────────────────── */
        .strip {
            height: 4px;
            background: linear-gradient(90deg, var(--blue-primary), var(--blue-light), var(--green-soft));
        }

        /* ── Content wrapper ──────────────────────────────────────────── */
        .content {
            padding: 20px 32px 28px 32px;
        }

        /* ── Summary cards ────────────────────────────────────────────── */
        .summary-grid {
            display: table;
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px 0;
            margin-bottom: 20px;
        }

        .summary-row {
            display: table-row;
        }

        .summary-card {
            display: table-cell;
            width: 16.66%;
            background: var(--sky);
            border: 1px solid var(--blue-lighter);
            border-radius: 8px;
            padding: 10px 10px 10px 12px;
            vertical-align: top;
            position: relative;
        }

        .summary-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 3px;
            height: 100%;
            border-radius: 8px 0 0 8px;
        }

        .card-blue::before  { background: var(--blue-primary); }
        .card-green::before { background: var(--green-soft); }
        .card-yellow::before{ background: var(--yellow-soft); }

        .summary-card .card-value {
            font-size: 18px;
            font-weight: 700;
            color: var(--blue-darker);
            line-height: 1;
        }

        .summary-card .card-label {
            font-size: 7px;
            color: var(--text-muted);
            margin-top: 3px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        /* ── Section title ────────────────────────────────────────────── */
        .section-title {
            font-size: 10px;
            font-weight: 700;
            color: var(--blue-darker);
            letter-spacing: 0.3px;
            margin-bottom: 8px;
            padding-bottom: 5px;
            border-bottom: 1.5px solid var(--blue-lighter);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .section-title .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--blue-primary);
            display: inline-block;
            flex-shrink: 0;
        }

        /* ── Table ────────────────────────────────────────────────────── */
        .table-wrap {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead tr {
            background: var(--blue-darker);
        }

        thead th {
            color: var(--white);
            font-size: 7.5px;
            font-weight: 600;
            padding: 7px 8px;
            text-align: left;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        thead th:first-child { border-radius: 6px 0 0 6px; }
        thead th:last-child  { border-radius: 0 6px 6px 0; }

        tbody tr:nth-child(even) {
            background: var(--sky);
        }

        tbody tr:nth-child(odd) {
            background: var(--white);
        }

        tbody tr {
            border-bottom: 1px solid var(--blue-lighter);
        }

        tbody td {
            padding: 6px 8px;
            font-size: 8px;
            color: var(--text-main);
            vertical-align: middle;
        }

        /* ── Status badges ────────────────────────────────────────────── */
        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 20px;
            font-size: 7px;
            font-weight: 600;
            letter-spacing: 0.2px;
        }

        .badge-selesai  { background: #D1F5E8; color: #1A7A55; }
        .badge-telat    { background: #FDE8C8; color: #9A5C0A; }
        .badge-dipinjam { background: var(--blue-lighter); color: var(--blue-darker); }
        .badge-pending  { background: #F3F0FF; color: #5B3FA6; }
        .badge-default  { background: #EDEFF2; color: #5A6A7A; }

        /* ── No data ──────────────────────────────────────────────────── */
        .no-data {
            text-align: center;
            padding: 24px;
            color: var(--text-muted);
            font-size: 8.5px;
            font-style: italic;
            background: var(--sky);
            border-radius: 6px;
        }

        /* ── Footer ───────────────────────────────────────────────────── */
        .footer {
            margin-top: 12px;
            padding-top: 10px;
            border-top: 1px solid var(--blue-lighter);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer .footer-left {
            font-size: 7px;
            color: var(--text-muted);
        }

        .footer .footer-right {
            font-size: 7px;
            color: var(--text-muted);
        }

        /* ── Page break ───────────────────────────────────────────────── */
        .page-break { page-break-after: always; }

        /* ── Align helpers ────────────────────────────────────────────── */
        .text-center { text-align: center; }
        .text-right  { text-align: right; }
        .font-bold   { font-weight: 700; }
        .color-muted { color: var(--text-muted); }

        /* ── Watermark / info strip ───────────────────────────────────── */
        .info-strip {
            background: var(--blue-lighter);
            border-left: 3px solid var(--blue-primary);
            padding: 7px 12px;
            border-radius: 0 6px 6px 0;
            margin-bottom: 16px;
            font-size: 7.5px;
            color: var(--blue-darker);
        }
    </style>
</head>
<body>
<div class="page">

    {{-- ════════ HEADER ════════ --}}
    <div class="header">
        <div class="header-accent"></div>
        <div class="header-accent-2"></div>
        <div class="header-top">
            <div class="header-brand">
                <div class="header-icon">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="3" y="3" width="8" height="8" rx="1.5" fill="rgba(255,255,255,0.9)"/>
                        <rect x="13" y="3" width="8" height="8" rx="1.5" fill="rgba(255,255,255,0.6)"/>
                        <rect x="3" y="13" width="8" height="8" rx="1.5" fill="rgba(255,255,255,0.6)"/>
                        <rect x="13" y="13" width="8" height="8" rx="1.5" fill="rgba(255,255,255,0.9)"/>
                    </svg>
                </div>
                <div class="header-title-group">
                    <h1>Laporan Peminjaman Barang</h1>
                    <p>Sistem Manajemen Peminjaman &mdash; Admin Dashboard</p>
                </div>
            </div>
            <div class="header-meta">
                @if ($start_date && $end_date)
                    <div class="badge-period">
                        {{ \Carbon\Carbon::parse($start_date)->format('d M Y') }}
                        &nbsp;&mdash;&nbsp;
                        {{ \Carbon\Carbon::parse($end_date)->format('d M Y') }}
                    </div>
                @else
                    <div class="badge-period">Semua Periode</div>
                @endif
                <div class="generated-at">
                    Dicetak: {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y, HH:mm') }} WIB
                </div>
            </div>
        </div>
    </div>

    <div class="strip"></div>

    {{-- ════════ CONTENT ════════ --}}
    <div class="content">

        {{-- ── Summary ── --}}
        @php
            $total     = $peminjaman->count();
            $selesai   = $peminjaman->where('status_pinjaman', 'selesai')->count();
            $telat     = $peminjaman->where('status_pinjaman', 'telat')->count();
            $dipinjam  = $peminjaman->where('status_pinjaman', 'dipinjam')->count();
            $siswaUnik = $peminjaman->pluck('user_id')->unique()->count();
            $rasio     = $total > 0 ? round(($selesai / $total) * 100) : 0;
        @endphp

        <table class="summary-grid" style="border-spacing:6px;margin-bottom:18px;">
            <tr class="summary-row">
                <td class="summary-card card-blue">
                    <div class="card-value">{{ $total }}</div>
                    <div class="card-label">Total Peminjaman</div>
                </td>
                <td class="summary-card card-green">
                    <div class="card-value">{{ $selesai }}</div>
                    <div class="card-label">Dikembalikan</div>
                </td>
                <td class="summary-card card-yellow">
                    <div class="card-value">{{ $telat }}</div>
                    <div class="card-label">Terlambat</div>
                </td>
                <td class="summary-card card-blue">
                    <div class="card-value">{{ $dipinjam }}</div>
                    <div class="card-label">Sedang Dipinjam</div>
                </td>
                <td class="summary-card card-blue">
                    <div class="card-value">{{ $siswaUnik }}</div>
                    <div class="card-label">Siswa Aktif</div>
                </td>
                <td class="summary-card card-green">
                    <div class="card-value">{{ $rasio }}%</div>
                    <div class="card-label">Tepat Waktu</div>
                </td>
            </tr>
        </table>

        {{-- ── Info filter ── --}}
        @if ($start_date && $end_date)
        <div class="info-strip">
            &#128197; Data difilter dari <strong>{{ \Carbon\Carbon::parse($start_date)->isoFormat('D MMMM Y') }}</strong>
            hingga <strong>{{ \Carbon\Carbon::parse($end_date)->isoFormat('D MMMM Y') }}</strong>
            &nbsp;|&nbsp; Total {{ $total }} record ditemukan.
        </div>
        @endif

        {{-- ── Tabel Detail Peminjaman ── --}}
        <div class="section-title">
            <span class="dot"></span>
            Detail Seluruh Peminjaman
        </div>

        <div class="table-wrap">
            @if ($peminjaman->isEmpty())
                <div class="no-data">Tidak ada data peminjaman untuk periode ini.</div>
            @else
            <table>
                <thead>
                    <tr>
                        <th style="width:3%">#</th>
                        <th style="width:16%">Nama Siswa</th>
                        <th style="width:13%">Jurusan</th>
                        <th style="width:18%">Nama Barang</th>
                        <th style="width:12%">Kategori</th>
                        <th style="width:11%">Keperluan</th>
                        <th style="width:9%">Tanggal</th>
                        <th style="width:9%">Tgl Kembali</th>
                        <th style="width:9%" class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($peminjaman as $i => $p)
                    <tr>
                        <td class="color-muted text-center">{{ $i + 1 }}</td>
                        <td class="font-bold">{{ $p->user?->name ?? '-' }}</td>
                        <td>{{ $p->user?->jurusan?->nama_jurusan ?? '-' }}</td>
                        <td>{{ $p->item?->nama_item ?? '-' }}</td>
                        <td class="color-muted">{{ $p->item?->kategoriJurusan?->nama_kategori ?? '-' }}</td>
                        <td class="color-muted">{{ Str::limit($p->keperluan ?? '-', 25) }}</td>
                        <td>
                            {{ $p->tanggal ? \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') : '-' }}
                        </td>
                        <td>
                            {{ $p->tanggal_kembali ? \Carbon\Carbon::parse($p->tanggal_kembali)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="text-center">
                            @php
                                $status = strtolower($p->status_pinjaman ?? '');
                                $badgeClass = match($status) {
                                    'selesai'  => 'badge-selesai',
                                    'telat'    => 'badge-telat',
                                    'dipinjam' => 'badge-dipinjam',
                                    default    => 'badge-default',
                                };
                                $statusLabel = match($status) {
                                    'selesai'  => 'Selesai',
                                    'telat'    => 'Terlambat',
                                    'dipinjam' => 'Dipinjam',
                                    default    => ucfirst($status) ?: '-',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>

        {{-- ── Rekap Per Jurusan ── --}}
        @php
            $rekap = $peminjaman
                ->groupBy(fn($p) => $p->user?->jurusan?->nama_jurusan ?? 'Tidak Diketahui')
                ->map(fn($g, $nama) => [
                    'jurusan' => $nama,
                    'total'   => $g->count(),
                    'selesai' => $g->where('status_pinjaman','selesai')->count(),
                    'telat'   => $g->where('status_pinjaman','telat')->count(),
                    'aktif'   => $g->where('status_pinjaman','dipinjam')->count(),
                ])
                ->sortByDesc('total')
                ->values();
        @endphp

        @if ($rekap->isNotEmpty())
        <div style="margin-top:16px;">
            <div class="section-title">
                <span class="dot"></span>
                Rekap per Jurusan
            </div>
            <table>
                <thead>
                    <tr>
                        <th style="width:4%">#</th>
                        <th style="width:36%">Jurusan</th>
                        <th style="width:15%" class="text-center">Total</th>
                        <th style="width:15%" class="text-center">Selesai</th>
                        <th style="width:15%" class="text-center">Terlambat</th>
                        <th style="width:15%" class="text-center">Dipinjam</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rekap as $i => $r)
                    <tr>
                        <td class="color-muted text-center">{{ $i + 1 }}</td>
                        <td class="font-bold">{{ $r['jurusan'] }}</td>
                        <td class="text-center font-bold">{{ $r['total'] }}</td>
                        <td class="text-center" style="color:var(--green-soft)">{{ $r['selesai'] }}</td>
                        <td class="text-center" style="color:var(--yellow-soft)">{{ $r['telat'] }}</td>
                        <td class="text-center" style="color:var(--blue-primary)">{{ $r['aktif'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- ── Footer ── --}}
        <div class="footer">
            <div class="footer-left">
                &#169; {{ date('Y') }} Sistem Manajemen Peminjaman &mdash; Dicetak secara otomatis
            </div>
            <div class="footer-right">
                Total data: <strong>{{ $total }}</strong> record
            </div>
        </div>

    </div>{{-- end .content --}}
</div>{{-- end .page --}}
</body>
</html>