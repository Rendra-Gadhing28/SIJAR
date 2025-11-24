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
            $table->foreignId('item_id')->nullable()->constrained('item')->onDelete('cascade');

            $table->date('tanggal');
            $table->timestamp('finished_at')->nullable();

            $table->enum('status_tujuan', ['Pending', 'Approved', 'Rejected'] )->default('Pending');
            $table->enum('status_pinjaman',['dipinjam','selesai'])->default('dipinjam');
            $table->string('gambar_bukti');
            $table->json('jam_pembelajaran')->nullable();


            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

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
