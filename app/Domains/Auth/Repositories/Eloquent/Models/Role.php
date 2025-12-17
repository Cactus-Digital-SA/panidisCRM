<?php

namespace App\Domains\Auth\Repositories\Eloquent\Models;

use App\Domains\Auth\Repositories\Eloquent\Models\Traits\Attribute\RoleAttribute;
use App\Domains\Auth\Repositories\Eloquent\Models\Traits\Method\RoleMethod;
use App\Domains\Auth\Repositories\Eloquent\Models\Traits\Scope\RoleScope;
use App\Domains\Widgets\Repositories\Eloquent\Models\Widget;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Role as SpatieRole;

/**
 * Class Role.
 * @method static where(string $string, string $string1, string $string2)
 * @method static find(string $id)
 */
class Role extends SpatieRole
{
    use RoleAttribute,
        RoleMethod,
        RoleScope;

    /**
     * @var string[]
     */
    protected $with = [
        'permissions',
    ];

    public function widgets(): BelongsToMany
    {
        return $this->belongsToMany(Widget::class, 'roles_widgets')
            ->withTimestamps();
    }
}
