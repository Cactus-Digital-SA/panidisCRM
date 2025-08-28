<?php

namespace App\Domains\Auth\Repositories\Eloquent;

use App\Domains\Auth\Models\UserDetails;
use App\Domains\Auth\Repositories\Eloquent\Models\User;
use App\Domains\Auth\Repositories\Eloquent\Models\UserDetails as EloquentUserDetails;
use App\Domains\Auth\Repositories\UserDetailsRepositoryInterface;
use App\Facades\ObjectSerializer;
use App\Models\CactusEntity;
use Carbon\Carbon;

class EloqUserDetailsRepository implements UserDetailsRepositoryInterface
{
    private EloquentUserDetails $model;

    public function __construct(EloquentUserDetails $userDetails)
    {
        $this->model = $userDetails;
    }

    public function getById(string $id): ?UserDetails
    {
        $userDetails = $this->model->with('user')->findOrFail($id);

        return ObjectSerializer::deserialize($userDetails?->toJson() ?? "{}", UserDetails::class, 'json');
    }


    public function getByUserId(string $userId): ?UserDetails
    {
        $userDetails = $this->model->with('user')->where('user_id',$userId)->first();

        return ObjectSerializer::deserialize($userDetails?->toJson() ?? "{}", UserDetails::class, 'json');
    }

    /**
     * @param UserDetails|CactusEntity $entity
     * @param string $userId
     * @return User|null
     */
    public function createOrUpdateByUserId(UserDetails|CactusEntity $entity, string $userId): ?UserDetails
    {
        $userDetails = $this->model->where('user_id',$userId)->first();

        if($userDetails) {
            $userDetails = $this->updateByUserId($entity, $userId);
        }else{
            $userDetails = $this->store($entity);
        }

        return $userDetails;
    }

    public function store(UserDetails|CactusEntity $entity): ?UserDetails
    {
        $userDetails = $this->model::create([
            'user_id' => $entity->getUserId(),
            'first_name' => $entity->getFirstName(),
            'last_name' => $entity->getLastName(),
            'phone' => $entity->getPhone(),
            'phone_confirmed' => $entity->getPhoneConfirmed(),
            'phone_confirmed_at' => $entity->getPhoneConfirmedAt()->format('Y-m-d H:i:s'),
            'birthday' => $entity->getBirthday()->format('Y-m-d'),
        ]);

        return ObjectSerializer::deserialize($userDetails?->toJson() ?? "{}", UserDetails::class, 'json');
    }

    public function update(UserDetails|CactusEntity $entity, string $id): ?UserDetails
    {

    }

    public function updateByUserId(UserDetails|CactusEntity $entity, string $userId): ?UserDetails
    {
        $userDetails = $this->model->where('user_id',$userId)->firstOrFail();

        if($userDetails) {
            if(!$userDetails->phone_confirmed_at && $entity->getPhoneConfirmed()){
                $entity->setPhoneConfirmedAt(Carbon::now());
            }

            $userDetails->update([
                'first_name' => $entity->getFirstName(),
                'last_name' => $entity->getLastName(),
                'phone' => $entity->getPhone(),
                'phone_confirmed' => $entity->getPhoneConfirmed(),
                'phone_confirmed_at' => $entity->getPhoneConfirmedAt()->format('Y-m-d H:i:s') ?? $userDetails->phone_confirmed_at,
                'birthday' => $entity->getBirthday()->format('Y-m-d'),
            ]);

        }

        return ObjectSerializer::deserialize($userDetails->toJson() ?? "{}", UserDetails::class, 'json');
    }

    public function deleteById(string $id): bool
    {
        $userDetails = $this->model->find($id);

        if($userDetails) {
            $userDetails->delete();
            return true;
        }

        return false;
    }

    public function deleteByUserId(string $userId): bool
    {
        $userDetails = $this->model->where('user_id',$userId)->first();

        if($userDetails) {
            $userDetails->delete();
            return true;
        }

        return false;
    }
}
