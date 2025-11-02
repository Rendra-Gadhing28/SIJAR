<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\peminjaman;
use App\Models\waktu_pembelajaran;
use db;

class slot_peminjaman extends Model
{
    protected $table = "slot_peminjaman";
    protected $fillable = [
        'id',
        'peminjaman_id',
        'waktu_pembelajaran'
    ];

    public static function getSlotPeminjaman(){
        return db::table('slot_peminjaman')->get();
    }

    public function peminjaman(){
        return $this->belongsTo(peminjaman::class);
    }

    public function waktu(){
        return $this->belongsTo(waktu_pembelajaran::class);
    }
}
