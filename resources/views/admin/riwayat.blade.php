<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Peminjaman</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 min-h-screen font-['Poppins']">
    @include('layouts.navigation')
    @include('layouts.navmobile')

    <main class="pt-28 px-6 md:px-12 pb-20 max-w-6xl mx-auto">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Riwayat Peminjaman</h2>
            <a href="{{ route('peminjaman.create') }}"
                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-semibold">
                + Pinjam Barang
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- Jika ada data --}}
        @if ($peminjaman->count() > 0)
            <div class="space-y-5">
                @foreach ($peminjaman as $pinjam)
                        <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition">

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

                                        {{-- DURASI WAKTU berjalan --}}
                                        <p class="text-sm text-gray-600">
                                            Durasi:
                                            <span class="font-semibold">
                                                {{ $pinjam->created_at->diffForHumans() }} {{-- Ganti $peminjaman ke $pinjam --}}
                                            </span>
                                        </p>

                                        {{-- Waktu Pembelajaran --}}
                                        <div class="mt-2">
                                            <p class="text-sm font-semibold text-gray-700">Waktu Pembelajaran:</p>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach(json_decode($pinjam->jam_pembelajaran, true) ?? [] as $waktuData) {{--
                                                    Decode JSON dari jam_pembelajaran --}}
                                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">
                                                        Jam {{ $waktuData['jam_ke'] }}. {{ $waktuData['start_time'] }} -
                                                        {{ $waktuData['end_time'] }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>

                            {{-- RIGHT STATUS --}}
                            <div class="flex flex-col items-end gap-3 text-right">

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

                                    {{-- Tombol Selesai --}}
                                    @if($pinjam->status_pinjaman == 'dipinjam')
                                        <form action="{{ route('peminjaman.selesai', $pinjam->id) }}" method="POST">
                                            @csrf
                                            <button class="mt-1 bg-green-600 text-white px-4 py-1 text-xs rounded-lg hover:bg-green-700">
                                                Selesaikan
                                            </button>
                                        </form>
                                    @endif
                                @endif

                                {{-- STATUS REJECT --}}
                                @if($pinjam->status_tujuan == 'Rejected')
                                    <p class="text-xs text-gray-500">
                                        Ditolak pada {{ $pinjam->rejected_at?->format('d M Y, H:i') }}
                                    </p>
                                @endif

            {{-- PAGINATION --}}
            <div class="mt-6">
                {{ $peminjaman->links() }}
            </div>

        @else

    </main>

</body>

</html>