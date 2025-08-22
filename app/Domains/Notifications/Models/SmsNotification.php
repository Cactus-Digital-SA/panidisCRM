<?php

namespace App\Domains\Notifications\Models;

use App\Domains\Auth\Repositories\Eloquent\Models\User as EloquentUser;
use App\Domains\Notifications\NotificationInterface;
use App\Domains\Notifications\Notifications\TwilioNotification;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SmsNotification implements NotificationInterface
{
    private Sender $sender;
    /**
     * @var Recipient []
     */
    private array $recipients = [];
    private Recipient $recipient;

    private string $body;

    public function getSender(): Sender
    {
        return $this->sender;
    }

    public function setSender(Sender $sender): void
    {
        $this->sender = $sender;
    }

    public function getRecipients(): array
    {
        return $this->recipients;
    }

    /**
     * @throws Exception
     */
    public function setRecipients(array $recipients): void
    {
        foreach ($recipients as $recipient) {
            if (!($recipient instanceof Recipient)) {
                throw new Exception("Όλα τα στοιχεία στον πίνακα θα πρέπει να είναι τύπου Recipient.");
            }
        }

        $this->recipients = $recipients;
    }

    public function getRecipient(): Recipient
    {
        return $this->recipient;
    }

    public function setRecipient(Recipient $recipient): void
    {
        $this->recipient = $recipient;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function prepare()
    {
        $this->setSender(new Sender('',config('services.twilio.from_name'), config('services.twilio.from')));
    }

    public function send()
    {
        foreach($this->getRecipients() as $recipient){
            try{
                if ($recipient->getPhone()):
                    try {
                        $this->setRecipient($recipient);

                        $notification = new TwilioNotification($this);
                        $notification->toSms();

                    } catch (Exception $e) {
                        Log::error('Send SMS Exception : '.$e);
                    }
                endif;
            }catch (Exception $e){
                Log::error('Notification Error: '.$e);
                return new Response(['message' => ''], 500);
            }
        }

        return new Response(['message' => 'send'], 200);
    }
}
