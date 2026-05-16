<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


1. Welcome Sijar (deskpripsi) (bg foto peminjaman/barang dll)
2. Tujuan website
3. Jurusan
4. foto barang 3
5. contact e dewe



   public function index(Request $request)
    {
        $user = Auth::user();

        $Peminjaman = Peminjaman::where("user_id", $user->id)
            ->with(["item:id,nama_item,kode_unit"])
            ->select(['id', 'keperluan', 'user_id', 'item_id', 'tanggal', 'status_tujuan', 'status_pinjaman', 'gambar_bukti', 'jam_pembelajaran'])
            ->latest()
            ->paginate(10);

        // Tetap return 200 meski kosong, biar FE tidak error — kosong bukan error
        return response()->json([
            "status"  => true,
            "message" => $Peminjaman->isEmpty() ? "data masih kosong" : "data Peminjaman berhasil diambil",
            "data"    => $Peminjaman
        ], 200);
    }

    fungsi index peminjaman

    public function beranda()
    {
        $userId = Auth::id();

        // Optimasi: jalankan 3 query secara bersamaan dengan selectRaw untuk count
        $Peminjaman = Peminjaman::where("user_id", $userId)
            ->with(["item:id,nama_item,kode_unit,foto_barang"])
            ->select(['id', 'keperluan', 'item_id', 'tanggal', 'status_tujuan', 'status_pinjaman'])
            ->latest()
            ->limit(6)
            ->get(); // ← FIX: tambah get() yang hilang di kode lama

        // Optimasi: gabung 2 count query menjadi 1 query dengan selectRaw
        $stats = Peminjaman::where('user_id', $userId)
            ->selectRaw("
                SUM(CASE WHEN status_pinjaman = 'dipinjam' THEN 1 ELSE 0 END) as dipinjam,
                SUM(CASE WHEN status_pinjaman = 'selesai' THEN 1 ELSE 0 END) as selesai
            ")
            ->first();

        return response()->json([
            "status"  => true,
            "message" => "data beranda berhasil diambil",
            "data"    => [
                "Peminjaman_terbaru" => $Peminjaman,
                "total_dipinjam"     => (int) $stats->dipinjam,
                "total_selesai"      => (int) $stats->selesai,
            ]
        ], 200);
    }

    fungsi api homepage



    public function index(Request $request)
{
    $user = Auth::user();
    // $user = User::find(8)
    // ->with('kategori')->first();
    if (!$user) {
    return response()->json([
        "status" => false,
        "message" => "Unauthenticated. Silakan login terlebih dahulu.",
    ], 401);
}
    $user->load('kategori');
    $jurusan = $user->kategori_id;
    $jurusanNama = $user->kategori->nama_kategori ?? 'Semua Jurusan';

    // Mulai dengan query builder (TANPA get)
    $item = Item::with('kategori_jurusan');

    // Inisialisasi kategori default
    $kategori = $jurusan;

    // Filter berdasarkan kategori (jika user memilih dropdown)
    if ($request->filled('kategori_jurusan_id')) {

        $item->where('kategori_jurusan_id', $request->kategori_jurusan_id);

        $kategoriObj = Kategori::find($request->kategori_jurusan_id);
        if ($kategoriObj) {
            $kategori = $kategoriObj->nama_kategori;
        }
    }
    if ($request->search) {
        $keyword = $request->search;

        $item->where(function ($q) use ($keyword) {
            $q->where('nama_item', 'LIKE', "%$keyword%")
              ->orWhere('kode_unit', 'LIKE', "%$keyword%");
        });
    }
    
    
   
    // Filter hanya barang yang tersedia
    $item->where('status_item', 'tersedia');
    $barangjurusan = $item->count();

    // Ambil data lengkap dengan relasi kategori
   $data = $item->with('kategori_jurusan')
        ->orderBy('created_at', 'desc')
        ->paginate(9)->appends($request->only(['search', 'kategori_jurusan_id']));;
        // ->appends([
        //     'search' => $request->search,
        //     'kategori_jurusan_id' => $request->kategori_jurusan_id
        // ]);
    $AllDataJurusan = Item::with('kategori_jurusan')->orderBy('created_at', 'desc')->paginate(9);

    // Dropdown kategori
    $kategoris = Kategori::orderBy('nama_kategori')->get();
    $dataLengkap = [$data, $kategori, $kategoris, $barangjurusan, $jurusanNama];
    return response()->json([
        "status" => true,
        "message" => "berhasil mengambil data untuk jurusan ".$jurusanNama,
        "data" => $dataLengkap[0],
        "kategori" => $dataLengkap[1],
        "Totalbarangjurusan" => $dataLengkap[3],
        "jurusanNama" => $dataLengkap[4],
    ], 200) ;
}
fungsi api barang

  public function index() {
    $user = Auth::user();
    $data = User::select('id', 'name', 'kode', 'telepon', 'profile')->find($user->id);
    return response()->json([
        "status" => true,
        "message" => "Data user berhasil diambil",
        "data" => $data
    ], 200);
}

fungsi api mobile profil


public function index(){
        $waktuP = waktu_pembelajaran::all();
        return response()->json([
            "status" => true,
            "message" => "data waktu pembelajaran",
            "data" => $waktuP
        ], 200);
    }
    fungsi api mobile waktu



    public function getKategori(){
        $kategori = User::with('kategori')->get();
        return response()->json([
            "status" => true,
            "message" => "data kategori jurusan",
            "data" => $kategori,
        ],);
    }

    fungsi api mobile jurusan


    public function store(Request $request)
    {
        $validated = $request->validate([
            'keperluan'    => 'required|string|max:255',
            'item_id'      => 'required|exists:item,id',
            'kode_unit'    => 'nullable|string',
            'waktu_ids'    => 'required|array|min:1',
            'waktu_ids.*'  => 'string',
            'bukti'        => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'waktu_ids.required' => 'Pilih minimal 1 waktu pembelajaran',
            'waktu_ids.array'    => 'Format waktu tidak valid',
        ]);

        DB::beginTransaction();

        try {
            $path = $request->file('bukti')->store('bukti_Peminjaman', 'public');

            // Validasi & proses waktu_ids
            $jamPembelajaran = [];
            foreach ($validated['waktu_ids'] as $waktuJson) {
                $waktuData = json_decode($waktuJson, true);

                if (!is_array($waktuData) || !isset($waktuData['jam_ke'], $waktuData['start_time'], $waktuData['end_time'])) {
                    throw new \Exception('Format waktu tidak valid: ' . $waktuJson);
                }

                // Optimasi: cukup cek exists, tidak perlu ambil seluruh object
                $exists = waktu_pembelajaran::where('jam_ke', $waktuData['jam_ke'])
                    ->where('start_time', $waktuData['start_time'])
                    ->where('end_time', $waktuData['end_time'])
                    ->exists();

                if (!$exists) {
                    throw new \Exception("Waktu tidak ditemukan: Jam {$waktuData['jam_ke']}, {$waktuData['start_time']} - {$waktuData['end_time']}");
                }

                $jamPembelajaran[] = $waktuData;
            }

            $Peminjaman = Peminjaman::create([
                'keperluan'       => $validated['keperluan'],
                'user_id'         => Auth::id(),
                'item_id'         => $validated['item_id'],
                'tanggal'         => now()->toDateString(),
                'status_tujuan'   => 'Pending',
                'status_pinjaman' => 'dipinjam',
                'gambar_bukti'    => $path,
                'jam_pembelajaran' => json_encode($jamPembelajaran),
            ]);

            // Update status item
            Item::where('id', $validated['item_id'])
                ->update(['status_item' => 'dipinjam']); // Optimasi: tidak perlu find() dulu

            // Notifikasi ke admin
            $admins = User::where('role', 'admin')->get();
            if ($admins->isNotEmpty()) {
                \Illuminate\Support\Facades\Notification::send($admins, new PeminjamanBaruNotification($Peminjaman));
            }

            ActivityLoggerService::logCreated(
                'Peminjaman',
                $Peminjaman->id,
                ['keperluan' => $Peminjaman->keperluan, 'item_id' => $Peminjaman->item_id, 'status_tujuan' => 'Pending']
            );

            DB::commit();

            return response()->json([
                "status"  => true,
                "message" => "Peminjaman berhasil dibuat",
                "data"    => $Peminjaman->only(['id', 'keperluan', 'user_id', 'item_id', 'tanggal', 'status_tujuan', 'gambar_bukti', 'jam_pembelajaran'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            // Hapus file jika ada error setelah upload
            if (isset($path)) Storage::disk('public')->delete($path);

            return response()->json([
                "status"  => false,
                "message" => "gagal menyimpan data",
                "error"   => $e->getMessage()
            ], 500);
        }
    }

    fungsi api mobile peminjaman/store



     public function destroy(Request $request): JsonResponse
{
    // 1. Ambil data user sebelum logout agar tidak null
    $user = $request->user();
    $userName = $user ? $user->name : 'Pengguna';

    // 2. Log aktivitas (pastikan service ini menangkap data sebelum auth hilang)
    ActivityLoggerService::logLogout();

    // 3. Proses Logout
    Auth::guard('web')->logout();

    // 4. Invalidate & Regenerate Token (Standar Keamanan Laravel)
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return response()->json([
        'status'  => true,
        'message' => "Logout berhasil, semoga harimu menyenangkan " . $userName . "!",
    ], 200);
}

fungsi api mobile logout





public function store(Request $request): JsonResponse
    {
        $request->validate([
            'kode'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($request->only('kode', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'kode' => __('auth.failed'),
            ]);
        }

                $request->session()->regenerate();

        ActivityLoggerService::logLogin();
        if(!Auth::attempt($request->only('kode', 'password'))){
                return response()->json([
                    "message" => "login gagal",
                ],401);
        }

        $user = Auth::user();
        $token = $user->createToken("token",['*'], 
        now()->addHours(24))->plainTextToken;

        // return response()->json([
        //     "token" => $token,
        //     "user" => $user,
        // ], 200);

        // $redirect = match($user->role) {
        //     'admin' => route('admin.dashboard'),
        //     default => route('user.homepage'),
        // };

        return response()->json([
            'token' => $token,
            'status'   => true,
            'message'  => 'Login berhasil',
            // 'redirect' => $redirect,
            'user'     => [
                'id'    => $user->id,
                'name'  => $user->name,
                'kode' => $user->kode,
                'role'  => $user->role,
            ],
        ]);
    }


    fungsi api mobile login


