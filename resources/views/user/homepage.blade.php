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
    @include('layouts.navigation')
    <main class="pt-28 px-6 md:px-12">
        <section class="max-w-4xl mx-auto bg-white rounded-2xl shadow p-6 text-center">
            <h2 class="text-2xl font-bold mb-6">Barang</h2>
            <div class="flex justify-center gap-6">
                <div class="bg-gray-100 rounded-lg p-4 w-32 text-center shadow">
                    <p class="font-semibold">Dipinjam</p>
                    <p class="text-2xl font-bold">0</p>
                </div>
                <div class="bg-gray-100 rounded-lg p-4 w-32 text-center shadow">
                    <p class="font-semibold">Dikembalikan</p>
                    <p class="text-2xl font-bold">0</p>
                </div>
            </div>
        </section>
        <section class="max-w-4xl mx-auto mt-8 bg-white rounded-2xl shadow p-6">
            <p class="font-medium mb-2">List barang yang dipinjam:</p>
            <div id="list-barang">
                <p class="text-gray-500 italic">Belum ada data</p>
            </div>
        </section>
    </main>


    {{-- <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>> --}}
</body>

</html>