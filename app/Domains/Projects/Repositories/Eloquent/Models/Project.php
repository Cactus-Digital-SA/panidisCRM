<?php

namespace App\Domains\Projects\Repositories\Eloquent\Models;

use App\Domains\Auth\Repositories\Eloquent\Models\User;
use App\Domains\Clients\Repositories\Eloquent\Models\Client;
use App\Domains\Companies\Repositories\Eloquent\Models\Company;
use App\Domains\Files\Repositories\Eloquent\Models\File;
use App\Domains\Milestones\Repositories\Eloquent\Models\Milestone;
use App\Domains\Notes\Repositories\Eloquent\Models\Note;
use App\Domains\Projects\Enums\ProjectCategoryEnum;
use App\Domains\Projects\Enums\ProjectCategoryStatusEnum;
use App\Domains\Projects\Enums\ProjectPriorityEnum;
use App\Domains\Tags\Repositories\Eloquent\Models\Tag;
use App\Domains\Tickets\Repositories\Eloquent\Models\Ticket;
use App\Helpers\Casts\ActiveStatus;
use App\Helpers\Casts\SecondsToTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'deadline',
        'sales_cost',
        'est_time',
        'type_id',
        'owner_id',
        'created_by',
        'client_id',
        'company_id',
        'google_drive',
        'est_date',
        'priority',
        'category',
        'category_status',
    ];


    protected $casts = [
        'est_time_array'  => SecondsToTime::class,
        'active_status' => ActiveStatus::class,
        'priority' => ProjectPriorityEnum::class,
        'category' => ProjectCategoryEnum::class,
        'category_status' => ProjectCategoryStatusEnum::class
    ];

    protected $appends = ['active_status','est_time_array'];


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
        return $this->belongsToMany(ProjectStatus::class, 'projects_statuses')->orderByPivot('date','desc')->withPivot('date');
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
    public function createdByUser() : BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return BelongsTo
     */
    public function type() : BelongsTo
    {
        return $this->belongsTo(ProjectType::class);
    }

    /**
     * @return BelongsTo
     */
    public function client() : BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return BelongsTo
     */
    public function company() : BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return MorphToMany
     */
    public function assignees() : MorphToMany
    {
        return $this->morphToMany(User::class, 'assignment', 'assignments')
            ->withPivot('sort')
            ->withTimestamps();
    }

    /**
     * @return MorphToMany
     */
    public function milestones() : MorphToMany
    {
        return $this->morphToMany(Milestone::class, 'milestoneable', 'milestoneables')
            ->withPivot(['sort_milestoneable', 'deadline_milestoneable'])
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
     * @return MorphToMany
     */
    public function tickets() : MorphToMany
    {
        return $this->morphToMany(Ticket::class,'ticketable', 'ticketables')
            ->withTimestamps();
    }

    /**
     * @return MorphToMany
     */
    public function tags() : MorphToMany
    {
        return $this->morphToMany(Tag::class,'taggable', 'taggables')
            ->withTimestamps();
    }
}
