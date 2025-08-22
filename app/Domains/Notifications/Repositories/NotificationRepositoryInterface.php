<?php

namespace App\Domains\Notifications\Repositories;

use App\Repositories\RepositoryInterface;

interface NotificationRepositoryInterface extends RepositoryInterface
{
    public function markAllAsReadByUserId(string $userId): bool;

    public function markNotification(string $userId, string $notificationId): bool;
}
