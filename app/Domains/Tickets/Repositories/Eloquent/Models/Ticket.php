<?php

namespace App\Domains\Tickets\Repositories\Eloquent\Models;

use App\Domains\Auth\Repositories\Eloquent\Models\User;
use App\Domains\Companies\Repositories\Eloquent\Models\Company;
use App\Domains\Files\Repositories\Eloquent\Models\File;
use App\Domains\Notes\Repositories\Eloquent\Models\Note;
use App\Domains\Projects\Repositories\Eloquent\Models\Project;
use App\Domains\Tickets\Enums\TicketSourceEnum;
use App\Helpers\Casts\ActiveStatus;
use App\Helpers\Casts\SecondsToTime;
use App\Helpers\Enums\PriorityEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'deadline',
        'billable',
        'est_time',
        'public',
        'owner_id',
        'company_id',
        'source',
        'priority',
    ];


    protected $casts = [
        'est_time_array'  => SecondsToTime::class,
        'active_status' => ActiveStatus::class,
        'source' => TicketSourceEnum::class,
        'priority' => PriorityEnum::class,
        'deadline' => 'datetime:Y-m-d',
    ];

    protected $appends = ['active_status','est_time_array','blocking_tickets'];


    /**
     * @return int|null
     */
    public function time() :?int
    {
        return $this->est_time;
    }

    /**
     * @return BelongsToMany
     */
    public function status() : BelongsToMany
    {
        return $this->belongsToMany(TicketStatus::class, 'tickets_statuses')->orderByPivot('date','desc')->withPivot('date','sort');
    }

    /**
     * @return BelongsTo
     */
    public function owner() : BelongsTo
    {
       return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function company() : BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * @return MorphToMany
     */
    public function projects() : MorphToMany
    {
        return $this->morphedByMany(Project::class, 'ticketable', 'ticketables')
            ->withTimestamps();
    }

    /**
     * @return MorphToMany
     */
    public function assignees() : MorphToMany
    {
        return $this->morphToMany(User::class, 'assignment', 'assignments')
            ->withPivot(['assigned_by','sort'])
            ->withTimestamps();
    }

    /**
     * @return MorphToMany
     */
    public function notes() : MorphToMany
    {
        return $this->morphToMany(Note::class,'notable', 'notables')->with('user')
            ->withTimestamps();
    }

    /**
     * @return MorphToMany
     */
    public function files() : MorphToMany
    {
        return $this->morphToMany(File::class,'fileable', 'fileables')
            ->withTimestamps();
    }

    /**
     * Tickets that block this one
     */
    public function blockedBy() : BelongsToMany
    {
        return $this->belongsToMany(
            Ticket::class,
            'ticket_blocks',
            'ticket_id',
            'blocked_by_ticket_id'
        )->withTimestamps();
    }

    /**
     * Tickets that this one blocks
     */
    public function blocks() : BelongsToMany
    {
        return $this->belongsToMany(
            Ticket::class,
            'ticket_blocks',
            'blocked_by_ticket_id',
            'ticket_id'
        )->withTimestamps();
    }

    public function getBlockingTicketsAttribute()
    {
        return $this->blocks()->get();
    }

}
