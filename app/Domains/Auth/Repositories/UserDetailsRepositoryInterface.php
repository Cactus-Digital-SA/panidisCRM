<?php

namespace App\Domains\Auth\Repositories;

use App\Domains\Auth\Models\UserDetails;
use App\Models\CactusEntity;
use App\Repositories\RepositoryInterface;

interface UserDetailsRepositoryInterface extends RepositoryInterface
{
    public function getByUserId(string $userId): ?UserDetails;

    /**
     * @param UserDetails|CactusEntity $entity
     * @param string $userId
     * @return UserDetails|null
     */
    public function createOrUpdateByUserId(UserDetails|CactusEntity $entity, string $userId): ?UserDetails;

    public function store(UserDetails|CactusEntity $entity): ?UserDetails;

    public function updateByUserId(UserDetails|CactusEntity $entity, string $userId): ?UserDetails;

    public function deleteByUserId(string $userId): bool;

}
