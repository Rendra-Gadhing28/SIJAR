<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kategori;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;
    
    protected $table = 'item';

    protected $fillable = [
        'id',
        'nama_item',
        'jenis_item',
        'kode_unit',
        'kategori_jurusan_id',
        'foto_barang',
        'status_item',
     
    ];

    public static function getItem()
    {
        return DB::table('item');
    }

     public function kategori_jurusan()
    {
        return $this->belongsTo(Kategori::class, 'kategori_jurusan_id');
    }
     public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }
  

}
