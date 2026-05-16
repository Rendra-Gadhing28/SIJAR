<?php

namespace App\Http\Controllers\WEB;

use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use App\Models\Item;



class UserController extends Controller
{
    public function gantiPassw(Request $request){
        $request->validate([
           'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)], 
        ]);

         $user = Auth::user();

        // Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('profile.index')->with('success', 'Password berhasil diubah!');
    }

    public function login(Request $request){
        
    }

 public function getKategori()
{
    // Ambil semua user dengan relasi kategori (jurusan)
    $users = User::with('kategori')->get();

    // Ambil semua item dengan relasi kategori jurusan
    $items = Item::with('kategoriJurusan')->get();

    // Kelompokkan item per kategori/jurusan
    $itemsByKategori = $items->groupBy('kategori_jurusan_id');

    // Kode mapping (sesuaikan dengan data di DB)
    $kodeMap = [
        'PPLG' => ['nama' => 'Pengembangan Perangkat Lunak dan Gim', 'icon_emoji' => '💻'],
        'TJKT' => ['nama' => 'Teknik Jaringan Komputer dan Telekomunikasi', 'icon_emoji' => '🌐'],
        'DKV'  => ['nama' => 'Desain Komunikasi Visual', 'icon_emoji' => '🎨'],
        'LK'   => ['nama' => 'Layanan Kesehatan', 'icon_emoji' => '🏥'],
        'PS'   => ['nama' => 'Perhotelan & Pariwisata', 'icon_emoji' => '🏨'],
    ];

    // Data highlight & konsentrasi per jurusan (statis di BE, bisa dipindah ke FE)
    $extraData = [
        'PPLG' => [
            'highlight'    => ['Laravel', 'React', 'Flutter', 'Python'],
            'konsentrasi'  => ['Rekayasa Perangkat Lunak', 'Pengembangan Gim'],
        ],
        'TJKT' => [
            'highlight'    => ['Cisco', 'MikroTik', 'Fiber Optik'],
            'konsentrasi'  => ['Teknik Komputer & Jaringan', 'Sistem Telekomunikasi'],
        ],
        'DKV'  => [
            'highlight'    => ['Adobe CC', 'Figma', 'Ilustrasi'],
            'konsentrasi'  => ['Desain Grafis', 'Animasi & Multimedia'],
        ],
        'LK'   => [
            'highlight'    => ['Keperawatan', 'Farmasi', 'Kesehatan Masyarakat'],
            'konsentrasi'  => ['Asisten Keperawatan', 'Caregiver'],
        ],
        'PS'   => [
            'highlight'    => ['Front Office', 'Housekeeping', 'F&B Service'],
            'konsentrasi'  => ['Akomodasi Perhotelan', 'Wisata Bahari'],
        ],
    ];

    // Ambil petugas dari relasi user->kategori (penanggung jawab jurusan)
    $petugasByKategori = $users->groupBy('kategori_id')->map(function ($group) {
        return $group->first()->name ?? 'Belum ditentukan';
    });

    // Ambil semua kategori unik dari item
    $kategoriList = $items->pluck('kategoriJurusan')->unique('id')->filter();

    // Hitung summary global
    $totalBarang    = $items->count();
    $totalTersedia  = $items->where('status_item', 'tersedia')->count();
    // Build data jurusan
    $data = $kategoriList->map(function ($kategori) use (
        $itemsByKategori, $kodeMap, $extraData, $petugasByKategori
    ) {
         $icon = $kategori->icon
                ? asset('storage/icons/' . $kategori->icon)
                : null;
        $kode        = $kategori->kode ?? strtoupper($kategori->nama_kategori);
        $itemsJurusan = $itemsByKategori->get($kategori->id, collect());

        $totalBarang   = $itemsJurusan->count();
        $tersedia      = $itemsJurusan->where('status_item', 'tersedia')->count();
        $persentase    = $totalBarang > 0 ? round(($tersedia / $totalBarang) * 100) : 0;

        // Kelas: ambil dari relasi item jika ada, atau generate otomatis
        $defaultKelas = [
    'PPLG' => ['X PPLG 1', 'X PPLG 2', 'X PPLG 3','XI PPLG 1', 'XI PPLG 2', 'XI PPLG 3','XII PPLG 1', 'XII PPLG 2', 'XII PPLG 3',],
    'DKV'  => ['X DKV 1',  'X DKV 2',  'X DKV 3','XI DKV 1',  'XI DKV 2',  'XI DKV 3', 'XII DKV 1',  'XII DKV 2',  'XII DKV 3'],
    'TJKT' => [],
    'LK'   => [],
    'PS'   => [],
];

// Kelas: ambil dari relasi item jika ada, atau pakai default
$kelasList = $itemsJurusan->pluck('kelas')->unique()->filter()->values()->toArray();
if (empty($kelasList)) {
    $kelasList = $defaultKelas[$kode] ?? [];
}
  if (empty($kelasList)) {
            // Generate default kelas jika tidak ada di DB
            $kelasList = [
               " X {$kode} 1",  " X {$kode} 2",
                " XI {$kode} 1",  " XI {$kode} 2",
                " XII {$kode} 1",  " XII {$kode} 2", 
            ];
        }

        return [
            'id'          => $kategori->id,
            'kode'        => $kode,
            'nama'        => $kodeMap[$kode]['nama'] ?? $kategori->nama,
            'icon'        => $icon,         // URL gambar jika ada
            'deskripsi'   => $kategori->deskripsi ?? "Program keahlian {$kode} di SMKN 8 Semarang.",
            'highlight'   => $extraData[$kode]['highlight']   ?? [],
            'konsentrasi' => $extraData[$kode]['konsentrasi'] ?? [],
            'kelas'       => $kelasList,
            'total_kelas' => count($kelasList),
            'petugas'     => $petugasByKategori->get($kategori->id) ?? 'Belum ditentukan',
            'statistik'   => [
                'total_barang'         => $totalBarang,
                'tersedia'             => $tersedia,
                'persentase_tersedia'  => $persentase,
            ],
        ];
    })->values();

    // Summary card FE
    $summary = [
        'total_jurusan'    => $data->count(),
        'total_barang'     => $totalBarang,
        'total_tersedia'   => $totalTersedia,
        'total_kelas'      => $data->sum('total_kelas'),
        'total_konsentrasi'=> $data->sum(fn($j) => count($j['konsentrasi'])),
    ];

    return response()->json([
        'status'  => true,
        'message' => 'Data kategori jurusan',
        'data'    => $data,
        'summary' => $summary,
    ]);
}
}
