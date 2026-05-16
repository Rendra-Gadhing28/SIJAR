<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();

        $query = $user->notifications();

        if ($request->filled('type')) {
            $query->where('type', 'like', '%' . $request->type . '%');
        }

        if ($request->has('unread') && $request->unread == '1') {
            $query->whereNull('read_at');
        }

        $notifications = $query->orderBy('created_at', 'desc')->paginate(10);

        $stats = DB::table('notifications')
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN read_at IS NULL THEN 1 ELSE 0 END) as unread,
                COUNT(DISTINCT type) as total_types
            ")
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil diambil',
            'data'    => [
                'notifications'       => $notifications,
                'totalNotifications'  => $stats->total,
                'unreadNotifications' => $stats->unread,
            ]
        ], 200);
    }

    public function trashed(Request $request): JsonResponse
    {
        $user = Auth::user();

        $query = DatabaseNotification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->onlyTrashed();

        if ($request->filled('type')) {
            $query->where('type', 'like', '%' . $request->type . '%');
        }

        if ($request->filled('days')) {
            $query->where('deleted_at', '>=', now()->subDays($request->days));
        }

        $notifications = $query->orderBy('deleted_at', 'desc')->paginate(15);

        $notificationTypes = DatabaseNotification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->distinct()
            ->pluck('type')
            ->map(fn($type) => class_basename($type))
            ->values();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi trash berhasil diambil',
            'data'    => [
                'notifications'     => $notifications,
                'notificationTypes' => $notificationTypes,
            ]
        ], 200);
    }

    public function markAsRead($id): JsonResponse
    {
        $notification = Auth::user()->notifications()->find($id);

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notifikasi tidak ditemukan.'
            ], 404);
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai sudah dibaca.'
        ], 200);
    }

    public function markAllAsRead(): JsonResponse
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi ditandai sudah dibaca.'
        ], 200);
    }

    public function destroy($id): JsonResponse
    {
        $notification = Auth::user()->notifications()->find($id);

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notifikasi tidak ditemukan.'
            ], 404);
        }

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi dipindahkan ke trash.'
        ], 200);
    }

    public function restore($id): JsonResponse
    {
        $user = Auth::user();

        $notification = DatabaseNotification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->onlyTrashed()
            ->find($id);

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notifikasi tidak ditemukan.'
            ], 404);
        }

        $notification->restore();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dipulihkan.'
        ], 200);
    }

    public function forceDelete($id): JsonResponse
    {
        $user = Auth::user();

        $notification = DatabaseNotification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->onlyTrashed()
            ->find($id);

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notifikasi tidak ditemukan.'
            ], 404);
        }

        $notification->forceDelete();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi dihapus permanen.'
        ], 200);
    }

    public function clearTrash(): JsonResponse
    {
        $user = Auth::user();

        $count = DatabaseNotification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->onlyTrashed()
            ->count();

        DatabaseNotification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->onlyTrashed()
            ->forceDelete();

        return response()->json([
            'success' => true,
            'message' => "{$count} notifikasi dihapus permanen dari trash."
        ], 200);
    }

    public function massAction(Request $request): JsonResponse
    {
        $request->validate([
            'action' => 'required|in:read,unread,delete,restore,force_delete',
            'ids'    => 'required|array',
            'ids.*'  => 'exists:notifications,id'
        ]);

        $notifications = Auth::user()->notifications()->whereIn('id', $request->ids);

        switch ($request->action) {
            case 'read':
                $notifications->update(['read_at' => now()]);
                $message = 'Notifikasi ditandai sebagai dibaca.';
                break;

            case 'unread':
                $notifications->update(['read_at' => null]);
                $message = 'Notifikasi ditandai sebagai belum dibaca.';
                break;

            case 'delete':
                $notifications->delete();
                $message = 'Notifikasi dipindahkan ke trash.';
                break;

            case 'restore':
                Auth::user()->notifications()->onlyTrashed()
                    ->whereIn('id', $request->ids)
                    ->restore();
                $message = 'Notifikasi berhasil dipulihkan.';
                break;

            case 'force_delete':
                Auth::user()->notifications()->onlyTrashed()
                    ->whereIn('id', $request->ids)
                    ->forceDelete();
                $message = 'Notifikasi dihapus permanen.';
                break;
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ], 200);
    }
}