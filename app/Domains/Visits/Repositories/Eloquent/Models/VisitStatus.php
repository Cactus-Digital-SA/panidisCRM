<?php

namespace App\Domains\Visits\Repositories\Eloquent\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class VisitStatus extends Model
{
    protected $fillable = [
        'id',
        'name',
        'slug',
        'label',
        'sort',
        'visibility',
    ];

    protected $table = 'visit_status';

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
    public function visits() : BelongsToMany
    {
        return $this->belongsToMany(Visit::class,'visits_statuses')->withPivot('date');
    }


}
