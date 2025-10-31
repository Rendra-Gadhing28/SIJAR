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
            $table->foreignId('user_id')->constrained('users')->nullable(); 
            $table->foreignId('items')->constrained('items')->nullable();
            $table->enum('status_pinjaman',['dipinjam','dikembalikan']);
            $table->string('gambar_bukti')->nullable();
            $table->datetime('waktu')->nullable();
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
