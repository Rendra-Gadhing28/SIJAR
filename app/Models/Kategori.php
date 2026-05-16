<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use App\Models\Item;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Kategori extends Model
{
    protected $table = "kategori_jurusan";
    protected $fillable = [
        'id',
        'nama_kategori',
        'icon',
        'role',
        'created_at',
        'updated_at'
    ];

    public static function getKategori(){
        return DB::table('kategori_jurusan')->get();
    }
     public function Item()
    {
        return $this->belongsTo(Item::class, 'kategori_jurusan_id');
    }
    public static function getKategoriById($id){
        return DB::table('kategori_jurusan')->where('id', $id)->first();
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'kategori_jurusan_id'  );
    }
}
