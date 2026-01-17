<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Halaman index notifikasi
     */
    public function index()
    {
        try {
            $notifications = Notification::orderBy('created_at', 'desc')
                ->paginate(20);

            return view('admin.notifications.index', compact('notifications'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat notifikasi: ' . $e->getMessage());
        }
    }

    /**
     * Get unread notifications (untuk AJAX)
     */
    public function getUnread()
    {
        try {
            $notifications = Notification::unread()
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get()
                ->map(function ($notif) {
                    return [
                        'id' => $notif->id,
                        'type' => $notif->type,
                        'message' => $notif->message,
                        'url' => $notif->url,
                        'time_ago' => $notif->created_at->diffForHumans(),
                        'created_at' => $notif->created_at->format('Y-m-d H:i:s'),
                    ];
                });

            $count = Notification::unread()->count();

            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'count' => $count,
                'has_new' => $notifications->isNotEmpty() // Flag untuk notifikasi baru
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat notifikasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        try {
            $notification = Notification::findOrFail($id);
            $notification->update(['is_read' => true]);

            // Return JSON jika AJAX request
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Notifikasi ditandai sebagai dibaca'
                ]);
            }

            // Redirect ke URL notifikasi jika bukan AJAX
            return redirect($notification->url);
        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Gagal menandai notifikasi: ' . $e->getMessage());
        }
    }

    /**
     * Mark all as read
     */
    public function markAllAsRead()
    {
        try {
            $updated = Notification::unread()->update(['is_read' => true]);

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Semua notifikasi telah dibaca',
                    'updated_count' => $updated
                ]);
            }

            return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca');
        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Gagal menandai semua notifikasi: ' . $e->getMessage());
        }
    }
}
