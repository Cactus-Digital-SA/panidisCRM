<?php

namespace App\Domains\Visits\Repositories\Eloquent\Models;

use App\Domains\Auth\Repositories\Eloquent\Models\User;
use App\Domains\Companies\Repositories\Eloquent\Models\Company;
use App\Domains\Files\Repositories\Eloquent\Models\File;
use App\Domains\Notes\Repositories\Eloquent\Models\Note;
use App\Domains\Visits\Enums\VisitNextActionSourceEnum;
use App\Domains\Visits\Enums\VisitProductDiscussedSourceEnum;
use App\Domains\Visits\Enums\VisitTypeSourceEnum;
use App\Helpers\Casts\ActiveStatus;
use App\Helpers\Enums\PriorityEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'deadline',
        'priority',
        'owner_id',
        'company_id',
        'visit_date',
        'visit_type',
        'outcome',
        'products_discussed',
        'next_action',
        'next_action_comment'
    ];

    protected $casts = [
        'active_status' => ActiveStatus::class,
        'priority' => PriorityEnum::class,
        'deadline' => 'datetime:Y-m-d',
        'visit_date' => 'datetime:Y-m-d',
        'visit_type' => VisitTypeSourceEnum::class,
        'products_discussed' => VisitProductDiscussedSourceEnum::class,
        'next_action' => VisitNextActionSourceEnum::class,
    ];

    protected $appends = ['active_status'];

    /**
     * @return BelongsToMany
     */
    public function status() : BelongsToMany
    {
        return $this->belongsToMany(VisitStatus::class, 'visits_statuses')->orderByPivot('date','desc')->withPivot('date','sort');
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
     * @return BelongsToMany
     */
    public function contacts() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'visit_contacts')->using(VisitsContact::class);
    }

}
