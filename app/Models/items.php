<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
class Items extends Model
{
    protected $table = 'items' ;
    protected $fillable = [
        'id',
        'nama_item',
        'jenis_item',
        'nama_jurusan',
        'kategori_jurusan_id',
        'stok_barang', 
        'foto_barang',
        'status_item',    
    ];
    public static function getItem(){
        return DB::table('items')->get();
    }
        public function item(){
        return $this->hasMany(item::class);
    } 

    protected function jenisItem(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => ucfirst(strtolower($value)) // "proyektor" -> "Proyektor"
        );
    }

}
