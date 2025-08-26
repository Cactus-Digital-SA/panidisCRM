<?php

namespace App\Domains\Companies\Repositories\Eloquent\Models;

use App\Domains\Auth\Repositories\Eloquent\Models\User;
use App\Domains\Clients\Repositories\Eloquent\Models\Client;
use App\Domains\CompanySource\Repositories\Eloquent\Models\CompanySource;
use App\Domains\CompanyTypes\Repositories\Eloquent\Models\CompanyType;
use App\Domains\CountryCodes\Repositories\Eloquent\Models\CountryCode;
use App\Domains\ExtraData\Repositories\Eloquent\Models\ExtraData;
use App\Domains\Files\Repositories\Eloquent\Models\File;
use App\Domains\Leads\Repositories\Eloquent\Models\Lead;
use App\Domains\Notes\Repositories\Eloquent\Models\Note;
use App\Domains\Projects\Repositories\Eloquent\Models\Project;
use App\Domains\Tickets\Repositories\Eloquent\Models\Ticket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use softDeletes;

    /**
     * Summary of table
     * @var string
     */
    protected $table = 'companies';

    /**
     * Summary of fillable
     * @var array
     */
    protected $fillable = [
        'erp_id',
        'name',
        'email',
        'phone',
        'activity',
        'type_id',
        'country_id',
        'city',
        'source_id',
        'website',
        'linkedin',
        'current_balance'
    ];

    public function companyType(): BelongsTo
    {
        return $this->belongsTo(CompanyType::class, 'type_id');
    }

    public function companySource(): BelongsTo
    {
        return $this->belongsTo(CompanySource::class, 'source_id');
    }

    /**
     * @return BelongsToMany
     */
    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_companies')
            ->using(UserCompany::class);
    }

    public function lead(): HasOne
    {
        return $this->hasOne(Lead::class, 'company_id');
    }

    public function client(): HasOne
    {
        return $this->hasOne(Client::class,'company_id');
    }

    public function tickets() : HasMany
    {
        return $this->hasMany(Ticket::class, 'company_id');
    }

    /**
     * @return HasMany
     */
    public function projects() : HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(CountryCode::class, 'country_id');
    }

//    public function extraData(): BelongsToMany
//    {
//        return $this->belongsToMany(ExtraData::class, 'company_extra_data', 'company_id', 'extra_data_id')
//            ->withPivot('value', 'sort','visibility')
//            ->withTimestamps();
//    }

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
    public function notes() : MorphToMany
    {
        return $this->morphToMany(Note::class,'notable', 'notables')->with('user')
            ->withTimestamps();
    }
}
