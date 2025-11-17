<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Items;
use DB;

class Peminjaman extends Model
{
    protected $table = "peminjaman";
    protected $fillable = [
        'id',
        'keperluan',
        'user_id',
        'item_id',
        'tanggal',
        'dipinjam',
        'dikembalikan',
        'status_pinjaman',
        'gambar_bukti',
    ];
    public static function getItem()
    {
        return DB::table('peminjaman');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function items()
    {
        return $this->belongsTo(Items::class, 'items_id');
    }
    public function slotPeminjaman()
    {
        return $this->hasMany(slot_peminjaman::class);
    }
    public function waktu_pembelajaran()
    {
        return $this->belongsTo(waktu_pembelajaran::class, 'waktu_id');
    }
}
