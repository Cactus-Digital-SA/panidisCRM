<?php

namespace App\Domains\Auth\Repositories\Eloquent\Models;

use App\Domains\Auth\Repositories\Eloquent\Models\Traits\Method\UserMethod;
use App\Domains\Companies\Repositories\Eloquent\Models\Company;
use App\Domains\Companies\Repositories\Eloquent\Models\UserCompany;
use App\Domains\ExtraData\Repositories\Eloquent\Models\ExtraData;
use App\Domains\Files\Repositories\Eloquent\Models\File;
use App\Domains\Notes\Repositories\Eloquent\Models\Note;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @method find(string $userId)
 * @method static create(array $array)
 */
class User extends Authenticatable
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        TwoFactorAuthenticatable,
        HasRoles,
        SoftDeletes,
        UserMethod,
        HasProfilePhoto;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'password_changed_at',
        'active',
        'last_login_at',
        'last_login_ip',
        'to_be_logged_out',
        'profile_photo_path',
        'uuid'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'active' => 'boolean',
        'last_login_at' => 'datetime:Y-m-d\TH:i:s.up',
        'email_verified_at' => 'datetime:Y-m-d\TH:i:s.up',
        'to_be_logged_out' => 'boolean',
    ];

    protected static function newFactory() {
        return UserFactory::new();
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Return true or false if the user can impersonate an other user.
     *
     * @return bool
     */
    public function canImpersonate(): bool
    {
        return $this->can('admin.access.user.impersonate');
    }

    /**
     * Return true or false if the user can be impersonated.
     *
     * @return bool
     */
    public function canBeImpersonated(): bool
    {
        return !$this->isMasterAdmin();
    }

    // need for api sanctum
    protected function getDefaultGuardName(): string
    {
        return 'web';
    }

    /**
     * Retrieves the user details relationship.
     *
     * @return HasOne|null
     */
    public function userDetails(): ?HasOne
    {
        return $this->hasOne(UserDetails::class);
    }

    public function extraData(): BelongsToMany
    {
        return $this->belongsToMany(ExtraData::class, 'user_extra_data', 'user_id', 'extra_data_id')
            ->withPivot('value', 'sort','visibility')
            ->withTimestamps();
    }

    /**
     * Summary of notes
     * @return MorphToMany
     */
    public function notes(): MorphToMany
    {
        return $this->morphToMany(Note::class, 'notable')->withTimestamps();
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
    public function companies() : BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'users_companies')
            ->using(UserCompany::class);
    }
}
