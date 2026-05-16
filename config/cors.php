<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', '*'],

    'allowed_methods' => ['*','GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],

    /*
     * ADVANCED PRACTICE: Jangan pernah hardcode origin di sini.
     * Ambil dari .env. Jika tidak ada, gunakan default.
     * Contoh di .env: CORS_ALLOWED_ORIGINS=http://localhost:5173,https://domainproduction.com
     */
    'allowed_origins' => explode(',', env('CORS_ALLOWED_ORIGINS', 'http://localhost:5173')),

    /*
     * ADVANCED PRACTICE: Gunakan regex untuk local development.
     * Ini akan mengizinkan akses dari Emulator, Port React yang berubah-ubah, 
     * dan IP Wi-Fi lokal tanpa harus update config terus menerus.
     */
    'allowed_origins_patterns' => env('APP_ENV') === 'local' ? [
        '#^https?://localhost(:\d+)?$#',          // React/Vite di port berapapun
        '#^https?://127\.0\.0\.1(:\d+)?$#',       // Localhost IP
        '#^https?://10\.0\.2\.2(:\d+)?$#',        // Android Emulator default IP
        '#^https?://192\.168\.\d+\.\d+(:\d+)?$#', // Physical device testing via Local Wi-Fi
    ] : [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 86400,

    /*
     * PENTING: Karena ini `true` (biasanya untuk Sanctum/Auth berbasis Cookie), 
     * kamu TIDAK BOLEH menggunakan 'allowed_origins' => ['*'].
     * Standar spesifikasi W3C melarang wildcard origin jika credentials diizinkan.
     */
    'supports_credentials' => true,
];