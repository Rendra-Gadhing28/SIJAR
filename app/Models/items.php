<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use app\Models\Item;
use Illuminate\Support\Facades\DB;

class Items extends Model
{
    protected $table = 'items';

    protected $fillable = [
        'item_id',
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


    public function itemm()
    {
        return $this->belongsTo(Item::class,'item_id');
    }
}
