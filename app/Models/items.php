<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
class items extends Model
{
    protected $table = 'items' ;
    protected $fillable = [
        'id',
        'nama_item',
        'jenis_item',
        'stok_barang',
        'foto_barang',
        'status item',    
    ];
    public static function getItem(){
        return DB::table('items')->get();
    }
        public function peminjaman(){
        return $this->hasMany(item::class);
    }
        public function peminjaman(){
        return $this->hasMany(item::class);
    }

}
