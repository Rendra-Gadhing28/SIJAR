<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\items;

class Item extends Model
{
    protected $table = "items";
    protected $fillable = [
        'id',
        'item_id'
    ];
    public static function getItem(){
        return DB::table('items')->get();
    }
    public function items(){
        return $this->belongsTo(items::class);
    }
}
