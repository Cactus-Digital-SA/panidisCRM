<?php

namespace App\Domains\Notifications\Models;

use App\Domains\Notifications\Notifications\CactusPushNotification;
use App\Domains\Auth\Repositories\Eloquent\Models\User as EloquentUser;
use App\Domains\Notifications\NotificationInterface;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use Exception;

class PushNotification implements NotificationInterface
{
    use Notifiable;

    private string $id;
    /**
     * @var Recipient []
     */
    private array $recipients = [];

    private Recipient $recipient;
    private string $subject;
    private string $body;

    private ?string $senderImage = null;

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

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): PushNotification
    {
        $this->id = $id;
        return $this;
    }

    public function getRecipient(): Recipient
    {
        return $this->recipient;
    }

    public function setRecipient(Recipient $recipient): void
    {
        $this->recipient = $recipient;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function getSenderImage(): ?string
    {
        return $this->senderImage;
    }

    public function setSenderImage(?string $senderImage): PushNotification
    {
        $this->senderImage = $senderImage;
        return $this;
    }

    public function prepare()
    {
        // TODO: Implement prepare() method.
    }

    public function send()
    {
        foreach($this->getRecipients() as $recipient){
            try{
                $this->setRecipient($recipient);

                // use eloquent model to use morph type

                $user = EloquentUser::where('uuid', $this->getRecipient()->getId())->first();
                $user?->notify(new CactusPushNotification($this));

            }catch (Exception $e){
                Log::error('Notification Error: '.$e);
                return new Response(['message' => ''], 500);
            }
        }

        return new Response(['message' => 'send'], 200);
    }
}
