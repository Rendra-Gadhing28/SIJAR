<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications.
     */
    public function index(Request $request)
{
    $user = Auth::user();

    // ✅ Debug dulu — cek apakah data ada
    $rawCheck = DB::table('notifications')
        ->where('notifiable_id', $user->id)
        ->where('notifiable_type', get_class($user)) // "App\Models\User"
        ->count();
    
    

    $query = $user->notifications();

    if ($request->filled('type')) {
        $query->where('type', 'like', '%' . $request->type . '%');
    }

    if ($request->has('unread') && $request->unread == '1') {
        $query->whereNull('read_at');
    }

    $notifications = $query->orderBy('created_at', 'desc')->paginate(10);

    // ✅ Gabung statistik dalam 1 query
    $stats = DB::table('notifications')
        ->where('notifiable_id', $user->id)
        ->where('notifiable_type', get_class($user))
        ->selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN read_at IS NULL THEN 1 ELSE 0 END) as unread,
            COUNT(DISTINCT type) as total_types
        ")
        ->first();

    // ✅ Ambil tipe unik
    $notificationTypes = DB::table('notifications')
        ->where('notifiable_id', $user->id)
        ->where('notifiable_type', get_class($user))
        ->distinct()
        ->pluck('type')
        ->map(fn($type) => class_basename($type))
        ->values();

    return response()->json([
        'status'  => true,
        'message' => 'Notifikasi berhasil diambil',
        'data'    => [
            'notifications'      => $notifications,
            'totalNotifications' => $stats->total,
            'unreadNotifications'=> $stats->unread,
        ]
    ], 200);
}
        // return view('admin.notifications.index', compact(
        //     'notifications',
        //     'totalNotifications',
        //     'unreadNotifications',
        //     'notificationTypes'
        // ));
    
    
    /**
     * Display a listing of trashed notifications.
     */
    public function trashed(Request $request)
    {
        $user = Auth::user();
        
        // Query langsung dari DatabaseNotification untuk akses onlyTrashed()
        $query = DatabaseNotification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->onlyTrashed();
        
        if ($request->filled('type')) {
            $query->where('type', 'like', '%' . $request->type . '%');
        }
        if ($request->filled('days')) {
            $days = $request->days;
            $date = now()->subDays($days);
            $query->where('deleted_at', '>=', $date);
        }
        
        $notifications = $query->orderBy('deleted_at', 'desc')->paginate(15);
        
        $notificationTypes = DatabaseNotification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->select('type')
            ->distinct()
            ->pluck('type')
            ->map(function ($type) {
                return class_basename($type);
            })
            ->unique();
        
        return view('admin.notifications.trashed', compact('notifications', 'notificationTypes'));
    }
    
    /**
     * Mark notification as read.
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        
        if (!$notification) {
            return back()->withErrors('Notifikasi tidak ditemukan.');
        }
        
        $notification->markAsRead();
        
        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }
    
    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        
        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
    
    /**
     * Soft delete notification.
     */
    public function destroy($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        
        if (!$notification) {
            return back()->withErrors('Notifikasi tidak ditemukan.');
        }
        
        $notification->delete();
        
        return back()->with('success', 'Notifikasi dipindahkan ke trash.');
    }
    
    
    /**
     * Restore notification from trash.
     */
    public function restore($id)
    {
        $user = Auth::user();
        
        // Query langsung dari DatabaseNotification untuk akses onlyTrashed()
        $notification = DatabaseNotification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->onlyTrashed()
            ->find($id);
        
        if (!$notification) {
            return back()->withErrors('Notifikasi tidak ditemukan.');
        }
        
        $notification->restore();
        
        return redirect()->route('admin.notifications.trashed')
            ->with('success', 'Notifikasi berhasil dipulihkan.');
    }
    
    /**
     * Permanently delete notification.
     */
    public function forceDelete($id)
    {
        $user = Auth::user();
        
        // Query langsung dari DatabaseNotification untuk akses onlyTrashed()
        $notification = DatabaseNotification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->onlyTrashed()
            ->find($id);
        
        if (!$notification) {
            return back()->withErrors('Notifikasi tidak ditemukan.');
        }
        
        $notification->forceDelete();
        
        return redirect()->route('admin.notifications.trashed')
            ->with('success', 'Notifikasi dihapus permanen.');
    }
    
    /**
     * Clear all trashed notifications.
     */
    public function clearTrash()
    {
        $user = Auth::user();
        
        // Query langsung dari DatabaseNotification untuk akses onlyTrashed()
        $count = DatabaseNotification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->onlyTrashed()
            ->count();
        
        DatabaseNotification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->onlyTrashed()
            ->forceDelete();
        
        return redirect()->route('admin.notifications.trashed')
            ->with('success', "{$count} notifikasi dihapus permanen dari trash.");
    }
    
    /**
     * Mass actions for notifications.
     */
    public function massAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:read,unread,delete,restore,force_delete',
            'ids' => 'required|array',
            'ids.*' => 'exists:notifications,id'
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
        ]);
    }
}