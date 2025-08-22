<?php

namespace App\Domains\Notifications\Http\Controllers;

use App\Domains\Notifications\Http\Requests\SendEmailNotificationRequest;
use App\Domains\Notifications\Http\Requests\SendNotificationRequest;
use App\Domains\Notifications\Http\Requests\SendSMSNotificationRequest;
use App\Domains\Notifications\Models\CactusNotification;
use App\Domains\Notifications\Models\EmailNotification;
use App\Domains\Notifications\Models\Recipient;
use App\Domains\Notifications\Models\Sender;
use App\Domains\Notifications\Models\SmsNotification;
use App\Domains\Notifications\Services\NotificationsService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class TestNotificationsController extends Controller
{

    public function __construct()
    {

    }

    /**
     * Test Send Email
     * @throws \Exception
     */
    public function sendEmailFromApi(SendNotificationRequest $request)
    {
        $recipients = [];
        foreach($request['recipients'] as $recipient){
            $recipients[] = new Recipient($recipient['email'],$recipient['name']);
        }

        $emailDTO = new EmailNotification();
        $emailDTO->setRecipients($recipients);
        $emailDTO->setSubject($request['subject'] ?? '');
        $emailDTO->setGreeting($request['greeting'] ?? '');
        $emailDTO->setBody($request['body'] ?? '');

        $cactusNotification = new CactusNotification([$emailDTO]);

        // Αποστολή Ειδοποίησης
        $notificationService = new NotificationsService();
        $notificationService->send($cactusNotification);

        return new Response(['message' => 'send'], 200);
    }

    public function sendEmailSendGrid(SendEmailNotificationRequest $request)
    {
        try {
            $email = $request['ToEmail'];
            $recipient = new Recipient($email, 'Test User');

            $emailDTO = new EmailNotification();
            $emailDTO->setRecipient($recipient);
            $emailDTO->setSubject('Test Email - PrivateLessons.gr');
            $emailDTO->setBody('Δοκιμή');

            $sender = new Sender($request['FromEmail'],'Private Lessons');
            $emailDTO->setSender($sender);

            $cactusNotification = new CactusNotification([$emailDTO]);

            // Αποστολή Ειδοποίησης
            $notificationService = new NotificationsService();
            $notificationService->send($cactusNotification);

            return new Response(['message' => 'send'], 200);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }

        return new Response(['message' => 'error'], 500);

    }

    public function sendSms(SendSMSNotificationRequest $request)
    {
        // initialize Recipient
        $recipient = new Recipient('', $request['name'], $request['phone']);

        try {
            $SmsNotificationDTO = new SmsNotification();
            $SmsNotificationDTO->setRecipients([$recipient]);
            $SmsNotificationDTO->setBody('Δοκιμή');

            $cactusNotification = new CactusNotification([$SmsNotificationDTO]);

            // Αποστολή Ειδοποίησης
            $notificationService = new NotificationsService();
            $notificationService->send($cactusNotification);

            return new Response(['message' => 'send'], 200);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }

        return new Response(['message' => 'error'], 500);
    }

}
