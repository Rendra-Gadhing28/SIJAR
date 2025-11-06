<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="{{ asset('/images/logo_sijar.png') }}">
    <title>Login SIJAR</title>
    @vite('resources/css/app.css')
<<<<<<< HEAD
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@400;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gradient-to-b from-gray-200 to-white flex flex-col min-h-screen font-['Roboto_Slab']">
=======
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gradient-to-b from-gray-200 to-white flex flex-col min-h-screen font-['Poppins']">
>>>>>>> eae2b90 (login dan homepage)

    @include('layouts.navigation')

    <div class="flex flex-col items-center justify-center flex-grow">
        <div
            class="bg-gradient-to-b from-gray-100 to-gray-200 shadow-[0_-8px_20px_rgba(0,0,0,0.3)] rounded-2xl p-8 w-80 sm:w-96">

            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="Logo SIJAR" class="w-16 h-16">
            </div>

            <h2
                class="text-center text-3xl font-extrabold drop-shadow-[0_4px_3px_rgba(0,0,0,0.4)] mb-8 bg-gradient-to-r from-[#444DCD] via-[#2D3492] to-[#171C59] text-transparent bg-clip-text">
                LOGIN
            </h2>

<<<<<<< HEAD

            <form action="/home" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="code_kelas" class="block text-sm font-bold text-gray-800">Code Kelas</label>
                    <input id="code_kelas" name="code_kelas" type="text" required
                        class="mt-1 block w-full rounded-md bg-gradient-to-r from-[#B7DBFF] via-[#8FADCA] to-[#3F4F5F] px-3 py-2 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400 shadow-inner"
                        placeholder="Masukkan kode kelas">
=======
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-bold text-gray-800">Email</label>
                    <input id="email" name="email" type="text" required
                        class="mt-1 block w-full rounded-md bg-gradient-to-r from-[#B7DBFF] via-[#8FADCA] to-[#3F4F5F] px-3 py-2 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400 shadow-inner"
                        placeholder="Masukkan email anda">
>>>>>>> eae2b90 (login dan homepage)
                </div>

                <div>
                    <label for="password" class="block text-sm font-bold text-gray-800">Password</label>
                    <input id="password" name="password" type="password" required
                        class="mt-1 block w-full rounded-md bg-gradient-to-r from-[#B7DBFF] via-[#8FADCA] to-[#3F4F5F] px-3 py-2 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400 shadow-inner"
                        placeholder="Masukkan password">
                </div>

                <div class="flex items-center space-x-2">
                    <label for="remember" class="text-sm text-gray-700">Ingat aku</label>
                    <input id="remember" name="remember" type="checkbox"
                        class="h-4 w-4 border-gray-300 rounded text-blue-600 focus:ring-blue-500">
                </div>

                <button type="submit"
<<<<<<< HEAD
                    class="w-full bg-gradient-to-r from-[#99E1FF] via-[#31A6D7] to-[#1683B1] text-white font-bold py-2 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                    <a href="{{ route('user.homepage') }}" class="block text-center">Submit</a>
                </button>
            </form>
=======
                    class="w-full bg-gradient-to-r from-[#99E1FF] via-[#31A6D7] to-[#1683B1] text-white font-bold py-2 rounded-lg shadow-md hover:shadow-lg transition duration-300 block text-center">
                    Submit
                </button>
            </form>
            @if(session('success'))
                <div class="text-green-600 font-bold mt-2 text-center">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="text-red-600 font-bold mt-2 text-center">
                    {{ $errors->first() }}
                </div>
            @endif

>>>>>>> eae2b90 (login dan homepage)
        </div>
    </div>

</body>

</html>