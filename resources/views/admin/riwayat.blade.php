<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Peminjaman</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('/images/logo_sijar.png') }}">
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 min-h-screen font-['Poppins']">
    <header>
    @include('layouts.navigationadmin')
    </header>

    <main class="pt-28 px-6 md:px-12 pb-20 max-w-6xl mx-auto">
        <h1 class="text-4xl font-bold text-center mb-8 mt-4">Riwayat Peminjaman Siswa</h1>
      <form method="GET" class="flex items-center justify-end gap-2 mb-4">
         
                <!-- Filter Kelas -->
                <div class="flex flex-col">
                    <select name="kelas" id="kelas" class="form-control rounded-lg shadow-sm shadow-sky-300">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas }}" 
                                {{ request('kelas') == $kelas ? 'selected' : '' }}>
                                Kelas {{ $kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Status -->
                <div class="flex flex-col">
                    <select name="status_tujuan" id="status_tujuan" class="form-control rounded-lg shadow-sm shadow-sky-300">
                        <option value="">-- Semua Status --</option>
                        <option value="Pending" {{ request('status_tujuan') == 'Pending' ? 'selected' : '' }}>
                            Pending
                        </option>
                        <option value="Approved" {{ request('status_tujuan') == 'Approved' ? 'selected' : '' }}>
                            Approved
                        </option>
                        <option value="Rejected" {{ request('status_tujuan') == 'Rejected' ? 'selected' : '' }}>
                            Rejected
                        </option>
                    </select>
                </div>

    <button type="submit"
        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
        Filter
    </button>

</form>


       @if ($peminjaman->count() > 0)
    <div class="space-y-5">
        @foreach ($peminjaman as $pinjam)
            <div class="relative bg-white rounded-2xl shadow p-6 hover:shadow-lg transition">

                <div class="flex flex-col md:flex-row gap-5 justify-between">

                    {{-- LEFT --}}
                    <div class="flex gap-5">
                        
                        {{-- Gambar Bukti --}}
                        @if ($pinjam->gambar_bukti)
                            <img src="{{ asset('storage/' . $pinjam->gambar_bukti) }}"
                                 class="w-24 h-24 rounded-xl object-cover shadow" alt="Bukti">
                        @else
                            <div class="w-24 h-24 bg-gray-200 rounded-xl flex items-center justify-center">
                                <span class="text-gray-500 text-sm">Tidak ada foto</span>
                            </div>
                        @endif

                        {{-- INFO --}}
                        <div>
                            <h2 class="font-bold text-lg">
                                {{ $pinjam->user->name ?? '-' }}
                            </h2>
                            <h2 class="font-bold text-lg">
                                {{ $pinjam->user->kategori->nama_kategori ?? '-' }}
                            </h2>

                            <h3 class="font-bold text-lg">
                                {{ $pinjam->item->nama_item ?? '-' }}
                            </h3>

                            <p class="text-sm text-gray-600">Kode Unit:
                                <span class="font-semibold">
                                    {{ $pinjam->item->kode_unit ?? '-' }}
                                </span>
                            </p>

                            <p class="text-sm text-gray-600">
                                Keperluan: {{ $pinjam->keperluan }}
                            </p>

                            <p class="text-sm text-gray-600">
                                Tanggal Pinjam:
                                {{ \Carbon\Carbon::parse($pinjam->tanggal)->format('d M Y') }}
                            </p>

                            <p class="text-sm text-gray-600">
                                Durasi:
                                <span class="font-semibold">
                                    {{ $pinjam->created_at->diffForHumans() }}
                                </span>
                            </p>

                            <div class="mt-2">
                                <p class="text-sm font-semibold text-gray-700">Waktu Pembelajaran:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach(json_decode($pinjam->jam_pembelajaran, true) ?? [] as $waktuData)
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">
                                            Jam {{ $waktuData['jam_ke'] }}.
                                            {{ $waktuData['start_time'] }} -
                                            {{ $waktuData['end_time'] }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                {{-- RIGHT STATUS --}}
                <div class=" flex flex-col justify-start items-end  gap-3 text-right mt-4">

                    {{-- STATUS APPROVAL --}}
                    <span class="px-4 py-2 rounded-full text-sm font-semibold
                        @if($pinjam->status_tujuan == 'Pending') bg-yellow-100 text-yellow-700
                        @elseif($pinjam->status_tujuan == 'Approved') bg-green-100 text-green-700
                        @elseif($pinjam->status_tujuan == 'Rejected') bg-red-100 text-red-700
                        @endif">
                        {{ $pinjam->status_tujuan }}
                    </span>

                    {{-- STATUS PINJAMAN --}}
                    @if($pinjam->status_tujuan == 'Approved')
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if($pinjam->status_pinjaman == 'dipinjam') bg-blue-100 text-blue-700
                            @else bg-gray-100 text-gray-700
                            @endif">
                            {{ ucfirst($pinjam->status_pinjaman) }}
                        </span>
                          <p class="text-xs text-gray-500">
                            Disetujui pada {{ $pinjam->approved_at?->format('d M Y, H:i') }}
                        </p>
                    @endif

                    {{-- STATUS REJECT --}}
                    @if($pinjam->status_tujuan == 'Rejected')
                        <p class="text-xs text-gray-500">
                            Ditolak pada {{ $pinjam->rejected_at?->format('d M Y, H:i') }}
                        </p>
                    @endif

                    {{-- STATUS finish --}}
                    @if($pinjam->status_pinjaman == 'selesai')
                        <p class="text-xs text-gray-500">
                            Dikembalikan pada {{ $pinjam->finished_at?->format('d M Y, H:i') }}
                        </p>
                    @endif

                </div>

            </div>
        @endforeach
    </div>

    {{-- PAGINATION DI LUAR LOOP --}}
    <div class="mt-6">
        {{ $peminjaman->links() }}
    </div>

@endif

</main>
</body>
</html>