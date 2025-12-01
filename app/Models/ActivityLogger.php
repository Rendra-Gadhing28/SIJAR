<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLogger extends Model
{
    protected $fillable = [
        'user_id',
        'role',
        'ip_address',
        'user_agents',
        'url',
        'model',
        'model_id',
        'action',
        'old_values',
        'new_values',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array'
    ];

    public function user():BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function getActionBadgeColor(): string
{
    return match($this->action) {
        'created' => 'success',
        'updated' => 'warning',
        'deleted' => 'danger',
        'login' => 'info',
        'logout' => 'secondary',
        default => 'primary'
    };
    }
}
