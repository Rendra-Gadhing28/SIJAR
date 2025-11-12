<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Items;

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
    public static function getItem()
    {
        return self::all();
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
