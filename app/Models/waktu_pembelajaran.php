<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class waktu_pembelajaran extends Model
{
    protected $table = "waktu_pembelajaran";
    protected $fillable = [
        'id',
        'jam_ke',
        'start_time',
        'end_time',
    ];

    public static function pembelajaran(){
        return DB::table('waktu_pembelajaran')->get();
    }
    public function slotPeminjaman(){
        return $this->hasMany(slot_peminjaman::class);
    }
}
