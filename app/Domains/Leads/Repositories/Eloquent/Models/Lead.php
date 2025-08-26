<?php

namespace App\Domains\Leads\Repositories\Eloquent\Models;

use App\Domains\Auth\Repositories\Eloquent\Models\User;
use App\Domains\Companies\Repositories\Eloquent\Models\Company;
use App\Domains\ExtraData\Repositories\Eloquent\Models\ExtraData;
use App\Domains\Files\Repositories\Eloquent\Models\File;
use App\Domains\LeadSections\Repositories\Eloquent\Models\Section;
use App\Domains\LeadSubSections\Repositories\Eloquent\Models\SubSection;
use App\Domains\Notes\Repositories\Eloquent\Models\Note;
use App\Domains\Tags\Repositories\Eloquent\Models\Tag;
use App\Helpers\Casts\SecondsToTime;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Lead extends Model
{
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

    protected static function boot()
    {
        parent::boot();

        static::created(function ($lead) {

        });

        static::updating(function ($lead) {

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

//    public function tags()
//    {
//        return Tag::whereHas('taggables', function ($query) {
//            $query->whereHasMorph('taggable', [Section::class, SubSection::class], function ($q) {
//                $q->whereHas('leadSectionData', function ($subQuery) {
//                    $subQuery->where('lead_id', $this->id);
//                });
//            });
//        })->get();
//    }

    /**
     * @return MorphToMany
     */
    public function files() : MorphToMany
    {
        return $this->morphToMany(File::class,'fileable', 'fileables')
            ->withTimestamps();
    }

}
