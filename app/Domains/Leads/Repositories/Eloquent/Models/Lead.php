<?php

namespace App\Domains\Leads\Repositories\Eloquent\Models;

use App\Domains\Auth\Repositories\Eloquent\Models\User;
use App\Domains\Companies\Repositories\Eloquent\Models\Company;
use App\Domains\ExtraData\Repositories\Eloquent\Models\ExtraData;
use App\Domains\Files\Repositories\Eloquent\Models\File;
use App\Domains\Notes\Repositories\Eloquent\Models\Note;
use App\Domains\Tags\Repositories\Eloquent\Models\Tag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use SoftDeletes;

    /**
     * Summary of table
     * @var string
     */
    protected $table = 'leads';

    /**
     * Summary of fillable
     * @var array
     */
    protected $fillable = [
        'company_id',
        'sales_person_id',
    ];

    /**
     * Summary of casts
     * @var array
     */
    protected $casts = [

    ];

    protected $appends = ['tag_ids'];


    protected static function boot()
    {
        parent::boot();

        static::created(function ($lead) {

        });

        static::updating(function ($lead) {

        });

        static::deleting(function ($lead) {
            $lead->tags()->detach();

            if ($lead->company && $lead->company->leads()->where('id', '!=', $lead->id)->doesntExist() &&
                $lead->company->clients()->doesntExist() )
            {
                $lead->company->delete();
            }
        });
    }

    /**
     * Summary of company
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * @return BelongsTo
     */
    public function salesPerson(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return MorphToMany
     */
    public function notes() : MorphToMany
    {
        return $this->morphToMany(Note::class,'notable', 'notables')->with('user')
            ->withTimestamps();
    }

    public function extraData(): BelongsToMany
    {
        return $this->belongsToMany(ExtraData::class, 'lead_extra_data', 'lead_id', 'extra_data_id')
            ->withPivot('value', 'sort','visibility')
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
    public function tags() : BelongsToMany
    {
        return $this->morphToMany(Tag::class, 'taggable', 'taggables', 'taggable_id', 'tag_id');
    }

    public function getTagIdsAttribute(): array
    {
        return $this->tags->pluck('id')->toArray();
    }
}
