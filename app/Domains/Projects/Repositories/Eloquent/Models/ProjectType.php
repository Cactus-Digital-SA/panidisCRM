<?php

namespace App\Domains\Projects\Repositories\Eloquent\Models;

use App\Helpers\Enums\LabelEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectType extends Model
{
    protected $fillable = [
        'name',
        'label',
        'visibility',
        'icon',
        'slug'
    ];

    protected $casts = [
        'label' => LabelEnum::class
    ];


    protected $table = 'project_types';

    /**
     * Scope a query to only include popular users.
     */
    public function scopeVisible(Builder $query): void
    {
        $query->where('visibility',true);
    }

    /**
     * @return HasMany
     */
    public function projects() : HasMany
    {
        return $this->hasMany(Project::class);
    }
}
