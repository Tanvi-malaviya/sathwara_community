<?php

namespace App\View\Composers;

use Illuminate\View\View;

class AdminNotificationComposer
{
    public function compose(View $view): void
    {
        $user = auth()->user();

        if (!$user) {
            $view->with('unreadNotificationsCount', 0);
            $view->with('recentNotifications', collect());
            return;
        }

        $view->with('unreadNotificationsCount', $user->unreadNotifications()->count());
        $view->with('recentNotifications', $user->notifications()->latest()->take(10)->get());
    }
}
