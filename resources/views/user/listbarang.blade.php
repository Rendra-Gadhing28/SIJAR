<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="{{ asset('/images/logo_sijar.png') }}">
    <title>List Barang</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gradient-to-b from-gray-200 to-white flex flex-col min-h-screen font-['Poppins'] pb-32 md:pb-0 lg:pb-0">
    <header>
        @include('layouts.navigation')
        @include('layouts.navmobile')
    </header>
    
    <main class="pt-28 px-6 md:px-12">
        <section class="max-w-5xl mx-auto bg-white rounded-2xl shadow-lg p-6 md:p-8">

            <div class="mb-8">
                <form action="{{ route('barang.index') }}" method="GET" class="relative">
                    <div class="relative group">
                        <input 
                            type="search" 
                            name="search" 
                            id="searchInput"
                            value="{{ request('search') }}" 
                            placeholder="Cari barang berdasarkan nama, kode, atau jenis..."
                            class="w-full px-5 py-4 pl-14 pr-32 text-gray-700 bg-gray-50 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 focus:bg-white transition-all duration-300 placeholder:text-gray-400"
                        >

                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 transition-colors duration-300">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </div>

                        <button 
                            type="submit"
                            class="absolute right-2 top-1/2 -translate-y-1/2 px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-blue-700 transform hover:scale-105 transition-all duration-300 shadow-md hover:shadow-lg"
                        >
                            Cari
                        </button>

                        @if(request('search'))
                        <a 
                            href="{{ route('barang.index') }}"
                            id="clearButton"
                            class="absolute right-28 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 transition-colors duration-300"
                            title="Clear search"
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                        @endif
                    </div>

                    @if(request('search'))
                    <div class="mt-4 px-4 py-3 bg-blue-50 border-l-4 border-blue-400 rounded-lg animate-slideDown">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-gray-700">
                                Hasil pencarian untuk <span class="font-bold text-blue-600">"{{ request('search') }}"</span>
                                <span class="ml-2 px-2 py-1 bg-blue-200 text-blue-800 rounded-full text-sm font-semibold">{{ $barangjurusan }} ditemukan</span>
                            </p>
                        </div>
                    </div>
                    @endif
                </form>

                <div class="mt-3 flex items-center justify-between text-sm text-gray-500">
                    <span id="searchHint" class="opacity-0 transition-opacity duration-300">
                        Tips: Gunakan kata kunci spesifik untuk hasil lebih akurat
                    </span>
                </div>
            </div>

            <div class="mb-6 pb-4 border-b-2 border-gray-100">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Daftar Barang</h2>
                <div class="flex items-center gap-4 text-gray-600">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span class="font-semibold">{{ Auth::user()->kategori->nama_kategori }}</span>
                    </span>
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span>Total: <span class="font-bold text-gray-800">{{ $barangjurusan }}</span> item</span>
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @forelse ($data as $item)
                    <div class="shadow-md p-4 rounded-2xl bg-gray-50 hover:shadow-lg transition">
                        <h3 class="text-xl font-bold mb-1">{{ $item->nama_item }}</h3>
                        <span class="text-green-500 flex justify-end mb-2">
                            Status: {{ $item->status_item }}
                        </span>
                        <div class="flex items-center justify-center">
                            <img class="object-cover w-full h-48 rounded-lg"
                                src="{{ asset('storage/encrypted/' . $item->foto_barang) }}" alt="{{ $item->nama_item }}">
                        </div>
                        <p class="mt-2">Kode: {{ $item->kode_unit }}</p>
                        <p>Unit: {{ $item->id }}</p>
                        <p>Jenis: {{ $item->jenis_item }}</p>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <p class="text-gray-500 text-lg">Tidak ada barang ditemukan</p>
                        @if(request('search'))
                        <p class="text-gray-400 mt-2">Coba gunakan kata kunci lain</p>
                        @endif
                    </div>
                @endforelse
            </div>

            @if($data->hasPages())
            <div class="mt-8 pt-6 border-t-2 border-gray-100">
                <nav class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-sm text-gray-600">
                        Menampilkan 
                        <span class="font-semibold text-gray-800">{{ $data->firstItem() }}</span>
                        sampai
                        <span class="font-semibold text-gray-800">{{ $data->lastItem() }}</span>
                        dari
                        <span class="font-semibold text-gray-800">{{ $data->total() }}</span>
                        hasil
                    </div>

                    <div class="flex items-center gap-2">
                        {{-- Previous Button --}}
                        @if ($data->onFirstPage())
                            <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </span>
                        @else
                            <a href="{{ $data->previousPageUrl() }}" 
                               class="px-4 py-2 bg-white border-2 border-gray-200 text-gray-700 rounded-lg hover:border-blue-400 hover:text-blue-600 transition-all duration-300 transform hover:scale-105">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </a>
                        @endif

                        <div class="hidden sm:flex items-center gap-1">
                            @foreach ($data->getUrlRange(1, $data->lastPage()) as $page => $url)
                                @if ($page == $data->currentPage())
                                    <span class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-bold rounded-lg shadow-md">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" 
                                       class="px-4 py-2 bg-white border-2 border-gray-200 text-gray-700 rounded-lg hover:border-blue-400 hover:text-blue-600 transition-all duration-300 transform hover:scale-105">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach

                        <div class="sm:hidden px-4 py-2 bg-blue-50 text-blue-700 rounded-lg font-semibold">
                            {{ $data->currentPage() }} / {{ $data->lastPage() }}
                        </div>

                        @if ($data->hasMorePages())
                            <a href="{{ $data->nextPageUrl() }}" 
                               class="px-4 py-2 bg-white border-2 border-gray-200 text-gray-700 rounded-lg hover:border-blue-400 hover:text-blue-600 transition-all duration-300 transform hover:scale-105">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        @else
                            <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        @endif
                    </div>
                </nav>

                <div class="mt-4 flex items-center justify-center gap-3">
                    <span class="text-sm text-gray-500">Quick jump:</span>
                    <form action="{{ route('barang.index') }}" method="GET" class="flex items-center gap-2">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        <input 
                            type="number" 
                            name="page" 
                            min="1" 
                            max="{{ $data->lastPage() }}" 
                            placeholder="Hal"
                            class="w-20 px-3 py-1 text-sm border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-400"
                        >
                        <button 
                            type="submit"
                            class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-blue-500 hover:text-white transition-all duration-300"
                        >
                            Go
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </section>
    </main>
    <script>

        const searchInput = document.getElementById('searchInput');
        const searchHint = document.getElementById('searchHint');

        searchInput.addEventListener('focus', () => {
            searchHint.classList.remove('opacity-0');
            searchHint.classList.add('opacity-100');
        });

        searchInput.addEventListener('blur', () => {
            searchHint.classList.remove('opacity-100');
            searchHint.classList.add('opacity-0');
        });

        const clearButton = document.getElementById('clearButton');
        if (clearButton) {
            clearButton.addEventListener('click', (e) => {
                e.preventDefault();
                searchInput.value = '';
                searchInput.form.submit();
            });
        }

        document.querySelectorAll('nav a[href*="page="]').forEach(link => {
            link.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });

        document.querySelector('form').addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            button.innerHTML = `
                <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            `;
            button.disabled = true;
        });
    </script>

    <style>
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slideDown {
            animation: slideDown 0.3s ease-out;
        }

        button, a, input {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</body>

</html>