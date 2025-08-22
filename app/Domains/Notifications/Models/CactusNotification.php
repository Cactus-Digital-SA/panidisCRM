<?php

namespace App\Domains\Notifications\Models;

use App\Domains\Notifications\NotificationInterface;
use Exception;

class CactusNotification
{
    private array $notifications = [];

    /**
     * @throws Exception
     */
    public function __construct(array $notifications = []) {
        foreach ($notifications as $notification) {
            if (!($notification instanceof NotificationInterface)) {
                throw new Exception("Όλα τα στοιχεία στον πίνακα θα πρέπει να είναι τύπου NotificationInterface.");
            }
        }

        $this->notifications = $notifications;
    }


    public function get(): array {
        return $this->notifications;
    }

    public function add(NotificationInterface $notification): void {
        $this->notifications[] = $notification;
    }

}
