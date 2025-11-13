<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        return self::all();
    }

}
