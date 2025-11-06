<!DOCTYPE html>
<html lang="en">
<<<<<<< HEAD
=======

>>>>>>> eae2b90 (login dan homepage)
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="{{ asset('/images/logo_sijar.png') }}">
    <title>Login SIJAR</title>
    @vite('resources/css/app.css')
<<<<<<< HEAD
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <card></card>
</body>
=======
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gradient-to-b from-gray-200 to-white flex flex-col min-h-screen font-['Poppins']">
    @include('layouts.navigation')
    <h1>Selamat datang, {{ Auth::user()->name }}!</h1>
    <main class="flex flex-col items-center flex-grow px-5 py-6">

        <h2 class="text-2xl font-extrabold text-gray-800 mb-4 text-center">Barang</h2>

        <div class="bg-gray-200 rounded-2xl p-5 w-80 shadow-inner">
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-300 rounded-lg p-3 text-center shadow-md">
                    <p class="text-sm font-semibold">Dipinjam</p>
                    <p class="text-3xl font-bold">0</p>
                </div>
                <div class="bg-gray-300 rounded-lg p-3 text-center shadow-md">
                    <p class="text-sm font-semibold">Dikembalikan</p>
                    <p class="text-3xl font-bold">0</p>
                </div>
            </div>
        </div>

        <div class="w-full mt-6">
            <p class="text-sm font-semibold text-left">List barang yang dipinjam :</p>
        </div>

    </main>

    {{-- <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>> --}}
</body>

>>>>>>> eae2b90 (login dan homepage)
</html>