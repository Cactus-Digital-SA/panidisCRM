<?php

namespace App\Domains\Auth\Services;

use App\Domains\Auth\Models\UserDetails;
use App\Domains\Auth\Repositories\UserDetailsRepositoryInterface;

class UserDetailsService
{
    private UserDetailsRepositoryInterface $repository;

    /**
     * @param UserDetailsRepositoryInterface $repository
     */
    public function __construct(UserDetailsRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    /**
     * @param string $userId
     * @return UserDetails
     */
    public function getByUserId(string $userId): UserDetails
    {
        return $this->repository->getByUserId($userId);
    }

    /**
     * @param UserDetails $entity
     * @param string $userId
     * @return UserDetails
     */
    public function createOrUpdateByUserId(UserDetails $entity, string $userId): UserDetails
    {
        return $this->repository->createOrUpdateByUserId($entity, $userId);
    }

    /**
     * @param UserDetails $entity
     * @return UserDetails
     */
    public function store(UserDetails $entity): UserDetails
    {
        return $this->repository->store($entity);
    }

    /**
     * @param UserDetails $entity
     * @param string $userId
     * @return UserDetails
     */
    public function updateByUserId(UserDetails $entity, string $userId): UserDetails
    {
        return $this->repository->updateByUserId($entity, $userId);
    }

    /**
     * @param string $id
     * @return bool
     */
    public function deleteById(string $id): bool
    {
        return $this->repository->deleteById($id);
    }

    /**
     * @param string $userId
     * @return bool
     */
    public function deleteByUserId(string $userId): bool
    {
        return $this->repository->deleteByUserId($userId);
    }

}
