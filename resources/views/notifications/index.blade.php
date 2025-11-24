<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="{{ asset('/images/logo_sijar.png') }}">
    <title>Notifikasi</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gradient-to-b from-gray-200 to-white flex flex-col min-h-screen font-['Poppins']">
    <header>
         @include('layouts.navigation')
         @include('layouts.navmobile')
    </header>
   
    <main class="pt-28 px-6 md:px-12 pb-12">
        <section class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Notifikasi</h2>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="text-sm text-gray-600">
                        {{ auth()->user()->unreadNotifications->count() }} notifikasi belum dibaca
                    </span>
                @endif
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($notifications->count() > 0)
                <div class="space-y-3">
                    @foreach($notifications as $notification)
                        <div class="bg-white rounded-xl shadow p-4 hover:shadow-lg transition {{ $notification->read_at ? 'opacity-75' : 'border-l-4 border-blue-500' }}">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <!-- Icon berdasarkan type -->
                                    <div class="flex items-start gap-3">
                                        @if($notification->data['type'] === 'peminjaman_baru')
                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                            </div>
                                        @elseif($notification->data['type'] === 'peminjaman_approved')
                                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                        @elseif($notification->data['type'] === 'peminjaman_rejected')
                                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </div>
                                        @endif

                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-800 mb-1">
                                                {{ $notification->data['message'] }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    @if(!$notification->read_at)
                                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-xs text-blue-600 hover:underline">
                                                Tandai dibaca
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400">Dibaca</span>
                                    @endif

                                    @if(isset($notification->data['peminjaman_id']))
                                        <a href="{{ route('peminjaman.show', $notification->data['peminjaman_id']) }}" 
                                           class="text-xs text-blue-600 hover:underline">
                                            Lihat Detail
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="bg-white rounded-2xl shadow p-12 text-center">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-600 mb-2">Tidak Ada Notifikasi</h3>
                    <p class="text-gray-500">Anda belum memiliki notifikasi</p>
                </div>
            @endif
        </section>
    </main>
</body>

</html>