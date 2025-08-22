<?php

namespace App\Domains\ExtraData\Repositories\Eloquent\Models;

use App\Domains\ExtraData\Enums\ExtraDataTypesEnum;
use App\Domains\Prospect\Repositories\Eloquent\Models\Prospect;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExtraData extends Model
{
    protected $table = 'extra_data';

    protected $fillable = [
        'name',
        'description',
        'type',
        'options',
        'required',
        'multiple',
    ];

    protected $casts = [
        'type' => ExtraDataTypesEnum::class,
        'required' => 'boolean',
        'multiple' => 'boolean',
    ];

    public function prospects(): BelongsToMany
    {
        return $this->belongsToMany(Prospect::class, 'prospect_extra_data', 'extra_data_id', 'prospect_id');
    }

    public function models(): HasMany
    {
        return $this->hasMany(ExtraDataModel::class, 'extra_data_id');
    }
}
