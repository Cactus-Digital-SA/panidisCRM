<?php

namespace App\Domains\Notifications\Repositories\Eloquent;

use App\Domains\Auth\Repositories\Eloquent\Models\User as EloquentUser;
use App\Domains\Notifications\Repositories\NotificationRepositoryInterface;
use App\Models\CactusEntity;

class EloqNotificationRepository implements NotificationRepositoryInterface
{
    private EloquentUser $model;

    public function __construct(EloquentUser $user)
    {
        $this->model = $user;
    }

    public function markAllAsReadByUserId(string $userId): bool
    {
        $user = $this->model->find($userId);
        if(!$user){
            return false;
        }

        $user->unreadNotifications->markAsRead();
        return true;
    }

    public function markNotification(string $userId, string $notificationId): bool
    {
        $user = $this->model->find($userId);
        if(!$user){
            return false;
        }

        $notification = $user->notifications()->find($notificationId);
        if($notification){
            if($notification->read_at){
                $notification->update(['read_at' => null]);
            }else{
                $notification->update(['read_at' => now()]);
            }

            return true;
        }

        return false;
    }

    public function getById(string $id): ?CactusEntity
    {
        // TODO: Implement getById() method.
    }

    public function store(CactusEntity $entity): ?CactusEntity
    {
        // TODO: Implement store() method.
    }

    public function update(CactusEntity $entity, string $id): ?CactusEntity
    {
        // TODO: Implement update() method.
    }

    public function deleteById(string $id): bool
    {
        // TODO: Implement deleteById() method.
    }
}
