<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Peminjaman Barang</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('/images/logo_sijar.png') }}">
    @vite('resources/css/app.css')

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #d1d5db !important;
            border-radius: .5rem !important;
            padding: .5rem !important;
            min-height: 42px !important;
        }

        .item-card {
            transition: .3s ease;
            cursor: pointer;
        }
        .item-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .item-card.selected {
            border: 3px solid #3b82f6;
            background: #eff6ff;
        }
    </style>
</head>

<body class="bg-gradient-to-b from-gray-200 to-white min-h-screen font-['Poppins']">

<header>
    @include('layouts.navigation')
</header>

<main class="pt-28 px-6 md:px-12 pb-12">
    <section class="max-w-6xl mx-auto">

        <h2 class="text-2xl font-bold mb-6 text-center">Form Peminjaman Barang</h2>

        {{-- ALERT --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid md:grid-cols-3 gap-6">

            {{-- LEFT SIDE FORM --}}
            <div class="bg-white p-6 rounded-2xl shadow md:col-span-1">
                <form action="{{ route('peminjaman.store') }}" method="POST" enctype="multipart/form-data" id="formPeminjaman">
                    @csrf

                    {{-- ITEM DISPLAY --}}
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-2">Barang yang Dipilih</label>
                        <input type="text" id="selected_item_display" readonly class="w-full px-4 py-2 bg-gray-100 border rounded-lg" placeholder="Pilih barang dari daftar →">
                        <input type="hidden" name="item_id" id="item_id" required>
                        <input type="hidden" name="kode_unit" id="kode_unit">
                        <p class="text-xs text-gray-500 mt-1">Klik card barang di sebelah kanan</p>
                    </div>

                    {{-- KEPERLUAN --}}
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-2">Keperluan</label>
                        <textarea name="keperluan" rows="3" class="w-full px-4 py-2 border rounded-lg" required>{{ old('keperluan') }}</textarea>
                    </div>

                    {{-- MULTIPLE JAM --}}
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-2">Jam Pembelajaran (Multiple)</label>
                        <select name="waktu_ids[]" id="waktu_ids" multiple class="w-full">
                            @foreach ($waktu as $wkt)
                                <option value="{{ json_encode(['jam_ke' => $wkt->jam_ke, 'start_time' => $wkt->start_time, 'end_time' => $wkt->end_time]) }}">
                                    Jam {{ $wkt->jam_ke }}. {{ $wkt->start_time }} - {{ $wkt->end_time }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- BUKTI --}}
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-2">Bukti Peminjaman</label>
                        <input type="file" name="bukti" accept="image/*" class="w-full border rounded-lg px-4 py-2" required>
                    </div>

                    <button class="w-full bg-blue-600 text-white py-3 font-semibold rounded-lg hover:bg-blue-700 transition">
                        Kirim Peminjaman
                    </button>

                    <a href="{{ route('peminjaman.index') }}" class="w-full mt-3 block text-center bg-gray-300 py-3 rounded-lg hover:bg-gray-400 text-gray-700 font-semibold">
                        Lihat Riwayat
                    </a>
                </form>
            </div>

            {{-- RIGHT SIDE ITEMS --}}
            <div class="bg-white p-6 rounded-2xl shadow md:col-span-2">

                <h3 class="text-lg font-bold mb-4">Pilih Barang</h3>

                {{-- SEARCH + FILTER --}}
                <form method="GET" class="flex flex-col md:flex-row gap-4 mb-5">

                    {{-- Search --}}
                    <input name="search" type="text" value="{{ request('search') }}" placeholder="Cari barang..."
                        class="px-4 py-2 border rounded-lg w-full md:w-1/2">

                    {{-- Filter Jenis --}}
                    <select name="jenis" class="px-4 py-2 border rounded-lg w-full md:w-1/3">
                        @foreach($jenis_items as $jenis)
                            <option value="{{ $jenis }}" {{ request('jenis') == $jenis ? 'selected' : '' }}>
                                {{ ucfirst($jenis) }}
                          
                        @endforeach
                    </select>
                    <select name="jenis" class="px-4 py-2 border rounded-lg w-full md:w-1/3">
                        @foreach($status_item as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : 'tidak ada yang rusak/dipinjam' }}>
                                {{ ucfirst($status) }}
                          
                        @endforeach
                    </select>

                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Filter
                    </button>
                </form>

                {{-- ITEM LIST --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-h-[600px] overflow-y-auto pr-2">

                    @forelse ($items as $item)
                        <div class="item-card border p-4 rounded-xl"
                             data-id="{{ $item->id }}"
                             data-nama="{{ $item->nama_item }}"
                             data-kode="{{ $item->kode_unit }}">

                            {{-- Status --}}
                            <div class="flex justify-end mb-1">
                                <span class="px-2 py-1 text-xs rounded
                                    @if ($item->status_item == 'tersedia') bg-green-100 text-green-600
                                    @elseif ($item->status_item == 'dipinjam') bg-yellow-100 text-yellow-600
                                    @else bg-gray-200 text-gray-600
                                    @endif">
                                    {{ $item->status_item }}
                                </span>
                            </div>

                            <div class="w-full h-40 rounded-lg overflow-hidden bg-gray-100 mb-3">
                                <img src="{{ asset('storage/encrypted/' . $item->foto_barang) }}"
                                     class="w-full h-full object-cover">
                            </div>

                            <h4 class="font-semibold text-sm truncate">{{ $item->nama_item }}</h4>
                            <p class="text-xs text-gray-500">Kode: {{ $item->kode_unit }}</p>
                            <p class="text-xs text-gray-500 mb-2">Jenis: {{ $item->jenis_item }}</p>

                            <p class="text-center text-blue-600 text-xs font-semibold">Klik untuk Pilih</p>
                        </div>
                    @empty
                        <p class="col-span-3 text-center text-gray-500 py-5">
                            Tidak ada barang ditemukan
                        </p>
                    @endforelse
                </div>

                {{-- PAGINATION --}}
                <div class="mt-6">
                    {{ $items->links() }}
                </div>

            </div>
        </div>

    </section>
</main>


<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function () {

    // Select2 Responsive
    $('#waktu_ids').select2({
        placeholder: "Pilih jam pembelajaran (bisa lebih dari 1)",
        width: "100%",
        allowClear: true
    });

    // Pilih Card Item
    $('.item-card').click(function () {
        $('.item-card').removeClass('selected');
        $(this).addClass('selected');

        $('#item_id').val($(this).data('id'));
        $('#kode_unit').val($(this).data('kode'));
        $('#selected_item_display').val($(this).data('nama') + ' (ID: ' + $(this).data('id') + ')');
    });

    // Submit Validation
    $('#formPeminjaman').submit(function (e) {
        if (!$('#item_id').val()) {
            e.preventDefault();
            alert("Silakan pilih barang terlebih dahulu!");
        }
    });
});
</script>

</body>
</html>
