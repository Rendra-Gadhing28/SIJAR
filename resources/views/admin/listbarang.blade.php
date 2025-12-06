<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Barang - {{ $kategori }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('/images/logo_sijar.png') }}">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-50 h-auto min-h-full font-sans antialiased">
    <header>
        @include('layouts.navigationadmin')
    </header>

    <main class="pt-24 px-4 md:px-8 pb-16 max-w-7xl h-full mx-auto mt-8 scroll-m-28">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $kategori }}</h1>
                <p class="text-gray-500 mt-1">Kelola inventaris barang jurusan</p>
            </div>
            <a href="{{ route('admin.barang.create') }}" 
               class="inline-flex items-center justify-center px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>Tambah Barang
            </a>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <!-- Tersedia -->
            <div class="bg-white rounded-xl p-5 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Tersedia</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $itemTersedia }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-lg"></i>
                    </div>
                </div>
            </div>

            <!-- Dipinjam -->
            <div class="bg-white rounded-xl p-5 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Dipinjam</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $itemDipinjam }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-yellow-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-hand-holding text-yellow-600 text-lg"></i>
                    </div>
                </div>
            </div>

            <!-- Rusak -->
            <div class="bg-white rounded-xl p-5 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Rusak</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $itemRusak }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
                    </div>
                </div>
            </div>

            <!-- Total -->
            <div class="bg-white rounded-xl p-5 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Total</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $barangjurusan }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-box text-blue-600 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="bg-white rounded-xl border border-gray-100 p-5 mb-6">
            <form method="GET" action="{{ route('admin.barang.index') }}">
                <div class="flex flex-col md:flex-row gap-3">
                    <!-- Search -->
                    <div class="flex-1">
                        <div class="relative">
                            <i class="fas fa-search absolute left-3.5 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" 
                                   name="search" 
                                   class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   placeholder="Cari nama barang atau kode unit..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>

                    <!-- Filter Status -->
                    <div class="md:w-56">
                        <select name="status_item" 
                                class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua Status</option>
                            <option value="tersedia" {{ request('status_item') == 'tersedia' ? 'selected' : '' }}>
                                Tersedia
                            </option>
                            <option value="dipinjam" {{ request('status_item') == 'dipinjam' ? 'selected' : '' }}>
                                Dipinjam
                            </option>
                            <option value="rusak" {{ request('status_item') == 'rusak' ? 'selected' : '' }}>
                                Rusak
                            </option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-2">
                        <button type="submit" 
                                class="px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            Filter
                        </button>
                        <a href="{{ route('admin.barang.index') }}" 
                           class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                            Reset
                        </a>
                    </div>
                </div>

                <!-- Active Filters -->
                @if(request('search') || request('status_item'))
                    <div class="flex flex-wrap items-center gap-2 mt-4 pt-4 border-t border-gray-100">
                        @if(request('search'))
                            <span class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-full">
                                Pencarian: "{{ request('search') }}"
                            </span>
                        @endif
                        @if(request('status_item'))
                            <span class="inline-flex items-center px-3 py-1 bg-purple-50 text-purple-700 text-xs font-medium rounded-full">
                                Status: {{ ucfirst(request('status_item')) }}
                            </span>
                        @endif
                        <span class="text-xs text-gray-500">{{ $barangjurusan }} barang ditemukan</span>
                    </div>
                @endif
            </form>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-start">
                <i class="fas fa-check-circle text-lg mr-3 mt-0.5"></i>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6 flex items-start">
                <i class="fas fa-exclamation-circle text-lg mr-3 mt-0.5"></i>
                <p class="text-sm">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Items Grid -->
        @if($data->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($data as $item)
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <!-- Image -->
                        <div class="relative h-48 bg-gray-100">
                            <img src="{{ asset('storage/encrypted/' . $item->foto_barang) }}" 
                                 class="w-full h-full object-cover p-4" 
                                 alt="{{ $item->nama_item }}">
                            
                            <!-- Status Badge -->
                            <div class="absolute top-3 right-3">
                                @if($item->status_item == 'tersedia')
                                    <span class="inline-flex items-center px-2.5 py-1 bg-green-500 text-white text-xs font-medium rounded-full">
                                        <i class="fas fa-check w-3 mr-1"></i>Tersedia
                                    </span>
                                @elseif($item->status_item == 'dipinjam')
                                    <span class="inline-flex items-center px-2.5 py-1 bg-yellow-500 text-white text-xs font-medium rounded-full">
                                        <i class="fas fa-hand-holding w-3 mr-1"></i>Dipinjam
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 bg-red-500 text-white text-xs font-medium rounded-full">
                                        <i class="fas fa-exclamation-triangle w-3 mr-1"></i>Rusak
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3 line-clamp-1">{{ $item->nama_item }}</h3>
                            
                            <div class="space-y-2 text-sm text-gray-600 mb-4">
                                <div class="flex items-center">
                                       <p class="text-gray-400">Jenis :</p>
                                    <span>{{ $item->jenis_item }}</span>
                                </div>
                                <div class="flex items-center">
                                       <p class="text-gray-400">Unit :</p>
                                    <span class="font-mono text-xs">{{ $item->kode_unit }}</span>
                                </div>
                                <div class="flex items-center">
                                     <p class="text-gray-400">Jurusan :</p>
                                    <span>{{ $item->kategori_jurusan->nama_kategori ?? '-' }}</span>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2">
                                 <form action="{{ route('admin.barang.setTersedia', $item->id) }}" 
                                      method="POST" 
                                      class="flex-1">
                                    @csrf
                                    @method('PUT')
                                <button type="submit"
                                   class="flex-1  w-full py-2 text-center text-xs font-medium text-green-600 bg-green-50 rounded-lg hover:bg-green-100 transition-colors hover:duration-300">
                                    <i class="fas fa-edit mr-1"></i>Tersedia
                                </button>
                                 </form>
                                <form action="{{ route('admin.barang.setRusak', $item->id) }}" 
                                      method="POST" 
                                      class="flex-1">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" 
                                            class="w-full py-2 text-xs font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors hover:duration-300">
                                        <i class="fas fa-exclamation-triangle w-3 mr-1"></i>Rusak
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $data->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-xl border border-gray-100 p-12 text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-inbox text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak ada barang</h3>
                
                @if(request('search'))
                    <p class="text-gray-500 mb-6 text-sm">
                        Tidak ditemukan barang dengan kata kunci <strong>"{{ request('search') }}"</strong>
                    </p>
                    <a href="{{ route('admin.barang.index') }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-redo mr-2"></i>Reset Filter
                    </a>
                @else
                    <p class="text-gray-500 mb-6 text-sm">Belum ada barang yang ditambahkan ke sistem</p>
                    <a href="{{ route('admin.barang.create') }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Tambah Barang Pertama
                    </a>
                @endif
            </div>
        @endif
    </main>

</body>
</html>