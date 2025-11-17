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

<body class="bg-gradient-to-b from-gray-200 to-white flex flex-col min-h-screen font-['Poppins'] pb-32 md:pb-0 lg:pb-0 ">
    <header>
<<<<<<< Updated upstream
        @include('layouts.navigation')
=======
    @include('layouts.navigation')
    @include('layouts.navmobile')
>>>>>>> Stashed changes
    </header>
    <main class="pt-28 px-6 md:px-12">
        <form action="{{ route('barang.index') }}" method="GET" class="mb-4 flex gap-2">
            <section class="max-w-5xl mx-auto bg-white rounded-2xl shadow p-6">
                <form action="{{ route('barang.index') }}" method="GET" class="mb-4 flex gap-2">
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari barang..."
                        class="px-3 py-2 border rounded-lg w-1/2">
                    <button class="px-3 py-2 hover:bg-blue-600 rounded-lg">
                        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </button>
                </form>

                @if(request('search'))
                    <p class="mb-3 text-gray-600">
                        Hasil pencarian: <b>{{ request('search') }}</b>
                        ({{ $barangjurusan }} ditemukan)
                    </p>
                @endif

                <h2 class="text-xl font-bold mb-4">Daftar Barang</h2>

                <span class="text-2xl font-bold">Jurusan: {{ Auth::user()->kategori->nama_kategori }}</span>
                <span class="text-xl font-bold block">Total: {{ $barangjurusan }}</span>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">

                    @forelse ($data as $item)
                        <div class="shadow-md p-4 rounded-2xl bg-gray-50 hover:shadow-lg transition">
                            <h3 class="text-xl font-bold mb-1">{{ $item->nama_item }}</h3>

                            <span class="text-green-500 flex justify-end mb-2">
                                Status: {{ $item->status_item }}
                            </span>

                            <div class="flex items-center justify-center">
                                <img class="object-cover w-full h-48 rounded-lg"
                                    src="{{ asset('storage/encrypted/' . $item->foto_barang) }}">
                            </div>

                            <p class="mt-2">Kode: {{ $item->kode_unit }}</p>
                            <p>Unit: {{ $item->id }}</p>
                            <p>Jenis: {{ $item->jenis_item }}</p>
                        </div>
                    @empty
                        <p class="col-span-full text-center text-gray-500">Data kosong plong</p>
                    @endforelse
                </div>
                @include('layouts.navmobile')
                {{-- PAGINATION --}}
                - <div class="mt-6">
                    {{ $data->links() }}
                </div> 
            </section>

        </form>
    </main>

</body>

</html>