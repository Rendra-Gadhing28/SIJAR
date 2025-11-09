<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\User;
use App\Models\items;

class Peminjaman extends Model
{
    protected $table = "peminjaman";
    protected $fillable = [
        'id',
        'keperluan',
        'user_id',
        'items_id',
        'status_pinjaman',
        'gambar_bukti',
        'waktu'       
    ];
    public static function getItem(){
        return DB::table('peminjaman')->get();
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function items(){
        return $this->belongsTo(items::class);
    }
     public function slotPeminjaman(){
        return $this->hasMany(slot_peminjaman::class);
    }
}
