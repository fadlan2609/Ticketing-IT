<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = AppNotification::where('user_id', auth()->id())
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notif = AppNotification::where('user_id', auth()->id())->findOrFail($id);
        $notif->markAsRead();

        if ($notif->url) {
            return redirect($notif->url);
        }
        return back();
    }

    public function markAllAsRead()
    {
        AppNotification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    /**
     * API: Ambil notifikasi belum dibaca (untuk badge)
     */
    public function unreadCount()
    {
        $count = AppNotification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * API: Ambil 5 notifikasi terbaru (untuk dropdown)
     */
    public function latest()
    {
        $notifications = AppNotification::where('user_id', auth()->id())
            ->latest()
            ->limit(5)
            ->get();

        return response()->json($notifications);
    }
}