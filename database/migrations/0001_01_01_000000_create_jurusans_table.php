<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jurusans', function (Blueprint $table) {
            $table->id();
            $table->enum('nama_jurusan', ['PPLG 1','PPLG 2','PPLG 3',
             'LK 1','LK 2',
             'TJKT 1','TJKT 2',
             'DKV 1','DKV 2','DKV 3',
             'PS 1','PS 2',
            'admin']);
            $table->foreignId('kategori_id')->nullable()->constrained('kategori_jurusan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurusans');
    }
};
