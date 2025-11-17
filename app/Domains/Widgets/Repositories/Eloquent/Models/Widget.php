<?php

namespace App\Domains\Widgets\Repositories\Eloquent\Models;

use App\Domains\Auth\Repositories\Eloquent\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Widget extends Model
{
    protected $fillable = [
        'name',
        'label',
        'description',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'roles_widgets')
            ->withTimestamps();
    }

    public function scopeEnabledForRole($query, int $roleId)
    {
        return $query->whereHas('roles', function ($q) use ($roleId) {
            $q->where('roles.id', $roleId);
        });
    }
}
