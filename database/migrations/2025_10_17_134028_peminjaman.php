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

            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade'); 
            $table->foreignId('items_id')->nullable()->constrained('items')->onDelete('cascade');

            $table->date('tanggal');
            $table->dateTime('dipinjam');
            $table->dateTime('dikembalikan')->nullable();
            $table->enum('status_pinjaman',['dipinjam','selesai'])->default('dipinjam');
            $table->string('gambar_bukti');
            $table->timestamps(); 
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
