<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }

    public function read($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect($notification->data['url'] ?? route('admin.dashboard'));
    }

    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
}
