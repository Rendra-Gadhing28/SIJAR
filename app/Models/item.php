<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class item extends Model
{
    protected $table = 'item' ;
    protected $fillable = [
        'id',
        'nama_item',
        'jenis_item',
        'stok_barang',
        'kondisi_barang',
        'foto_barang',
    ];

    
}
