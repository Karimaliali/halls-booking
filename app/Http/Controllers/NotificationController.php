<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * Get user's notifications
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $notifications = $user->notifications()
            ->latest()
            ->paginate(20);

        return response()->json([
            'notifications' => $notifications
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, DatabaseNotification $notification)
    {
        // Ensure the notification belongs to the authenticated user
        if ($notification->notifiable_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->markAsRead();

        return response()->json([
            'message' => 'Notification marked as read',
            'notification' => $notification
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request)
    {
        $user = $request->user();
        $user->unreadNotifications()->update(['read_at' => now()]);

        return response()->json([
            'message' => 'All notifications marked as read'
        ]);
    }

    /**
     * Get unread notifications count
     */
    public function unreadCount(Request $request)
    {
        $user = $request->user();
        $count = $user->unreadNotifications()->count();

        return response()->json([
            'unread_count' => $count
        ]);
    }
}
