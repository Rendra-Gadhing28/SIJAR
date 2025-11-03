<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login SIJAR</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@400;700&display=swap" rel="stylesheet">
</head>
 @todo fix the color later
<body class="bg-gradient-to-b from-gray-200 to-white flex flex-col min-h-screen font-['Roboto_Slab']">

    @include('layouts.navigation')

    <div class="flex flex-col items-center justify-center flex-grow">
        <div
            class="bg-gradient-to-b from-gray-100 to-gray-200 shadow-[0_-8px_20px_rgba(0,0,0,0.3)] rounded-2xl p-8 w-80 sm:w-96">

            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="Logo SIJAR" class="w-16 h-16">
            </div>

            <h2 class="text-center text-3xl font-extrabold text-[#1E3A8A] drop-shadow-[0_4px_3px_rgba(0,0,0,0.4)] mb-8">
                LOGIN
            </h2>

            <form action="/home" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="code_kelas" class="block text-sm font-bold text-gray-800">Code Kelas</label>
                    <input id="code_kelas" name="code_kelas" type="text" required
                        class="mt-1 block w-full rounded-md bg-gradient-to-r from-[#6B7280] to-[#374151] px-3 py-2 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400 shadow-inner"
                        placeholder="Masukkan kode kelas">
                </div>

                <div>
                    <label for="password" class="block text-sm font-bold text-gray-800">Password</label>
                    <input id="password" name="password" type="password" required
                        class="mt-1 block w-full rounded-md bg-gradient-to-r from-[#6B7280] to-[#374151] px-3 py-2 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400 shadow-inner"
                        placeholder="Masukkan password">
                </div>

                <div class="flex items-center space-x-2">
                    <input id="remember" name="remember" type="checkbox"
                        class="h-4 w-4 border-gray-300 rounded text-blue-600 focus:ring-blue-500">
                    <label for="remember" class="text-sm text-gray-700">Ingat aku</label>
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-[#2563EB] to-[#0EA5E9] text-white font-bold py-2 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                    Submit
                </button>
            </form>
        </div>
    </div>

</body>

</html>