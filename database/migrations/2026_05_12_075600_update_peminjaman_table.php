// database/migrations/2026_05_11_update_peminjaman_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePeminjamanTable extends Migration
{
    public function up()
    {
        // Schema::table('peminjaman', function (Blueprint $table) {
        //     $table->text('bukti_pengembalian');
        // });
        
        // RENAME kolom finished_at jadi returned_at (opsional)
        // Schema::table('peminjaman', function (Blueprint $table) {
        //     $table->renameColumn('finished_at', 'returned_at');
        // });
        
        // UPDATE enum status_pinjaman
        // DB::statement("ALTER TABLE peminjaman MODIFY status_pinjaman ENUM('dipinjam', 'dikembalikan', 'telat', 'verifikasi') DEFAULT 'dipinjam'");
    }


}