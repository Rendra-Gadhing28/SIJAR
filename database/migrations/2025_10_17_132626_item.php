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
        Schema::create('item', function (Blueprint $table) {
            $table->id();
            $table->string('nama_item');
            $table->string('jenis_item');
            $table->bigInteger('stok_barang');
            $table->string('foto_barang');
            $table->timestamps(); 
            $table->softDeletes();
        });
        DB::statement('ALTER TABLE item AUTO_INCREMENT = 1000;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item');
    }
};
