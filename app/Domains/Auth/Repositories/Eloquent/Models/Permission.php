<?php

namespace App\Domains\Auth\Repositories\Eloquent\Models;

use App\Domains\Auth\Repositories\Eloquent\Models\Traits\Relationship\PermissionRelationship;
use App\Domains\Auth\Repositories\Eloquent\Models\Traits\Scope\PermissionScope;
use Spatie\Permission\Models\Permission as SpatiePermission;

/**
 * Class Permission.
 * @method static find(mixed $permission)
 */
class Permission extends SpatiePermission
{
    use PermissionRelationship,
        PermissionScope;
}
