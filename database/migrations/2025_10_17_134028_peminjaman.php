<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('keperluan');
            $table->foreignId('user_id')->constrained('users'); 
            $table->foreignId('items')->constrained('items');
            $table->enum('status_pinjaman',['proses','diperbolehkan','tidak diperbolehkan']);
            $table->string('jam_ke');
            $table->string('gambar_bukti')->nullable();
            $table->datetime('waktu')->nullable();
            $table->timestamps(); 
            $table->foreign('jurusan')->references('jurusan')->on('users')->onDelete('cascade');
            $table->foreign('kelas')->references('kelas')->on('users')->onDelete('cascade');
            $table->foreign('jurusan')->references('jurusan')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
