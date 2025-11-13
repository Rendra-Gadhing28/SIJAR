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
    @include('layouts.navigation')
    <main class="pt-28 px-6 md:px-12">
        <section class="max-w-2xl mx-auto bg-white rounded-2xl shadow p-6 text-center">
            <h2 class="text-xl font-bold mb-4">Daftar Barang</h2>
            @forelse ($data as $item)
            <div id="list-barang">
                <div class=""> 
                {{-- Tambahkan card dari setiap barang --}}
                <h3>{{$item->nama_item}}</h3>
                <img src="{{ asset('storage/encrypted/'.$item->foto_barang) }}" width="400" height="400">
                {{-- di file css tambahkan properti cover fit, --}}
                <p>{{ $item->kode_unit }}</p>
                <p>{{ $item->id }}</p>
                </div>
               
               

            @empty
                <p>data kosong</p>
            @endforelse
            </div>
            </div>
        </section>
    </main>

</body>

</html>