

<nav class="fixed top-0  w-screen z-50 bg-gradient-to-r from-sky-300 to-sky-600 rounded-2xl py-6 shadow-md px-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/logo.png') }}" alt="Logo SIJAR" class="w-14 h-14">
        </div>

        <h1 class="absolute left-1/2 transform -translate-x-1/2 text-3xl font-extrabold text-transparent bg-clip-text"
            style="background-image: linear-gradient(90deg, #444DCD 0%, #2D3492 61%, #171C59 100%)">
            SIJAR
        </h1>
        {{-- tampilan dekstop --}}
        <div class="hidden lg:flex items-center gap-1">
            <a href="{{ route('user.homepage') }}"
                class="nav-link px-4 py-2 rounded-lg text-white font-semibold transition-all duration-300 hover:bg-sky-700/50 {{ Request::is('homepage*') ? 'bg-sky-800 border-b-4 border-sky-900' : '' }}">
                Beranda
            </a>
            <a href="{{ route('barang.index') }}"
                class="nav-link px-4 py-2 rounded-lg text-white font-semibold transition-all duration-300 hover:bg-sky-700/50 {{ Request::is('barang*') ? 'bg-sky-800 border-b-4 border-sky-900' : '' }}">
                Barang
            </a>
            <a href="{{ route("peminjaman.index") }}"
                class="nav-link px-4 py-2 rounded-lg text-white font-semibold transition-all duration-300 hover:bg-sky-700/50 {{ Request::is('peminjaman*') ? 'bg-sky-800 border-b-4 border-sky-900' : '' }}">
                Pinjam
            </a>
            <a href="{{ route('riwayat.index') }}"
                class="nav-link px-4 py-2 rounded-lg text-white font-semibold transition-all duration-300 hover:bg-sky-700/50 {{ Request::is('riwayat*') ? 'bg-sky-800 border-b-4 border-sky-900' : '' }}">
                Riwayat
            </a>
            <a href="{{ route('riwayat.index') }}"
                class="nav-link px-4 py-2 rounded-lg text-white font-semibold transition-all duration-300 hover:bg-sky-700/50 {{ Request::is('riwayat*') ? 'bg-sky-800 border-b-4 border-sky-900' : '' }}">
                Profil
            </a>
        </div>

        {{-- tombol navigasi ipad --}}
        <button id="hamburgerBtn" class="hidden lg:hidden md:block text-white focus:outline-none z-50">
            <svg id="hamburgerIcon" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                </path>
            </svg>
            <svg id="closeIcon" class="w-8 h-8 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    {{--  hover navbar button--}}
    <div id="mobileMenu" class="lg:hidden hidden mt-4 space-y-2 pb-2">
        <a href="{{ route('user.homepage') }}"
            class="block px-4 py-3 rounded-lg text-white font-semibold transition-all duration-300 hover:bg-sky-700/50 {{ Request::is('homepage*') ? 'bg-sky-800 border-l-4 border-sky-900' : '' }}">
            Beranda
        </a>
          <a href="{{ route('barang.index') }}"
            class="block px-4 py-3 rounded-lg text-white font-semibold transition-all duration-300 hover:bg-sky-700/50 {{ Request::is('barang*') ? 'bg-sky-800 border-l-4 border-sky-900' : '' }}">
            Barang
        </a>
        <a href="{{ route("peminjaman.index") }}"
            class="block px-4 py-3 rounded-lg text-white font-semibold transition-all duration-300 hover:bg-sky-700/50 {{ Request::is('peminjaman*') ? 'bg-sky-800 border-l-4 border-sky-900' : '' }}">
            Pinjam
        </a>
      
        <a href="{{ route('riwayat.index') }}"
            class="block px-4 py-3 rounded-lg text-white font-semibold transition-all duration-300 hover:bg-sky-700/50 {{ Request::is('riwayat*') ? 'bg-sky-800 border-l-4 border-sky-900' : '' }}">
            Riwayat
        </a>
        <a href="{{ route('riwayat.index') }}"
            class="block px-4 py-3 rounded-lg text-white font-semibold transition-all duration-300 hover:bg-sky-700/50 {{ Request::is('riwayat*') ? 'bg-sky-800 border-l-4 border-sky-900' : '' }}">
            Profil
        </a>
    </div>
</nav>

<script>
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const hamburgerIcon = document.getElementById('hamburgerIcon');
    const closeIcon = document.getElementById('closeIcon');

    hamburgerBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
        hamburgerIcon.classList.toggle('hidden');
        closeIcon.classList.toggle('hidden');
    });
</script>

<style>
    #mobileMenu {
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .nav-link.active {
        position: relative;
    }
</style>