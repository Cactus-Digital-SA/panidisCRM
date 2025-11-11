<?php

namespace App\Domains\Projects\Repositories\Eloquent\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProjectStatus extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'label',
        'sort',
        'visibility'
    ];

    protected $table = 'project_status';

    /**
     * Scope a query to only include popular users.
     */
    public function scopeVisible(Builder $query): void
    {
        $query->where('visibility',true);
    }

    /**
     * @return BelongsToMany
     */
    public function projects() : BelongsToMany
    {
        return $this->belongsToMany(Project::class,'projects_statuses')->withPivot('date');
    }


}
