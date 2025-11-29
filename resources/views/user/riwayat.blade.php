<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Peminjaman</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('/images/logo_sijar.png') }}">
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen font-['Poppins']">
    @include('layouts.navigation')
   

    <main class="pt-28 px-6 md:px-12 pb-20 max-w-6xl mx-auto">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <h2 class="text-2xl font-bold">Riwayat Peminjaman</h2>
            <a href="{{ route('peminjaman.create') }}"
                class="w-full sm:w-auto bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-semibold text-center">
                + Pinjam Barang
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if ($peminjaman->count() > 0)
            <div class="space-y-6">
                @foreach ($peminjaman as $pinjam)
                    <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition mb-6">

                        <div class="flex flex-col gap-5">

                            <div class="flex gap-5">
                                @if ($pinjam->gambar_bukti)
                                    <img src="{{ asset('storage/' . $pinjam->gambar_bukti) }}"
                                        class="w-24 h-24 rounded-xl object-cover shadow flex-shrink-0" alt="Bukti">
                                @else
                                    <div class="w-24 h-24 bg-gray-200 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <span class="text-gray-500 text-sm">Tidak ada foto</span>
                                    </div>
                                @endif

                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2 mb-2">
                                        <h3 class="font-bold text-lg pr-2">
                                            {{ $pinjam->item->nama_item ?? '-' }}
                                        </h3>
                                        
                                        <span class="px-3 py-1 rounded-full text-sm font-semibold self-start flex-shrink-0
                                            @if($pinjam->status_tujuan == 'Pending') bg-yellow-100 text-yellow-700
                                            @elseif($pinjam->status_tujuan == 'Approved') bg-green-100 text-green-700
                                            @elseif($pinjam->status_tujuan == 'Rejected') bg-red-100 text-red-700
                                            @endif">
                                            {{ $pinjam->status_tujuan }}
                                        </span>
                                    </div>

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
                                        <p class="text-sm font-semibold text-gray-700 mb-2">Waktu Pembelajaran:</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach(json_decode($pinjam->jam_pembelajaran, true) ?? [] as $waktuData)
                                                <span
                                                    class="px-2 py-1 md:px-3 md:py-1.5 bg-blue-100 text-blue-700 text-[10px] md:text-xs rounded-full">
                                                    Jam {{ $waktuData['jam_ke'] }}. {{ $waktuData['start_time'] }} -
                                                    {{ $waktuData['end_time'] }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                                
                                <div>
                                    @if($pinjam->status_tujuan == 'Approved')
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold mb-2
                                            @if($pinjam->status_pinjaman == 'dipinjam') bg-blue-100 text-blue-700
                                            @else bg-gray-100 text-gray-700
                                            @endif">
                                            {{ ucfirst($pinjam->status_pinjaman) }}
                                        </span>
                                        <div class="mt-2">
                                        <p class="text-xs text-green-600">
                                            Disetujui pada {{ $pinjam->approved_at?->format('d M Y, H:i') }}
                                        </p>
                                        <p class="text-xs text-indigo-600">
                                            Dikembalikan pada {{ $pinjam->finished_at?->format('d M Y, H:i') }}
                                        </p>
                                        </div>
                                    @endif

                                    @if($pinjam->status_tujuan == 'Rejected')
                                        <p class="text-xs text-red-500">
                                            Ditolak pada {{ $pinjam->rejected_at?->format('d M Y, H:i') }}
                                        </p>
                                    @endif
                                </div>

                                <div class="flex gap-2 items-center">
                                    @if($pinjam->status_tujuan == 'Approved' && $pinjam->status_pinjaman == 'dipinjam')
                                        <form action="{{ route('peminjaman.selesai', $pinjam->id) }}" method="POST">
                                            @csrf
                                             <button id="btn1" type="submit" class="mt-2 lg:hover:bg-[#f5f5f5] bg-green-500 text-white border-2 border-gray-500 lg:hover:border-green-400 px-2 py-2 text-lg rounded-lg lg:hover:text-green-500">
                                            Selesaikan
                                        </button>
                                        </form>
                                    @endif

                                    @if($pinjam->status_tujuan == 'Pending')
                                        <a href="{{ route('peminjaman.edit', $pinjam->id) }}"
                                            class="px-3 py-1.5 text-blue-600 text-xs font-medium hover:bg-blue-50 rounded-lg transition">
                                            Edit
                                        </a>
                                        <form action="{{ route('peminjaman.destroy', $pinjam->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin membatalkan?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="px-3 py-1.5 text-red-600 text-xs font-medium hover:bg-red-50 rounded-lg transition">
                                                Batalkan
                                            </button>
                                        </form>
                                    @endif
                                </div>

                            </div>

                        </div>

                    </div>
                @endforeach

            </div>

            <div class="mt-6">
                {{ $peminjaman->links() }}
            </div>

        @else
            <div class="bg-white p-14 rounded-2xl shadow text-center">
                <h3 class="text-xl font-bold text-gray-600 mb-2">Belum Ada Riwayat</h3>
                <p class="text-gray-500 mb-4">Anda belum pernah melakukan peminjaman barang.</p>

                <a href="{{ route('peminjaman.create') }}"
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-semibold">
                    Mulai Pinjam Barang
                </a>
            </div>
        @endif

    </main>

</body>

</html>