<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\AdminAlertNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class AdminNotifier
{
    /**
     * Notify Administrators (always) plus any Sub Admin holding the given permission
     * (pass null for notifications that have no dedicated manage-permission, e.g.
     * contact inquiries — those go to Administrators only).
     *
     * Never throws: a notification failure must not break the public flow that
     * triggered it, matching the existing swallow-on-failure pattern used for
     * every Mail::send() call in this codebase.
     */
    public static function send(
        ?string $permission,
        string $type,
        string $title,
        string $message,
        ?string $url = null,
        array $meta = [],
        string $icon = 'bell',
        string $color = 'primary',
    ): void {
        try {
            $recipients = User::role('Administrator')->get();

            if ($permission) {
                $recipients = $recipients->merge(User::permission($permission)->get());
            }

            $recipients = $recipients->unique('id');

            if ($recipients->isEmpty()) {
                return;
            }

            Notification::send($recipients, new AdminAlertNotification($type, $title, $message, $url, $meta, $icon, $color));
        } catch (\Throwable $e) {
            Log::error('AdminNotifier failed: ' . $e->getMessage(), ['type' => $type]);
        }
    }
}
