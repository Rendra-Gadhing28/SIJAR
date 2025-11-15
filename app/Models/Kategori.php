<?php

namespace App\Models;

use DB;
use app\Models\Item;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = "kategori_jurusan";
    protected $fillable = [
        'id',
        'nama_kategori'
    ];

    public static function getKategori(){
        return DB::table('kategori_jurusan')->get();
    }
     public function items()
    {
        return $this->hasMany(Item::class, 'kategori_jurusan_id');
    }
    public static function getKategoriById($id){
        return DB::table('kategori_jurusan')->where('id', $id)->first();
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }
    public function item()
    {
        return $this->hasMany(Item::class);
    }
}
