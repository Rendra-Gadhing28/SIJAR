<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="{{ asset('/images/logo_sijar.png') }}">
    <title>Notifikasi Trash - Admin</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gradient-to-b from-gray-100 to-white min-h-screen font-['Poppins']">
    <header>
        @include('layouts.navigationadmin')
    </header>
   
    <main class="pt-24 px-4 md:px-8 lg:px-12 pb-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800 mb-1">Trash Bin</h1>
                        <p class="text-gray-600">Notifikasi yang telah dihapus</p>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.notifications.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke Notifikasi
                        </a>
                    </div>
                </div>
                
                <!-- Stats Card -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-white rounded-xl shadow-sm p-5">
                        <div class="flex items-center">
                            <div class="p-3 bg-red-100 rounded-lg mr-4">
                                <i class="fas fa-trash text-red-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Total di Trash</p>
                                <p class="text-2xl font-bold text-gray-800">
                                    {{ $notifications->total() }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-sm p-5">
                        <div class="flex items-center">
                            <div class="p-3 bg-yellow-100 rounded-lg mr-4">
                                <i class="fas fa-clock text-yellow-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Tertua di Trash</p>
                                <p class="text-lg font-semibold text-gray-800">
                                    @if($notifications->count() > 0)
                                        {{ $notifications->first()->deleted_at->diffForHumans() }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-sm p-5">
                        <div class="flex items-center">
                            <div class="p-3 bg-purple-100 rounded-lg mr-4">
                                <i class="fas fa-database text-purple-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Aksi</p>
                                <form action="{{ route('admin.notifications.clearTrash') }}" method="POST" class="inline">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" 
                                            onclick="return confirm('HAPUS PERMANEN semua notifikasi di trash? Aksi ini tidak dapat dibatalkan!')"
                                            class="text-red-600 hover:text-red-800 font-medium">
                                        <i class="fas fa-broom mr-1"></i> Kosongkan Trash
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                        <span class="text-green-700">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Warning Alert -->
            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Peringatan</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Notifikasi di trash bin akan otomatis dihapus permanen setelah 30 hari. Anda dapat memulihkan notifikasi atau menghapusnya secara permanen.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Filter Trash</h2>
                <form method="GET" action="{{ route('admin.notifications.trashed') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Notifikasi</label>
                        <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Tipe</option>
                            @foreach($notificationTypes as $type)
                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dihapus Sejak</label>
                        <select name="days" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Waktu</option>
                            <option value="7" {{ request('days') == '7' ? 'selected' : '' }}>1 Minggu Terakhir</option>
                            <option value="14" {{ request('days') == '14' ? 'selected' : '' }}>2 Minggu Terakhir</option>
                            <option value="30" {{ request('days') == '30' ? 'selected' : '' }}>1 Bulan Terakhir</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <div class="flex gap-2 w-full">
                            <button type="submit" 
                                    class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center justify-center">
                                <i class="fas fa-filter mr-2"></i> Terapkan Filter
                            </button>
                            <a href="{{ route('admin.notifications.trashed') }}" 
                               class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition flex items-center justify-center">
                                <i class="fas fa-redo mr-2"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Mass Actions -->
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" id="selectAllTrash" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                        <label for="selectAllTrash" class="text-sm font-medium text-gray-700">Pilih Semua</label>
                        <span id="selectedCountTrash" class="text-sm text-gray-600 ml-4">0 dipilih</span>
                    </div>
                    
                    <div id="massActionsTrash" class="flex gap-2 hidden">
                        <button onclick="massActionTrash('restore')" 
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center">
                            <i class="fas fa-undo mr-2"></i> Pulihkan yang Dipilih
                        </button>
                        <button onclick="massActionTrash('force_delete')" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center">
                            <i class="fas fa-fire mr-2"></i> Hapus Permanen
                        </button>
                    </div>
                </div>
            </div>

            <!-- Trashed Notifications List -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                @if($notifications->count() > 0)
                    <div class="divide-y divide-gray-100">
                        @foreach($notifications as $notification)
                            @php
                                $data = $notification->data;
                                $type = class_basename($notification->type);
                                $daysDeleted = $notification->deleted_at ? now()->diffInDays($notification->deleted_at) : 0;
                            @endphp
                            
                            <div class="p-6 hover:bg-gray-50 transition notification-trash-item opacity-80">
                                <div class="flex items-start justify-between">
                                    <!-- Checkbox -->
                                    <div class="mr-4 mt-1">
                                        <input type="checkbox" 
                                               class="trash-checkbox rounded border-gray-300 text-red-600 focus:ring-red-500" 
                                               value="{{ $notification->id }}">
                                    </div>
                                    
                                    <!-- Notification Content -->
                                    <div class="flex-1">
                                        <div class="flex items-start gap-4">
                                            <!-- Icon -->
                                            <div class="flex-shrink-0">
                                                @if(str_contains($type, 'Approved'))
                                                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                                    </div>
                                                @elseif(str_contains($type, 'Rejected'))
                                                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-times-circle text-red-600 text-xl"></i>
                                                    </div>
                                                @else
                                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-bell text-blue-600 text-xl"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- Content -->
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                        @if(str_contains($type, 'Approved')) bg-green-100 text-green-800
                                                        @elseif(str_contains($type, 'Rejected')) bg-red-100 text-red-800
                                                        @else bg-blue-100 text-blue-800 @endif">
                                                        {{ $type }}
                                                    </span>
                                                    
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                                        @if($daysDeleted > 20) bg-red-100 text-red-800
                                                        @elseif($daysDeleted > 10) bg-yellow-100 text-yellow-800
                                                        @else bg-gray-100 text-gray-800 @endif">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        Dihapus {{ $notification->deleted_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                                
                                                <p class="text-gray-800 font-medium mb-2 line-through">
                                                    {{ $data['message'] ?? 'Notifikasi' }}
                                                </p>
                                                
                                                <!-- Additional Data -->
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-3">
                                                    @if(isset($data['user_name']))
                                                    <div class="flex items-center text-sm text-gray-600">
                                                        <i class="fas fa-user mr-2"></i>
                                                        <span>{{ $data['user_name'] }}</span>
                                                    </div>
                                                    @endif
                                                    
                                                    @if(isset($data['item_name']))
                                                    <div class="flex items-center text-sm text-gray-600">
                                                        <i class="fas fa-box mr-2"></i>
                                                        <span>{{ $data['item_name'] }} 
                                                            @if(isset($data['kode_unit']))
                                                                ({{ $data['kode_unit'] }})
                                                            @endif
                                                        </span>
                                                    </div>
                                                    @endif
                                                    
                                                    @if(isset($data['tanggal']))
                                                    <div class="flex items-center text-sm text-gray-600">
                                                        <i class="fas fa-calendar mr-2"></i>
                                                        <span>{{ $data['tanggal'] }}</span>
                                                    </div>
                                                    @endif
                                                    
                                                    @if(isset($data['peminjaman_id']))
                                                    <div class="flex items-center text-sm text-gray-600">
                                                        <i class="fas fa-hashtag mr-2"></i>
                                                        <span>ID: {{ $data['peminjaman_id'] }}</span>
                                                    </div>
                                                    @endif
                                                </div>
                                                
                                                <!-- Time Info -->
                                                <div class="flex items-center text-sm text-gray-500">
                                                    <i class="far fa-clock mr-2"></i>
                                                    <span>Dibuat {{ $notification->created_at->diffForHumans() }}</span>
                                                    <span class="mx-2">•</span>
                                                    <i class="fas fa-trash mr-1"></i>
                                                    <span>Dihapus {{ $notification->deleted_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Action Buttons -->
                                    <div class="flex flex-col items-end gap-2 ml-4">
                                        <!-- Restore Button -->
                                        <form action="{{ route('admin.notifications.restore', $notification->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" 
                                                    onclick="return confirm('Pulihkan notifikasi ini?')"
                                                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm">
                                                <i class="fas fa-undo mr-1"></i> Pulihkan
                                            </button>
                                        </form>
                                        
                                        <!-- Permanent Delete Button -->
                                        <form action="{{ route('admin.notifications.forceDelete', $notification->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('HAPUS PERMANEN notifikasi ini? Aksi ini tidak dapat dibatalkan!')"
                                                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm">
                                                <i class="fas fa-fire mr-1"></i> Hapus Permanen
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    @if($notifications->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="text-center py-16">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-6">
                            <i class="fas fa-trash-alt text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-medium text-gray-600 mb-3">Trash Bin Kosong</h3>
                        <p class="text-gray-500 mb-8 max-w-md mx-auto">
                            Tidak ada notifikasi di trash bin. Notifikasi yang dihapus akan muncul di sini dan otomatis dihapus setelah 30 hari.
                        </p>
                        <a href="{{ route('admin.notifications.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke Notifikasi
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </main>

    <script>
    // Mass selection functionality untuk trash
    const selectAllTrash = document.getElementById('selectAllTrash');
    const trashCheckboxes = document.querySelectorAll('.trash-checkbox');
    const massActionsTrash = document.getElementById('massActionsTrash');
    const selectedCountTrash = document.getElementById('selectedCountTrash');
    
    selectAllTrash.addEventListener('change', function() {
        const isChecked = this.checked;
        trashCheckboxes.forEach(checkbox => {
            checkbox.checked = isChecked;
        });
        updateMassActionsTrash();
    });
    
    trashCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateMassActionsTrash);
    });
    
    function updateMassActionsTrash() {
        const checkedBoxes = document.querySelectorAll('.trash-checkbox:checked');
        const count = checkedBoxes.length;
        
        if (count > 0) {
            massActionsTrash.classList.remove('hidden');
            selectedCountTrash.textContent = `${count} notifikasi dipilih`;
        } else {
            massActionsTrash.classList.add('hidden');
            selectedCountTrash.textContent = '0 dipilih';
        }
    }
    
    function massActionTrash(action) {
        const checkedBoxes = document.querySelectorAll('.trash-checkbox:checked');
        const ids = Array.from(checkedBoxes).map(checkbox => checkbox.value);
        
        if (ids.length === 0) {
            alert('Pilih setidaknya satu notifikasi');
            return;
        }
        
        let confirmMessage = '';
        let actionText = '';
        
        switch(action) {
            case 'restore':
                confirmMessage = `Pulihkan ${ids.length} notifikasi dari trash?`;
                actionText = 'restore';
                break;
            case 'force_delete':
                confirmMessage = `HAPUS PERMANEN ${ids.length} notifikasi dari trash? Aksi ini tidak dapat dibatalkan!`;
                actionText = 'force_delete';
                break;
        }
        
        if (!confirm(confirmMessage)) return;
        
        fetch('{{ route("admin.notifications.massAction") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                action: actionText,
                ids: ids
            })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            window.location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memproses aksi');
        });
    }
    
    // Auto-check berdasarkan filter days
    const daysFilter = document.querySelector('select[name="days"]');
    if (daysFilter) {
        daysFilter.addEventListener('change', function() {
            if (this.value) {
                const days = parseInt(this.value);
                trashCheckboxes.forEach(checkbox => {
                    const notificationItem = checkbox.closest('.notification-trash-item');
                    // Logika untuk auto-select berdasarkan days
                    // Anda bisa menambahkan data attribute untuk waktu delete
                });
            }
        });
    }
    </script>
</body>
</html>