<?php

namespace App\Domains\Notifications;

interface NotificationInterface
{
    public function prepare();
    public function send();
}
