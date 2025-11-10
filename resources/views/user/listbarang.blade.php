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
            <div id="list-barang">
                <p class="text-gray-500 italic">Belum ada data</p>
            </div>
        </section>
    </main>

</body>

</html>