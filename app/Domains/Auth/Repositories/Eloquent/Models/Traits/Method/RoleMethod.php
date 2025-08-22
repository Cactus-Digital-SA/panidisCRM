<?php

namespace App\Domains\Auth\Repositories\Eloquent\Models\Traits\Method;

use Illuminate\Support\Collection;

/**
 * Trait RoleMethod.
 */
trait RoleMethod
{
    /**
     * @return mixed
     */
    public function isAdmin(): bool
    {
//        || $this->name === 'Administrator'
        return $this->name === 'super-admin' ;
    }

    /**
     * @return Collection
     */
    public function getPermissionDescriptions(): Collection
    {
        return $this->permissions->pluck('description');
    }
}
