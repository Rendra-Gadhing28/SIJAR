<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tambah Barang</title>
    @vite('resources/css/app.css')

</head>
<body>
    <header class="">
        @include('layouts.navigationadmin')
    </header>
    <main class="mt-4 py-32">
        <form action="{{ route('admin.barang.store') }}" class="">
            <div class="">
                <label for="" class="">Nama Barang</label>
                <input type="text" class="">
                <br>
                <label for="">Jenis Barang</label>
                <input type="text">
                <br>
                <label for="" class="">Kategori Barang</label>
                <input type="text" class="">
            </div>
        </form>
    </main>
    <footer class=""></footer>
</body>
</html>

