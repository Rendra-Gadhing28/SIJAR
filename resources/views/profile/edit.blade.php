<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="{{ asset('/images/logo_sijar.png') }}">
    <title>Edit Profile</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gradient-to-b from-gray-200 to-white flex flex-col min-h-screen font-['Poppins']">
    <header>
    @if(auth()->check() && auth()->user()->role === 'admin')
        @include('layouts.navigationadmin')
    @else
        @include('layouts.navigation')
    @endif

 
    </header>

    <main class="pt-28 px-6 md:px-12">
        <section class="max-w-2xl mx-auto bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-center mb-8 text-gray-800">Edit Profile</h2>
            
            @auth
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PATCH')

                {{-- Avatar Upload --}}
                <div class="flex flex-col items-center mb-6">
                    @if(Auth::user()->profile)
                        <img src="{{ asset('storage/avatars/' . Auth::user()->profile) }}" 
                             alt="Avatar" 
                             id="avatar-preview"
                             class="w-24 h-24 rounded-full object-cover border-4 border-gray-200 mb-4">
                    @else
                        <div id="avatar-preview" class="w-24 h-24 rounded-full bg-gray-300 flex items-center justify-center border-4 border-gray-200 mb-4">
                            <span class="text-4xl font-bold text-gray-600">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                    @endif
                    <label for="profile" class="cursor-pointer px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200">
                        Pilih Foto
                    </label>
                    <input type="file" id="profile" name="profile" class="hidden" accept="image/*" onchange="previewImage(event)">
                    @error('avatar')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm text-gray-500 mb-1">Nama</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', Auth::user()->name) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-800 @error('name') border-red-500 @enderror"
                           required>
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm text-gray-500 mb-1">Email</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', strtolower(Auth::user()->email)) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-800 @error('email') border-red-500 @enderror"
                           required>
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Phone --}}
                <div>
                    <label for="telepon" class="block text-sm text-gray-500 mb-1">Telepon</label>
                    <input type="text" 
                           id="telepon" 
                           name="telepon" 
                           value="{{ old('telepon', Auth::user()->telepon) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-800"
                           placeholder="081234567890">
                    @error('telepon')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
              
                {{-- Jurusan (Read Only) --}}
                @if(Auth::user()->kategori)
                <div>
                    <label class="block text-sm text-gray-500 mb-1">Jurusan</label>
                    <input type="text" 
                           value="{{ Auth::user()->kategori->nama_kategori }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600"
                           disabled>
                </div>
                @endif

                {{-- Action Buttons --}}
                <div class="flex gap-4 justify-center pt-4">
                    <button type="submit" 
                            class="px-6 py-2.5 bg-gray-800 text-white rounded-lg font-medium hover:bg-gray-700">
                        Simpan
                    </button>
                    <a href="{{ route('profile.index') }}" 
                       class="px-6 py-2.5 bg-white border-2 border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 inline-block text-center">
                        Batal
                    </a>
                </div>
            </form>
            @endauth
        </section>

        {{-- Change Password Section --}}
        @auth
        <section class="max-w-2xl mx-auto bg-white rounded-2xl shadow-lg p-8 mt-6">
            <h3 class="text-xl font-bold mb-6 text-gray-800">Ubah Password</h3>
            
            <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-sm text-gray-500 mb-1">Password Lama</label>
                    <input type="password" 
                           id="current_password" 
                           name="current_password" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-800 @error('current_password', 'updatePassword') border-red-500 @enderror"
                           required>
                    @error('current_password', 'updatePassword')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm text-gray-500 mb-1">Password Baru</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-800 @error('password', 'updatePassword') border-red-500 @enderror"
                           required>
                    @error('password', 'updatePassword')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm text-gray-500 mb-1">Konfirmasi Password Baru</label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-800"
                           required>
                </div>

                <div class="pt-2">
                    <button type="submit" 
                            class="w-full px-6 py-2.5 bg-gray-800 text-white rounded-lg font-medium hover:bg-gray-700">
                        Ubah Password
                    </button>
                </div>
            </form>
        </section>
        @endauth
    </main>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('avatar-preview');
                    preview.outerHTML = `<img src="${e.target.result}" id="avatar-preview" class="w-24 h-24 rounded-full object-cover border-4 border-gray-200 mb-4">`;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>