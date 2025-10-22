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
         Schema::create('waktu_pembelajaran', function (Blueprint $table) {
            $table->id();
            $table->integer('jam_ke');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('pembelajaran',50);
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::dropIfExists('waktu_pembelajaran');
    }
};
