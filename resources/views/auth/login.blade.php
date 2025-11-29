<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="{{ asset('/images/logo_sijar.png') }}">
    <title>Login SIJAR</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gradient-to-b from-gray-200 to-white min-h-screen font-['Poppins'] overflow-y-auto">
    <header>
    @include('layouts.navigation')
    </header>
    <main class="w-screen h-screen mt-28">
    <div class="flex flex-col items-center justify-center flex-grow mt-36 md:mt-40 lg:mt-48 px-4">
        <div
            class="bg-gradient-to-b from-gray-100 to-gray-200 shadow-[0_-8px_20px_rgba(0,0,0,0.3)] rounded-2xl p-8 w-full max-w-sm mx-4 sm:max-w-md">

            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="Logo SIJAR" class="w-16 h-16">
            </div>

            <h2
                class="text-center text-3xl font-extrabold drop-shadow-[0_4px_3px_rgba(0,0,0,0.4)] mb-8 bg-gradient-to-r from-[#444DCD] via-[#2D3492] to-[#171C59] text-transparent bg-clip-text">
                LOGIN
            </h2>

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-bold text-gray-800">Email</label>
                    <input id="email" name="email" type="text" required
                        class="mt-1 block w-full rounded-md bg-gradient-to-r from-[#B7DBFF] via-[#8FADCA] to-[#3F4F5F] px-3 py-2 text-white placeholder-gray-300 focus:outline-none focus:ring-0 focus:border-transparent shadow-inner"
                        placeholder="Masukkan email anda">
                </div>

                <div class="w-auto flex flex-col justify-center items-end">
                    <label for="password" class="block text-sm font-bold text-gray-800 self-start">Password</label>
                        <div class="flex justify-end absolute mt-6 mx-4 z-50">
                        <button id="btn" type="button" onclick="klik()" class="w-auto h-full px-2">
                        <img src="{{ asset('images/eye.svg') }}" alt="ini gambar mata" class="object-cover hidden z-10 rounded text-[#B7DBFF]" id="on">
                        <img src="{{ asset('images/eye-off.svg') }}" alt="ini gambar mata" class="object-cover z-10 rounded" id="off">
                        </button>
                        </div>
                    <input id="password" name="password" type="password" required
                        class="mt-1 block w-full rounded-md bg-gradient-to-r from-[#B7DBFF] via-[#8FADCA] to-[#3F4F5F] px-3 py-2 text-white placeholder-gray-300 focus:outline-none focus:ring-0 focus:border-transparent shadow-inner"
                        placeholder="Masukkan password">

                </div>

                <div class="flex items-center space-x-2">
                    <label for="remember" class="text-sm text-gray-700">Ingat aku</label>
                    <input id="remember" name="remember" type="checkbox"
                        class="h-4 w-4 border-gray-300 rounded text-blue-600 focus:ring-blue-500">
                </div>

                <button type="submit"
                    class="w-3/4 sm:w-1/2 mx-auto block bg-gradient-to-r from-[#99E1FF] via-[#31A6D7] to-[#1683B1] text-black font-bold py-2 rounded-lg shadow-md hover:shadow-lg transition duration-300 text-center">
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

        </div>
    </div>
    </main>
    <script>
        const button = document.getElementById['btn'].value;
        const eye = document.getElementById['on'].value;
        const eye_Off = document.getElementById['off'].value;
        const password = document.getElementById['password'].value

        function klik(){
            button.addEventListener('click', ()=>{
                if(password.type === 'password'){
                    password.type = 'text'
                    eye.style.display ='inline'
                    eye_Off.style.display = 'none'
                }

                else{
                    eye.style.display = 'none'
                    eye_Off.style.display = 'inline'
                    password.type = 'password'
                }
            })
        }
        
    </script>
</body>

</html>