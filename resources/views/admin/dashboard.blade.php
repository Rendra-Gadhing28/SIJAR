<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="{{ asset('/images/logo_sijar.png') }}">
    <title>Dashboard Admin - SIJAR</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gradient-to-b from-gray-200 to-white flex flex-col min-h-screen font-['Poppins']">
    <header>
        @include('layouts.navigationadmin')  <!-- Navigation utama (sama seperti user, tapi bisa tambah menu admin jika perlu) -->
    </header>
    
    <main class="pt-28 px-6 md:px-12">
        <section class="max-w-2xl mx-auto bg-white rounded-2xl shadow p-6 text-center">
            <h2 class="text-2xl font-bold mb-6">Dashboard Admin</h2>
            <div class="flex justify-center gap-6">
                <div class="bg-gray-100 rounded-lg p-4 w-32 text-center shadow">
                    <p class="font-semibold">Pending</p>
                    <p class="text-2xl font-bold">{{ $totalPending }}</p>
                </div>
                <div class="bg-gray-100 rounded-lg p-4 w-32 text-center shadow">
                    <p class="font-semibold">Approved</p>
                    <p class="text-2xl font-bold">{{ $totalApproved }}</p>
                </div>
                <div class="bg-gray-100 rounded-lg p-4 w-32 text-center shadow">
                    <p class="font-semibold">Rejected</p>
                    <p class="text-2xl font-bold">{{ $totalRejected }}</p>
                </div>
            </div>
        </section>
        
        <section class="max-w-2xl mx-auto mt-8 bg-white rounded-2xl shadow p-6">
            <p class="font-medium mb-2">List peminjaman terbaru:</p>
            <div id="list-peminjaman">
                @if($recentPeminjaman->count() > 0)
                    @foreach($recentPeminjaman as $pinjam)
                        <div class="border-b py-2 flex justify-between items-center">
                            <div>
                                <p class="font-semibold">{{ $pinjam->user->name ?? 'Unknown' }} - {{ $pinjam->item->nama_item ?? 'Unknown' }}</p>
                                <p class="text-sm text-gray-500">Status: {{ $pinjam->status_tujuan }} | {{ $pinjam->created_at->diffForHumans() }}</p>
                                {{-- Waktu Pembelajaran --}}
                                <div class="mt-1">
                                    @foreach(json_decode($pinjam->jam_pembelajaran, true) ?? [] as $waktuData)
                                        <span class="px-2 py-1 bg-blue-100 text-xs rounded">{{ $waktuData['jam_ke'] }}. {{ $waktuData['start_time'] }} - {{ $waktuData['end_time'] }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <a href="{{ route('admin.peminjaman.show', $pinjam->id) }}" class="text-blue-600 text-sm hover:underline">Lihat Detail</a>
                        </div>
                    @endforeach
                @else
                    <p class="text-gray-500 italic">Belum ada data peminjaman</p>
                @endif
            </div>
        </section>
        
        {{-- Notifikasi Admin (Opsional, tambahan dari template) --}}
        <section class="max-w-2xl mx-auto mt-8 bg-white rounded-2xl shadow p-6">
            <p class="font-medium mb-2">Notifikasi Terbaru:</p>
            <div id="list-notifikasi">
                @if($notifications->count() > 0)
                    @foreach($notifications as $notif)
                        <div class="border-b py-2">
                            <p>{{ $notif->data['message'] ?? 'Peminjaman baru' }}</p>
                            <small class="text-gray-500">{{ $notif->created_at->diffForHumans() }}</small>
                        </div>
                    @endforeach
                @else
                    <p class="text-gray-500 italic">Tidak ada notifikasi baru</p>
                @endif
            </div>
        </section>
    </main>
</body>

</html>