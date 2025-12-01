<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLoggerController extends Controller
{
    public function index(Request $request): View
    {
        $query = ActivityLogger::with('user')->latest();

        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }

        if ($request->has('model') && $request->model) {
            $query->where('model', $request->model);
        }

        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }

        if ($request->has('date') && $request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $activities = $query->paginate(20);

        $actions = ActivityLogger::distinct()->pluck('action');
        $models = ActivityLogger::distinct()->pluck('model');
        $roles = ActivityLogger::distinct()->pluck('role');
        $users = ActivityLogger::with('user')->distinct()->get(['user_id']);

        return view('admin.activitylogger.index', compact('activities', 'actions', 'models', 'roles', 'users'));
    }

    public function show(ActivityLogger $activityLogger): View
    {
        $activityLogger->load('user');

        return view('admin.activitylogger.show', compact('activityLogger'));
    }
    public function destroy(ActivityLogger $activityLogger)
    {
        $activityLogger->delete();

        return redirect()->route('admin.activitylogger.index')
            ->with('success', 'Activity log deleted successfully.');
    }

    public function clearOldLogs()
    {
        $cutoffDate = now()->subDays(30);
        ActivityLogger::where('created_at', '<', $cutoffDate)->delete();

        return redirect()->route('admin.activitylogger.index')
            ->with('success', 'Old activity logs cleared successfully.');
    }
}
