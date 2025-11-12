<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    protected $table = 'items';

    protected $fillable = [
        'item_id',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
