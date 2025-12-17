<?php

namespace App\Domains\Widgets\Repositories\Eloquent\Models;

use App\Domains\Auth\Repositories\Eloquent\Models\Role;
use Illuminate\Database\Eloquent\Relations\Pivot;

class RoleWidget extends Pivot
{
    protected $table = 'roles_widgets';

    protected $fillable = [
        'role_id',
        'widget_id',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'position' => 'integer',
    ];

    public function widget()
    {
        return $this->belongsTo(Widget::class, 'widget_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
