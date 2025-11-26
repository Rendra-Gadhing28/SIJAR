<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Peminjaman - Admin</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('/images/logo_sijar.png') }}">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen">
    @include('layouts.navigationadmin')

    <main class="pt-28 px-6 md:px-12 pb-20 max-w-4xl mx-auto">
        <h2 class="text-2xl font-bold mb-6">Detail Peminjaman</h2>

        <div class="bg-white rounded-2xl shadow p-6">
            <p><strong>User:</strong> {{ $peminjaman->user->name }}</p>
            <p><strong>Barang:</strong> {{ $peminjaman->item->nama_item }}</p>
            <p><strong>Keperluan:</strong> {{ $peminjaman->keperluan }}</p>
            <p><strong>Status:</strong> {{ $peminjaman->status_tujuan }}</p>
            {{-- Waktu --}}
            <div class="mt-2">
                <p class="font-semibold">Waktu Pembelajaran:</p>
                @foreach(json_decode($peminjaman->jam_pembelajaran, true) ?? [] as $waktuData)
                    <span class="px-2 py-1 bg-blue-100 text-xs rounded">{{ $waktuData['jam_ke'] }}. {{ $waktuData['start_time'] }} - {{ $waktuData['end_time'] }}</span>
                @endforeach
            </div>
            @if($peminjaman->gambar_bukti)
                <img src="{{ asset('storage/'.$peminjaman->gambar_bukti) }}" class="w-32 h-32 mt-4">
            @endif

            @if($peminjaman->status_tujuan == 'Pending')
                <div class="mt-4 flex gap-2">
                    <form action="{{ route('admin.peminjaman.approve', $peminjaman->id) }}" method="POST">
                        @csrf
                        <button class="bg-green-600 text-white px-4 py-2 rounded">Approve</button>
                    </form>
                    <form action="{{ route('admin.peminjaman.reject', $peminjaman->id) }}" method="POST">
                        @csrf
                        <button class="bg-red-600 text-white px-4 py-2 rounded">Reject</button>
                    </form>
                </div>
            @endif
        </div>

        <a href="{{ route('admin.peminjaman.index') }}" class="mt-4 inline-block bg-gray-600 text-white px-4 py-2 rounded">Kembali</a>
    </main>
</body>
</html>