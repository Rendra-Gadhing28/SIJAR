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

<body class="bg-gradient-to-b from-gray-200 to-white flex flex-col min-h-screen font-['Poppins']">
    <header>
    @include('layouts.navigation')
    </header>
    <main class="pt-28 px-6 md:px-12">
        <form action="{{ route('barang.index') }}" method="GET" class="mb-4 flex gap-2"> 
        <section class="max-w-2xl mx-auto bg-white rounded-2xl shadow p-6 text-center">
            <div>
            <input type="search" name="search" value="{{ request('search') }}"  placeholder="Cari barang..."   class="px-3 py-2 border rounded-lg w-1/2">
            <button class="px-3 py-2 hover:bg-blue-600 rounded-lg"> <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
</svg>
        </button>
        @if(request('search'))
    <p class="mb-3 text-gray-600">
        Hasil pencarian untuk: <b>{{ request('search') }}</b>  
        ({{$barangjurusan}} ditemukan)
    </p>
@endif
            </div>
            <h2 class="text-xl font-bold mb-4">Daftar Barang</h2>
             <span class="flex align-top justify-start text-2xl font-bold font-poppin">Jurusan : {{ Auth::user()->kategori->nama_kategori;}}</span>
             <span class="flex align-top justify-start text-xl font-bold font-poppin">Total : {{$barangjurusan}}</span>
            @forelse ($data as $item)
            <div id="list-barang" class="w-full flex flex-wrap">
                <div class="w-full mx-auto px-4 h-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"> 
                    <div class="w-auto h-auto shadow-md p-4 rounded-2xl ">
                     <h3 class="text-xl font-bold ">{{$item->nama_item}}</h3>
                    <span class="text-green-500 flex align-top justify-end">Status : {{$item->status_item }}</span>
                {{-- Tambahkan card dari setiap barang --}}
                <div class="flex items-center justify-center w-full relative">
                    <img class="object cover" src="{{ asset('storage/encrypted/'.$item->foto_barang) }}" width="400" height="400">
                </div>
                {{-- di file css tambahkan properti cover fit, --}}
                <p>Kode : {{ $item->kode_unit }}</p>
                <p>Unit : {{ $item->id }}</p>
                <p>Jenis : {{ $item->jenis_item }}</p>
                </div>
                </div>
               
               

            @empty
                <p>data kosong plong</p>
            @endforelse
            </div>
            </div>
        </section>
        </form>
    </main>

</body>

</html>