<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/svg+xml" href="{{ asset('/images/logo_sijar.png') }}">
    <title>Tambah Barang - SIJAR</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gradient-to-b from-gray-200 to-white flex flex-col min-h-screen font-['Poppins']">
    <header>
        @include('layouts.navigationadmin')
    </header>

    <main class="pt-32 pb-12 px-6">
        <div class="flex flex-col justify-center items-center">
            <div class="w-full max-w-4xl bg-gradient-to-br from-slate-200 via-slate-300 to-slate-400 rounded-2xl shadow-2xl p-8">
                
                <!-- Header -->
                <div class="flex items-center gap-4 ">
                    <a href="{{ route('admin.barang.index') }}" class="text-blue-700 hover:text-blue-900">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 self-center text-center mb-4">Tambah Barang</h1>
                <!-- Alert Errors -->
                @if($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg" role="alert">
                        <p class="font-bold mb-2">Terjadi Kesalahan!</p>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form -->
                <form action="{{ route('admin.barang.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="">
                        <!-- Nama Barang -->
                        <div class="relative mb-4">
                            <label for="nama_barang" class="block font-bold text-lg text-gray-800 mb-2">
                                Nama Barang <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="nama_barang" 
                                   id="nama_barang" 
                                   value="{{ old('nama_barang') }}"
                                   class="w-full px-4 py-3 bg-gradient-to-r from-[#B2C7DC] via-[#8FADCA] to-[#5882AC] border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-600 text-gray-900 font-medium"
                                   placeholder="Contoh: Proyektor Epson EB-X41" 
                                   required>
                        </div>

                        <!-- Kategori Jurusan -->
                        <div class="mb-4">
                            <label for="kategori_jurusan_id" class="block font-bold text-lg text-gray-800 mb-2">
                                Kategori Jurusan <span class="text-red-500">*</span>
                            </label>
                            <select name="kategori_jurusan_id" 
                                    id="kategori_jurusan_id" 
                                    class="w-full px-4 py-3 bg-[#B2C7DC] from-[#B2C7DC] via-[#8FADCA] to-[#5882AC] rounded-lg text-gray-900 font-medium  focus:outline-none focus:ring-2 focus:ring-blue-400"
                                    required>
                                @forelse ($kategori as $kategoris)
                                    <option value="{{ $kategoris->id }}" class="rounded-lg border-collapse ring-0 ">
                                        {{ $kategoris->nama_kategori }}
                                    </option>
                                @empty
                                    <option disabled>Tidak ada kategori</option>
                                @endforelse
                            </select>
                            <p class="text-xs text-gray-600 mt-1">Kode unit akan digenerate otomatis</p>
                        </div>

                        <!-- Jenis Barang -->
                     
                            <label for="jenis_barang" class="block font-bold text-lg text-gray-800 mb-2">
                                Jenis Barang <span class="text-red-500">*</span>
                            </label>
                            <input type="text" placeholder="Alat Praktik, Alat Kesehatan, Alat Elektronik dll." class="w-full px-4 py-3 bg-gradient-to-r from-[#B2C7DC] via-[#8FADCA] to-[#5882AC] border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-600 text-gray-900 font-medium">
                            
                        </div>

                        <!-- Foto Barang -->
                        <div>
                            <label for="foto_barang" class="block font-bold text-lg text-gray-800 mb-2">
                                Foto Barang <span class="text-red-500">*</span>
                            </label>
                            <input type="file" 
                                   name="foto_barang" 
                                   id="foto_barang" 
                                   accept="image/*"
                                   class="w-full px-4 py-3 bg-gradient-to-r from-[#B2C7DC] via-[#8FADCA] to-[#5882AC] border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 font-medium file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gradient-to-r file:from-sky-300 file:via-sky-500 file:to-sky-600 file:text-white file:cursor-pointer hover:file:bg-blue-700"
                                   onchange="previewImage(event)"
                                   required>
                            <p class="text-xs text-gray-600 mt-1">JPG, PNG, JPEG. Max 2MB</p>
                        </div>

                        <!-- Preview Foto -->
                        <div id="preview-container" class="hidden">
                            <label class="block font-bold text-lg text-gray-800 mb-2">Preview Foto</label>
                            <div class="relative">
                                <img id="preview-image" src="" alt="Preview" class="max-w-full h-48 object-cover rounded-lg shadow-lg border-4 border-white">
                                <button type="button" onclick="removePreview()" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-2 hover:bg-red-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                    <!-- Info Box -->
                    <div class="bg-blue-100 border-l-4 border-blue-500 p-4 rounded-r-lg">
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="text-sm text-blue-800">
                                <p class="font-bold mb-1">Kode Unit Otomatis</p>
                                <p>Sistem akan generate kode unit secara otomatis berdasarkan kategori jurusan yang dipilih.</p>
                                <p class="font-semibold mt-1">Contoh: RPL001, TKJ002, MM003</p>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-4 pt-4">
                        <button type="submit" 
                                class="flex-1 bg-gradient-to-r from-sky-300 via-sky-500 to-sky-600 text-white font-bold py-4 rounded-lg hover:from-sky-700 hover:to-sky-800 transition shadow-lg hover:shadow-xl transform hover:transition-all hover:duration-300 hover:-translate-y-1">
                            <span class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Barang
                            </span>
                        </button>
                        <a href="{{ route('admin.barang.index') }}" 
                           class="flex-1 bg-gradient-to-r from-gray-400 to-gray-500 text-white font-bold py-4 rounded-lg hover:from-gray-500 hover:to-gray-600 transition shadow-lg hover:shadow-xl text-center transform hover:transition-all hover:duration-300 hover:-translate-y-1">
                            <span class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Batal
                            </span>
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </main>

    <script>
        // Preview Image
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-container').classList.remove('hidden');
                    document.getElementById('preview-image').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        // Remove Preview
        function removePreview() {
            document.getElementById('preview-container').classList.add('hidden');
            document.getElementById('foto_barang').value = '';
        }

        // Auto-generate placeholder kode unit
        document.getElementById('kategori_jurusan_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const kategoriText = selectedOption.text;
            const prefix = kategoriText.substring(0, 3).toUpperCase();
            
            // Show info about generated code
            console.log('Kode unit akan dimulai dengan: ' + prefix);
        });
    </script>
</body>
</html
