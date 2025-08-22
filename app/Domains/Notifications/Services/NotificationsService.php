<?php

namespace App\Domains\Notifications\Services;

use App\Domains\Notifications\Models\CactusNotification;
use App\Domains\Notifications\Models\EmailNotification;
use App\Domains\Notifications\Models\PushNotification;
use App\Domains\Notifications\Models\SmsNotification;
use App\Domains\Notifications\Repositories\NotificationRepositoryInterface;
use Exception;

class NotificationsService
{
    public function __construct(protected ?NotificationRepositoryInterface $repository = null)
    {

    }

    public function send(CactusNotification $cactusNotification): bool
    {
        $notifications = $cactusNotification->get();

        foreach($notifications as $notification){

            if ($notification instanceof EmailNotification) {
                try {
                    $notification->prepare();
                    $response = $notification->send();
                } catch (Exception $e) {
                    \Log::error('Notification exception: '. $e->getMessage());
                }
            }
            else if ($notification instanceof PushNotification) {
                try {
                    $response = $notification->send();
                } catch (Exception $e) {
                    \Log::error('Notification exception: '. $e->getMessage());
                }
            }
            else if ($notification instanceof SmsNotification) {
                try {
                    $notification->prepare();
                    $response = $notification->send();
                } catch (Exception $e) {
                    \Log::error('Notification exception: '. $e->getMessage());
                }
            }
        }

        return true;
    }

    public function markAllAsRead(string $userId): bool
    {
        return $this->repository->markAllAsReadByUserId($userId);
    }

    public function markNotification(string $userId, string $notificationId): bool
    {
        return $this->repository->markNotification($userId, $notificationId);
    }

}
