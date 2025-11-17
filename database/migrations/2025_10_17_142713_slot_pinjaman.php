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
        Schema::create('slot_peminjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->nullable()->constrained('peminjaman');
            $table->foreignId('waktu_pembelajaran_id')->nullable()->constrained('waktu_pembelajaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::dropIfExists('slot_peminjaman');
    }
};
