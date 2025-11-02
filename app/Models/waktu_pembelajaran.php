<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use db;

class waktu_pembelajaran extends Model
{
    protected $table = "waktu_pembelajaran";
    protected $fillable = [
        'id',
        'start_time',
        'end_time',
        'pembelajaran'
    ];

    public static function pembelajaran(){
        return db::table('waktu_pembelajaran')->get();
    }
    public function slotPeminjaman(){
        return $this->hasMany(slot_peminjaman::class);
    }
}
