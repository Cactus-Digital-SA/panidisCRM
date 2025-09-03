<?php

namespace App\Domains\Tickets\Repositories\Eloquent\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TicketStatus extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'label',
        'sort',
        'visibility',
        'id'
    ];

    protected $table = 'ticket_status';

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
    public function tickets() : BelongsToMany
    {
        return $this->belongsToMany(Ticket::class,'tickets_statuses')->withPivot('date');
    }


}
