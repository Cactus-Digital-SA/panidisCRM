<?php

namespace App\Domains\Files\Repositories\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fileable extends Model
{
    /**
     * Summary of guarded
     * @var array
     */
    protected $guarded = [];

    /**
     * Summary of file
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }
}
