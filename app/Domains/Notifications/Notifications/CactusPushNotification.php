<?php

namespace App\Domains\Notifications\Notifications;

use App\Domains\Notifications\Models\PushNotification;
use App\Events\NotificationSent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class CactusPushNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly PushNotification $pushNotification)
    {

    }

    public function viaQueues(): array
    {
        return [
            'broadcast' => 'notification-queue',
            'database' => 'database-queue',
        ];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['database','broadcast'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'image' => $this->pushNotification->getSenderImage(),
            'subject' => $this->pushNotification->getSubject(),
            'body' => $this->pushNotification->getBody(),
        ];
    }

    public function toBroadcast($notifiable): array
    {
        $notificationObject = (object)[
            'user' => (object)[
                'uuid' => $this->pushNotification->getRecipient()->getId()
            ],
            'data' => [
                'id' => $this->id ?? null,
                'image' => asset($this->pushNotification->getSenderImage()),
                'subject' => $this->pushNotification->getSubject(),
                'body' => $this->pushNotification->getBody(),
            ],
        ];

        $notify = broadcast(new NotificationSent($notificationObject));

        return [
            'data' => [
                'id' => $this->id ?? null,
                'image' => $this->pushNotification->getSenderImage(),
                'subject' => $this->pushNotification->getSubject(),
                'body' => $this->pushNotification->getBody(),
            ],
        ];
    }
}
