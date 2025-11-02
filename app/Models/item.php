<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\items;

class item extends Model
{
    protected $table = "item";
    protected $fillable = [
        'id',
        'item_id'
    ];
    public static function getItem(){
        return DB::table('item')->get();
    }
    public function item(){
        return $this->belongsTo(items::class);
    }
}
