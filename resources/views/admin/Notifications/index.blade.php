<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="{{ asset('/images/logo_sijar.png') }}">
    <title>Notifikasi - Admin</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gradient-to-b from-gray-200 to-white flex flex-col min-h-screen font-['Poppins']">
    <header>
         @include('layouts.navigationadmin')
    </header>
   
    <main class="pt-28 px-6 md:px-12 pb-12">
        <section class="max-w-8xl mx-auto">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <h2 class="text-2xl font-bold">Notifikasi Admin</h2>
                <div class="flex items-center gap-3 flex-wrap">
                    <a href="{{ route('admin.notifications.trashed') }}" 
                       class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-lg hover:bg-yellow-200 transition text-sm font-medium">
                        🗑️ Trash Bin
                    </a>
                    
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="text-sm text-gray-600">
                            {{ auth()->user()->unreadNotifications->count() }} belum dibaca
                        </span>
                        <form action="{{ route('admin.notifications.markAllRead') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-sm text-blue-600 hover:underline font-medium">
                                Tandai semua dibaca
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            
            <div class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($notifications as $notification)
                    <div class="h-38 bg-white/90 rounded-xl shadow p-4 hover:shadow-lg transition 
                        {{ $notification->read_at ? 'opacity-75' : 'border-l-4 border-blue-500' }}">
                        <div class="flex items-start justify-between gap-4">
                            <div class="">
                                <div class="flex items-start gap-3">
                                    <!-- Icon berdasarkan type -->
                                    
                                    @if(isset($notification->data['type']) && $notification->data['type'] === 'peminjaman_baru')
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </div>
                                    @elseif(isset($notification->data['type']) && $notification->data['type'] === 'peminjaman_approved')
                                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    @elseif(isset($notification->data['type']) && $notification->data['type'] === 'peminjaman_rejected')
                                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                            </svg>
                                        </div>
                                    @endif

                                    <div class="">
                                        <p class="font-semibold text-gray-800 mb-1">
                                            {{ $notification->data['message'] ?? 'Notifikasi baru' }}
                                        </p>
                                        
                                        @if(isset($notification->data['user_name']))
                                            <p class="text-sm text-gray-600 mb-1">
                                                <span class="font-medium">User:</span> {{ $notification->data['user_name'] }}
                                            </p>
                                        @endif
                                        
                                        @if(isset($notification->data['item_name']))
                                            <p class="text-sm text-gray-600 mb-1">
                                                <span class="font-medium">Barang:</span> {{ $notification->data['item_name'] }}
                                                @if(isset($notification->data['kode_unit']))
                                                    ({{ $notification->data['kode_unit'] }})
                                                @endif
                                            </p>
                                        @endif
                                        
                                        @if(isset($notification->data['tanggal']))
                                            <p class="text-sm text-gray-600 mb-1">
                                                <span class="font-medium">Tanggal:</span> {{ $notification->data['tanggal'] }}
                                            </p>
                                        @endif
                                        
                                        <p class="text-xs text-gray-500 mt-2">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col items-end gap-2">
                                <!-- Delete Button -->
                                <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus notifikasi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors" title="Hapus Notifikasi">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>

                                @if(!$notification->read_at)
                                    <form action="{{ route('admin.notifications.read', $notification->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-xs text-blue-600 hover:underline">
                                            Tandai dibaca
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-400">✓ Dibaca</span>
                                @endif
                                
                                @if(isset($notification->data['peminjaman_id']))
                                    <form action="{{ route('admin.peminjaman.approve', $notification->data['peminjaman_id']) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-xs bg-green-600 text-white h-10 px-3 py-3 rounded lg:hover:bg-green-700 transition-all lg:hover:duration-500 lg:hover:-translate-y-1">
                                            Approved
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('admin.peminjaman.reject', $notification->data['peminjaman_id']) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-xs bg-red-600 text-white h-10 px-3 py-3 rounded lg:hover:bg-red-700 transition-all lg:hover:duration-500 lg:hover:-translate-y-1">
                                            Rejected
                                        </button>    
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                  
                    <div class="bg-white rounded-2xl shadow p-12 text-center col-span-full">
                        <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <h3 class="text-xl font-bold text-gray-600 mb-2">Tidak Ada Notifikasi</h3>
                        <p class="text-gray-500">Anda belum memiliki notifikasi</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($notifications->count() > 0)
                <div class="mt-6">
                    {{ $notifications->links() }}
                </div>
            @endif
        </section>
    </main>
</body>

</html>