<?php

namespace App\Domains\Notifications\Notifications;

use App\Domains\Notifications\Models\EmailNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CactusEmailNotification extends Notification
    implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(private readonly EmailNotification $emailNotification)
    {

    }

    public function viaQueues(): array
    {
        return [
            'mail' => 'email-queue',
        ];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        $message = new MailMessage();

        $emailNotification = $this->emailNotification;
        $message = $message->subject($emailNotification->getSubject())
            ->greeting(' ')
            ->from($emailNotification->getSender()->getEmail(), $emailNotification->getSender()->getName());

        $message->line($emailNotification->getBody() ?? '');
        $message->action($emailNotification->getAction()['text'] ?? null, $emailNotification->getAction()['url'] ?? null);

        $buttonClass = null;
        // Iterate over content blocks and add them in the specified order
        foreach ($emailNotification->getContentBlocks() ?? [] as $block) {
            if ($block['type'] === 'body') {
                $message->line($block['content']);
            } elseif ($block['type'] === 'action') {
                $message->action($block['content']['text'], $block['content']['url']);
                $buttonClass = $block['content']['buttonClass'] ?? null;
            }
        }

        $message->salutation('<br>Με εκτίμηση, <br>Η ομάδα του privateℓessons.&#8204;gr');

        if ($emailNotification->getAttachments()) {
            foreach ($emailNotification->getAttachments() as $attachment) {
                $message->attach($attachment);
            }
        }


        $contentBeforeFooter = $emailNotification->getContentBeforeFooter();


//        $buttonClass = 'btn bg-blue text-white d-flex justify-content-center align-items-center py-3 fwc-4 fsc-7';
        $message->markdown('notifications::email', [
            'buttonClass' => $emailNotification->getAction()['buttonClass'] ?? $buttonClass ?? null,
            'contentBeforeFooter'  => $contentBeforeFooter,  // Pass the final lines correctly to the view
        ]);


        return $message;
    }


}
