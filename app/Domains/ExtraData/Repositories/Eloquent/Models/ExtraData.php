<?php

namespace App\Domains\ExtraData\Repositories\Eloquent\Models;

use App\Domains\ExtraData\Enums\ExtraDataTypesEnum;
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

    public function models(): HasMany
    {
        return $this->hasMany(ExtraDataModel::class, 'extra_data_id');
    }
}
