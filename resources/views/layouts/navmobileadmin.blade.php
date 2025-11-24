<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .nav-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .nav-item.active .icon-container {
            transform: translateY(-8px);
            background: linear-gradient(135deg, #7dd3fc, #2563eb);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }
        
        .nav-item.active .icon {
            color: white;
            transform: scale(1.1);
        }
        
        .nav-item .label {
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .nav-item.active .label {
            opacity: 1;
            transform: translateY(0);
            color: #1e40af;
            font-weight: 600;
        }
        
        .icon {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .icon-container {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .nav-item:not(.active):hover .icon-container {
            background: #dbeafe;
            transform: translateY(-2px);
        }
        
        .nav-item:not(.active):hover .icon {
            color: #7dd3fc;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen pb-32">

    <!-- Bottom Navigation -->
    <nav class="fixed bottom-0 left-0 right-0 w-screen h-28 p-4 shadow-lg z-50 md:hidden bg-gradient-to-r from-sky-300 to-sky-600 rounded-t-3xl py-6 px-6">
        <div class="relative">
            <div class="flex justify-around items-center">
                
        @if(auth()->user()->role == 'admin')
                   {{-- Hover Navbar Button --}}
<div id="mobileMenu" class="lg:hidden hidden mt-4 space-y-2 pb-2">

        {{-- Menu Mobile Admin --}}
        <a href="{{ route('admin.dashboard') }}"
            class="block px-4 py-3 rounded-lg text-white font-semibold transition-all duration-300 hover:bg-sky-700/50 {{ Request::is('admin/dashboard*') ? 'bg-sky-800 border-l-4 border-sky-900' : '' }}">
            Dashboard
        </a>
        <a href="{{ route('barang.index') }}"
            class="block px-4 py-3 rounded-lg text-white font-semibold transition-all duration-300 hover:bg-sky-700/50 {{ Request::is('barang*') ? 'bg-sky-800 border-l-4 border-sky-900' : '' }}">
            Barang
        </a>
        <a href="{{ route('peminjaman.create') }}"  {{-- Tambah Pinjam --}}
            class="block px-4 py-3 rounded-lg text-white font-semibold transition-all duration-300 hover:bg-sky-700/50 {{ Request::is('peminjaman/create*') ? 'bg-sky-800 border-l-4 border-sky-900' : '' }}">
            Pinjam
        </a>
        <a href="{{ route('admin.barang.create') }}"
            class="block px-4 py-3 rounded-lg text-white font-semibold transition-all duration-300 hover:bg-sky-700/50 {{ Request::is('admin/barang/create*') ? 'bg-sky-800 border-l-4 border-sky-900' : '' }}">
            Tambah Barang
        </a>
        <a href="{{ route('admin.riwayat') }}"
            class="block px-4 py-3 rounded-lg text-white font-semibold transition-all duration-300 hover:bg-sky-700/50 {{ Request::is('admin/riwayat*') ? 'bg-sky-800 border-l-4 border-sky-900' : '' }}">
            Riwayat Siswa
        </a>
        <a href="{{ route('admin.peminjaman.index') }}"
            class="block px-4 py-3 rounded-lg text-white font-semibold transition-all duration-300 hover:bg-sky-700/50 {{ Request::is('admin/peminjaman*') ? 'bg-sky-800 border-l-4 border-sky-900' : '' }}">
            Kelola Peminjaman
        </a>
    @elseif(auth()->user()->role == 'user')
        {{-- Menu Mobile User --}}
        <a href="{{ route('user.homepage') }}"
            class="block px-4 py-3 rounded-lg text-white font-semibold transition-all duration-300 hover:bg-sky-700/50 {{ Request::is('homepage*') ? 'bg-sky-800 border-l-4 border-sky-900' : '' }}">
            Beranda
        </a>
        <a href="{{ route('barang.index') }}"
            class="block px-4 py-3 rounded-lg text-white font-semibold transition-all duration-300 hover:bg-sky-700/50 {{ Request::is('barang*') ? 'bg-sky-800 border-l-4 border-sky-900' : '' }}">
            Barang
        </a>
        <a href="{{ route('peminjaman.create') }}"
            class="block px-4 py-3 rounded-lg text-white font-semibold transition-all duration-300 hover:bg-sky-700/50 {{ request()->routeIs('peminjaman.create') ? 'bg-sky-800 border-l-4 border-sky-900' : '' }}">
            Pinjam
        </a>
        <a href="{{ route('peminjaman.index') }}"
            class="block px-4 py-3 rounded-lg text-white font-semibold transition-all duration-300 hover:bg-sky-700/50 {{ request()->routeIs('peminjaman.index') || request()->routeIs('peminjaman.show') || request()->routeIs('peminjaman.edit') ? 'bg-sky-800 border-l-4 border-sky-900' : '' }}">
            Riwayat
        </a>
    @else <p>error</p>
    @endif
    {{-- Profil (sama untuk semua) --}}
    <a href="{{ route('profile.index') }}"
        class="block px-4 py-3 rounded-lg text-white font-semibold transition-all duration-300 hover:bg-sky-700/50 {{ Request::is('profile*') ? 'bg-sky-800 border-l-4 border-sky-900' : '' }}">
        Profil
    </a>
</div>
        </div>
    </nav>

    <script>
        // Get all nav items
        const navItems = document.querySelectorAll('.nav-item');
        
        // Add click event to each nav item
        navItems.forEach(item => {
            item.addEventListener('click', function(e) {
                // Allow navigation, but update active state
                // Remove active class from all items
                navItems.forEach(navItem => {
                    navItem.classList.remove('active');
                });
                
                // Add active class to clicked item
                this.classList.add('active');
            });
        });
    </script>

</body>

</html>
