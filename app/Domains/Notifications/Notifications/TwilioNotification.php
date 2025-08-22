<?php

namespace App\Domains\Notifications\Notifications;

use App\Domains\Notifications\Models\SmsNotification;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class TwilioNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly SmsNotification $smsNotification)
    {

    }

    public function viaQueues(): array
    {
        return [
            'sms' => 'sms-queue',
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
        return [];
    }

    public function toSms(): void
    {
        try {
            $twilio = new Client(config('services.twilio.sid'), config('services.twilio.token'));

            $twilio->messages->create($this->smsNotification->getRecipient()->getPhone(), [
                'from' => $this->smsNotification->getSender()->getPhone(),
                'body' => $this->smsNotification->getBody(),
            ]);

            Log::info('Message sent to ' . $this->smsNotification->getRecipient()->getPhone());
        } catch (Exception $e) {
            Log::error(
                'Could not send SMS notification.' .
                ' Twilio replied with: ' . $e
            );
        }


    }
}
