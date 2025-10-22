<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLogger extends Model
{
    protected $fillable = [
        'user_id',
        'role',
        'ip_adress',
        'user_agents',
        'url',
        'model',
        'model_id',
        'action'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array'
    ];

    public function user():BelongsTo {
        return $this->belongsTo(User::class);
    }
}
