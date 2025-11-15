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
                
                <!-- Home -->
                <a href="#" class="nav-item active relative flex flex-col items-center group" data-nav="home">
                    <div class="icon-container bg-gray-100 rounded-full p-3">
                        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        class="icon w-6 h-6 text-gray-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                    </div>
                    <span class="label text-xs mt-1 text-gray-600">Beranda</span>
                </a>

                <!-- Items -->
                <a href="#" class="nav-item relative flex flex-col items-center group" data-nav="items">
                    <div class="icon-container bg-gray-100 rounded-full p-3">
                        <svg class="icon w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <span class="label text-xs mt-1 text-gray-600">Barang</span>
                </a>

                <!-- Add -->
                <a href="#" class="nav-item relative flex flex-col items-center group" data-nav="add">
                    <div class="icon-container bg-gray-100 rounded-full p-3">
                        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                            class="icon w-6 h-6 text-gray-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                    <span class="label text-xs mt-1 text-gray-600">Pinjam</span>
                </a>
                <!-- History -->
                <a href="#" class="nav-item relative flex flex-col items-center group" data-nav="history">
                    <div class="icon-container bg-gray-100 rounded-full p-3">
                        <svg class="icon w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="label text-xs mt-1 text-gray-600">Riwayat</span>
                </a>

                <!-- Profile -->
                <a href="#" class="nav-item relative flex flex-col items-center group" data-nav="profile">
                    <div class="icon-container bg-gray-100 rounded-full p-3">
                        <svg class="icon w-6 h-6 text-gray-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <span class="label text-xs mt-1 text-gray-600">Profil</span>
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
                e.preventDefault();
                
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