<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/svg+xml" href="{{ asset('/images/logo_sijar.png') }}">
    <title>SIJAR</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        :root {
            --c1: #444DCD;
            --c2: #2D3492;
            --c3: #171C59;

            --button: #99E1FF;
        }
    </style>
</head>

<body class="bg-gray-100">
    <header class="flex items-center ">
        <div class="block md:hidden">
            @include('layouts.navmobile')
        </div>
        <div class=" md:block">
            @include('layouts.navigation')
        </div>
    </header>
    <main class="w-screen h-screen ">

        <section class="text-center mt-28 sm:text-wrap lg:mt-40">
            <h1 class="mt-4 text-5xl font-extrabold bg-gradient-to-r from-[--c1] via-[#2D3492] to-[#171C59] text-transparent bg-clip-text inline-block lg:text-7xl"
                style="font-family: 'Roboto Slab';
                    text-shadow: 0 4px 10px rgba(0, 0, 0, 0.25); ">
                Welcome to SIJAR
            </h1>

            <div class="relative mt-8 flex items-center justify-center lg:mt-20">
                <picture>
                    <img class=" w-screen object-cover opacity-50 rounded-sm lg:h-[50vh] md:h-[50vh]"
                        src="/images/snapan.jpg" alt="ini gambar smk8">
                </picture>

                <!-- Background transparan overlay -->
                <div class="absolute inset-0 flex items-center justify-center z-10">
                    <div class="text-center px-4">
                        <h2 class="text-white font-extrabold font-serif"
                            style="text-shadow: 0 0 10px rgba(0,0,0,0.5), 0 0 20px rgba(0,0,0,0.3);">
                            <span
                                class="block mb-4 text-3xl bg-gradient-to-tr from-[--c1] via-[--c2] to-[--c3] bg-clip-text text-transparent md:text-4xl">
                                Minjam barang jurusan masih pakai kertas??
                            </span><br>
                            <span class="text-xl font-light font-serif text-black md:text-2xl lg:text-4xl">
                                Sudah saatnya kamu beralih ke SIJAR. <br> Sistem Inventaris Peminjaman Barang Jurusan
                                berbasis website
                            </span>
                        </h2>
                    </div>
                </div>
            </div>


            <div class="flex justify-center items-center mt-16 lg:mt-28">
                <a href="{{ route('login') }}" class="w-1/4 h-[3rem] text-black font-semibold rounded-full text-sm px-6 py-3 text-center shadow-md lg:h-
            bg-gradient-to-b from-[#99E1FF] via-[#31A6D7] to-[#1683B1]
            hover:brightness-110 transition duration-300 inline-block">
                    Login
                </a>
            </div>
        </section>
    </main>
    <footer
        class="rounded-md ring-2 ring-slate-800 absolute flex items-end justify-center w-screen h-16 p-4 bg-white text-shadow-red shadow-sm">
        @ Production By : XI PPLG 3</footer>
</body>
</html>