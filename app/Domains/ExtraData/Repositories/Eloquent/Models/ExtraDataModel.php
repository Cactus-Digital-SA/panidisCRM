<?php

namespace App\Domains\ExtraData\Repositories\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExtraDataModel extends Model
{
    protected $table = 'extra_data_models';

    protected $fillable = [
        'model',
        'extra_data_id',
    ];

    public function extraData(): BelongsTo
    {
        return $this->belongsTo(ExtraData::class, 'extra_data_id');
    }

}
