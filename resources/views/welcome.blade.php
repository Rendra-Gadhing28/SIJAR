<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/svg+xml" href="{{ asset('/images/logo_sijar.png') }}">
    <title>SIJAR</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@600;800&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100">
    @include('layouts.navigation')

    <section class="text-center mt-10">
        <h1 class="text-4xl font-extrabold text-transparent bg-clip-text inline-block" style="font-family: 'Roboto Slab', serif; background-image: linear-gradient(90deg, #444DCD 0%, #2D3492 61%, #171C59 100%);
                    text-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);">
            Welcome to SIJAR
        </h1>
    </section>

    <div class="text-center mt-20">
        <h2 class="text-xl font-bold" style="font-family: Roboto">
            peminjaman barang jurusan masih pakai kertas?? sudah saatnya kamu beralih ke SIJAR
        </h2>
    </div>

    <div class="flex justify-center items-center mt-16">
        <a href="{{ route('login') }}" class="text-black font-semibold rounded-full text-sm px-6 py-2.5 text-center shadow-md 
            bg-gradient-to-b from-[#99E1FF] via-[#31A6D7] to-[#1683B1]
            hover:brightness-110 transition duration-300 inline-block">
            Login
        </a>
    </div>
</body>

</html>