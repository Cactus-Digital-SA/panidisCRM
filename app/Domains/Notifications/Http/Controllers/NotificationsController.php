<?php

namespace App\Domains\Notifications\Http\Controllers;

use App\Domains\Notifications\Services\NotificationsService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NotificationsController extends Controller
{
    private NotificationsService $notificationsService;

    public function __construct(NotificationsService $notificationsService)
    {
        $this->notificationsService = $notificationsService;
    }

    public function markAllAsRead(Request $request)
    {
        $userId = $request->user()->id;
        $response = $this->notificationsService->markAllAsRead($userId);

        if($response){
            return new Response(['message' => 'send'], 200);
        }

        return new Response(['message' => 'error'], 400);
    }

    public function markNotification(Request $request)
    {
        $userId = $request->user()->id;
        $notificationId = $request->input('notificationId');

        if (!$notificationId) {
            return new Response(['message' => 'notificationId is required'], 400);
        }

        $response = $this->notificationsService->markNotification($userId, $notificationId);

        if($response){
            return new Response(['message' => 'send'], 200);
        }

        return new Response(['message' => 'error'], 400);
    }

}
