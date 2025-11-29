<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="{{ asset('/images/logo_sijar.png') }}">
    <title>Profile</title>
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
            <h2 class="text-2xl font-bold text-center mb-8 text-gray-800">Profile</h2>
            
            @auth
            {{-- Alert Success --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="space-y-6">
                {{-- Avatar Section --}}
                <div class="flex justify-center mb-6">
                    @if(Auth::user()->profile)
                        <img src="{{ asset('storage/avatars/' . Auth::user()->profile) }}" 
                             alt="profile" 
                             class="w-24 h-24 rounded-full object-cover border-4 border-gray-200">
                    @else
                        <div class="w-24 h-24 rounded-full bg-gray-300 flex items-center justify-center border-4 border-gray-200">
                            <span class="text-4xl font-bold text-gray-600">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                    @endif
                </div>

                {{-- User Info --}}
                <div class="space-y-4 text-left">
                    <div class="border-b border-gray-200 pb-3">
                        <p class="text-sm text-gray-500 mb-1">Nama</p>
                        <p class="text-lg font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                    </div>

                    <div class="border-b border-gray-200 pb-3">
                        <p class="text-sm text-gray-500 mb-1">Email</p>
                        <p class="text-lg text-gray-700">{{ strtolower(Auth::user()->email) }}</p>
                    </div>

                    
                    <div class="border-b border-gray-200 pb-3">
                        <p class="text-sm text-gray-500 mb-1">Telepon</p>
                        <p class="text-lg text-gray-700">{{ Auth::user()->telepon ?? '-'}}</p>
                    </div>
                  

                    @if(Auth::user()->kategori)
                    <div class="border-b border-gray-200 pb-3">
                        <p class="text-sm text-gray-500 mb-1">Jurusan</p>
                        <p class="text-lg text-gray-700">{{ Auth::user()->kategori->nama_kategori }}</p>
                    </div>
                    @endif
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-4 justify-center pt-6">
                    <a href="{{ route('profile.edit') }}" 
                       class="px-6 py-2.5 bg-gray-800 text-white rounded-lg font-medium hover:bg-gray-700">
                        Edit Profile
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="px-6 py-2.5 bg-white border-2 border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
            @endauth
        </section>
    </main>
</body>
</html>