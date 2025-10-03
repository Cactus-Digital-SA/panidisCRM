<?php

namespace App\Domains\Clients\Repositories\Eloquent\Models;

use App\Domains\Auth\Repositories\Eloquent\Models\User;
use App\Domains\Companies\Repositories\Eloquent\Models\Company;
use App\Domains\ExtraData\Repositories\Eloquent\Models\ExtraData;
use App\Domains\Files\Repositories\Eloquent\Models\File;
use App\Domains\Notes\Repositories\Eloquent\Models\Note;
use App\Domains\Projects\Repositories\Eloquent\Models\Project;
use App\Domains\Tags\Repositories\Eloquent\Models\Tag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    protected $table = 'clients';
    protected $fillable = [
        'company_id',
        'sales_person_id'
    ];

    protected $appends = ['tag_ids'];

    protected static function boot()
    {
        parent::boot();

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
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


//    public function getStatusAttribute(): object
//    {
//        return ClientStatusEnum::from($this->status_id)->model();
//    }


    /**
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return BelongsTo
     */
    public function salesPerson(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    /**
     * @return HasMany
     */
    public function projects() : HasMany
    {
        return $this->hasMany(Project::class);
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

    public function extraData(): BelongsToMany
    {
        return $this->belongsToMany(ExtraData::class, 'client_extra_data', 'client_id', 'extra_data_id')
            ->withPivot('value', 'sort','visibility')
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
