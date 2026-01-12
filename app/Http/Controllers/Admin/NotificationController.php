<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Get unread notifications (untuk AJAX)
    public function getUnread()
    {
        $notifications = Notification::unread()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $count = Notification::unread()->count();

        return response()->json([
            'notifications' => $notifications,
            'count' => $count
        ]);
    }

    // Mark notification as read
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['is_read' => true]);

        return redirect($notification->url);
    }

    // Mark all as read
    public function markAllAsRead()
    {
        Notification::unread()->update(['is_read' => true]);

        return response()->json(['message' => 'Semua notifikasi telah dibaca']);
    }
}
