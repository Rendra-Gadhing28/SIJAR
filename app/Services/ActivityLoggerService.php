<?php

namespace App\Services;

use App\Models\ActivityLogger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLoggerService
{
    /**
     * Log an activity
     */
    public static function log(
        string $action,
        ?string $model = null,
        ?int $model_id = null,
        ?array $old_values = null,
        ?array $new_values = null
    ): ActivityLogger {
        $user = Auth::user();

        return ActivityLogger::create([
            'user_id' => $user?->id,
            'role' => $user?->role,
            'ip_address' => Request::ip(),
            'user_agents' => Request::userAgent(),
            'url' => Request::fullUrl(),
            'model' => $model,
            'model_id' => $model_id,
            'action' => $action,
            'old_values' => $old_values,
            'new_values' => $new_values,
        ]);
    }

    /**
     * Log user login
     */
    public static function logLogin(): ActivityLogger
    {
        return static::log('login');
    }

    /**
     * Log user logout
     */
    public static function logLogout(): ActivityLogger
    {
        return static::log('logout');
    }

    /**
     * Log model creation
     */
    public static function logCreated(string $modelName, int $modelId, array $newValues = []): ActivityLogger
    {
        return static::log('created', $modelName, $modelId, null, $newValues);
    }

    /**
     * Log model update
     */
    public static function logUpdated(string $modelName, int $modelId, array $oldValues = [], array $newValues = []): ActivityLogger
    {
        return static::log('updated', $modelName, $modelId, $oldValues, $newValues);
    }

    /**
     * Log model deletion
     */
    public static function logDeleted(string $modelName, int $modelId, array $oldValues = []): ActivityLogger
    {
        return static::log('deleted', $modelName, $modelId, $oldValues, null);
    }
}
