<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kategori;
use Illuminate\Support\Facades\DB; // Gunakan Facades\DB

class Item extends Model
{
    protected $table = 'item';

    protected $fillable = [
        'id',
        'nama_item',
        'kode_unit',
        'jenis_item',
        'kategori_jurusan_id',
        'status_item',
        'foto_barang',
    ];

    public static function getItem()
    {
        return Db::table('item');
    }

     public function kategori_jurusan()
    {
        return $this->belongsTo(Kategori::class, 'kategori_jurusan_id');
    }
  

}
