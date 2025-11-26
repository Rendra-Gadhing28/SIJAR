<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="{{ asset('/images/logo_sijar.png') }}">
    <title>Beranda SIJAR</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gradient-to-b from-gray-200 to-white flex flex-col min-h-screen font-['Poppins']">

    <header>
       @if(Auth::user()->role === 'admin')
    @include('layouts.navigationadmin')
@else
    @include('layouts.navigation')
    @include('layouts.navmobile')
@endif
    </header>

    <main class="pt-28 px-6 md:px-12 py-36 scroll-py-36">

        {{-- SECTION STATISTIK --}}
        <section class="max-w-2xl mx-auto bg-white rounded-2xl shadow p-6 text-center">
            <h2 class="text-2xl font-bold mb-6">Pinjaman</h2>
            <div class="flex justify-center gap-6">

                <div class="bg-gray-100 rounded-lg p-4 w-36 text-center shadow">
                    <p class="font-semibold">Dipinjam</p>
                    <p class="text-2xl font-bold">{{ $dipinjam }}</p>
                </div>

                <div class="bg-gray-100 rounded-lg p-4 w-36 text-center shadow">
                    <p class="font-semibold">Dikembalikan</p>
                    <p class="text-2xl font-bold">{{ $selesai }}</p>
                </div>

            </div>
        </section>

        {{-- LIST BARANG DIPINJAM --}}
        <section class="max-w-2xl mx-auto mt-8 bg-white rounded-2xl shadow p-6">
            <p class="font-medium mb-2">List barang yang dipinjam:</p>

            @forelse ($peminjaman as $pinjam)
                <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition mb-5">

                    <div class="flex flex-col  md:flex-row justify-between gap-5">

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

                                {{-- DURASI --}}
                                <p class="text-sm text-gray-600">
                                    Durasi:
                                    <span class="font-semibold">
                                        {{ $pinjam->created_at->diffForHumans() }}
                                    </span>
                                </p>

                                {{-- WAKTU PEMBELAJARAN JSON --}}
                                <div class="mt-2">
                                    <p class="text-sm font-semibold text-gray-700">Waktu Pembelajaran:</p>
                                    <div class="flex flex-wrap gap-2">

                                        @foreach (json_decode($pinjam->jam_pembelajaran, true) ?? [] as $waktuData)
                                            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">
                                                Jam {{ $waktuData['jam_ke'] }}.
                                                {{ $waktuData['start_time'] }} - {{ $waktuData['end_time'] }}
                                            </span>
                                        @endforeach

                                    </div>
                                </div>

                            </div>

                        </div>

                        {{-- RIGHT --}}
                        <div class="flex flex-col items-end gap-3 text-right">

                            {{-- STATUS APPROVAL --}}
                            <span class="px-4 py-2 rounded-full text-sm font-semibold
                                @if($pinjam->status_tujuan == 'Pending') bg-yellow-100 text-yellow-700
                                @elseif($pinjam->status_tujuan == 'Approved') bg-green-100 text-green-700
                                @elseif($pinjam->status_tujuan == 'Rejected') bg-red-100 text-red-700
                                @endif">
                                {{ $pinjam->status_tujuan }}
                            </span>

                            @if ($pinjam->status_tujuan == 'Approved')

                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($pinjam->status_pinjaman == 'dipinjam') bg-blue-100 text-blue-700
                                    @else bg-gray-100 text-gray-700
                                    @endif">
                                    {{ ucfirst($pinjam->status_pinjaman) }}
                                </span>

                                {{-- Tombol Selesaikan --}}
                                @if ($pinjam->status_pinjaman == 'dipinjam')
                                    <form action="{{ route('peminjaman.selesai', $pinjam->id) }}" method="POST" class="relative mt-auto items-baseline">
                                        @csrf
                                        <button id="btn1" type="submit" onclick="colors()" class="mt-2 lg:hover:bg-green-600 text-green-500 border-2 border-green-500 lg:hover:border-gray-400 px-2 py-2 text-lg rounded-lg lg:hover:text-white">
                                            Selesaikan
                                        </button>
                                    </form>
                                @endif
                            @endif

                            {{-- STATUS REJECTED --}}
                            @if ($pinjam->status_tujuan == 'Rejected')
                                <p class="text-xs text-gray-500">
                                    Ditolak pada {{ $pinjam->rejected_at?->format('d M Y, H:i') }}
                                </p>
                            @endif

                            {{-- EDIT / CANCEL --}}
                            @if ($pinjam->status_tujuan == 'Pending')
                                <div class="flex gap-3">
                                    <a href="{{ route('peminjaman.edit', $pinjam->id) }}"
                                        class="text-blue-600 text-sm hover:underline">Edit</a>

                                    <form action="{{ route('peminjaman.destroy', $pinjam->id) }}"
                                        method="POST" onsubmit="return confirm('Yakin ingin membatalkan?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 text-sm hover:underline">Batalkan</button>
                                    </form>
                                </div>
                            @endif

                        </div>

                    </div>

                </div>
            @empty
                <p class="text-center text-gray-500 py-8">Belum ada peminjaman.</p>
            @endforelse

        </section>

    </main>
    <script>
        let btn = document.getElementById['btn1']
        function colors(){
            btn.addEventlistener({
                btn.style.background = 'green'
            })
        }
    </script>
</body>
</html>
