<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Jurusan extends Model
{
    protected $table = 'jurusans';
    protected $fillable = [
        'id',
        'nama_jurusan',
        'kategori_id',
    ];

    public static function getAll(){
        return DB::table('jurusans');
    }

    public function items()
    {
        return $this->hasMany(items::class, 'jurusan_id');
    }
    public function user()
    {
        return $this->hasMany(User::class, 'jurusan_id');
    }
}
