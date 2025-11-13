<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Items extends Model
{
    protected $table = 'item' ;
    protected $fillable = [
        'id',
        'nama_item',
        'kode_unit',
        'jenis_item',
        'kategori_jurusan_id',
        'status_item',   
        'foto_barang',
         
    ];
    public static function getItem(){
        return DB::table('item')->get();
    }
        public function item(){
        return $this->hasMany(item::class);
    } 
    public function kategoriJurusan()
    {
        return $this->belongsTo(Kategori::class, 'kategori_jurusan_id');
    }
     protected $casts = [
        'status_item' => 'string',
    ];
    public function getStatusColorAttribute()
    {
        return match($this->status_item) {
            'tersedia' => 'green',
            'dipinjam' => 'yellow',
            'rusak' => 'red',
            default => 'gray'
        };
    }


    protected function jenisItem(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => ucfirst(strtolower($value)) // "proyektor" -> "Proyektor"
        );
    }

}
