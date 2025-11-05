<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    protected $table = 'jurusans';
    protected $fillable = [
        'nama_jurusan',
    ];

    public function items()
    {
        return $this->hasMany(items::class, 'jurusan_id');
    }
    public function user()
    {
        return $this->hasMany(User::class, 'jurusan_id');
    }
}
