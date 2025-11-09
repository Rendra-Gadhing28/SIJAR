<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\items;

class items_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $barang_PPLG = [
            'Proyektor',
            'Kunci lab',
            'Kabel VGA',
            'Converter HDMI',
            'Keyboard',
            'Mouse',
            'VR Oculus',
            'Set Obeng',
            'Lan Tester'
        ];

        $barang_DKV = [
            'Kamera',
            'Tripod',
            'Kamera Panggung',
            'Perangkat ALat Streaming 1 Set',
            'Perangkat Pensil Gambar 1 Set',
            'Pensil Warna 1 Set',
            'Cat Warna Full set',
            'Alat Recording Audio 1 set',
            'LCD',
            'Alat Pres 1 paket',
            'Press Sablon Kaos 1 paket',
            'Pen Tablet',
            'Pen Display'
        ];
//         1. Pispot 8
// 2. Bengkok 10
// 3. Bak Instumen 10
// 4. Pinset Cirugis 10
// 5. Pinset Anatomis 10
// 6. Gunting plester 7
// 7. Gunting klem 7
// 8. Gunting jaringan 7
// 9. Pantom kelamin pria 3
// 10. Pantom kelamin wanita 3
// 11. Pantom RJP 1
// 12. Gunting korentang 2
// 13. Selimut 10
// 14. Seprei 10
// 15. Laken 8
// 16. Stik laken 8
// 17. Perlak 8
// 18. Bed 7
// 19. Termometer 8
// 20. Nebulizer 2
// 21. Timbangan badan 2
// 22. Pulse Oximeter 1
// 23. Kursi Roda 1
// 24. WWZ 5
// 25. Ice bag 5
// 26. Kasur Antidekubitus 1
// 27. Tabung oksigen 2
// 28. Tounge spatel 4
// 29. Refleks Hammer 2

        $barang_LK = [
            'Pispot',
            'Bengkok',
            'Bak Instumen',
            'Pinset Cirugis',
            'Pinset Anatomis',
            'Gunting plester',
            'Gunting klem',
            'Gunting jaringan',
            'Pantom kelamin pria',
            'Pantom kelamin wanita',
            'Pantom RJP',
            'Gunting korentang',
            'Selimut',
            'Seprei',
            'Laken',
            'Stik laken',
            'Perlak',
            'Bed',
            'Termometer',
            'Nebulizer',
            'Timbangan badan',
            'Pulse Oximeter',
            'Kursi Roda',
            'WWZ',
            'Ice bag',
            'Kasur Antidekubitus',
            'Tabung oksigen',
            'Tounge spatel',
            'Refleks Hammer'
        ];

        items::create([
            'nama_item' => '',
        ]);
    }
}
