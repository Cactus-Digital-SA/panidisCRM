<?php

namespace App\Domains\Notes\Repositories\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notable extends Model
{
    /**
     * Summary of guarded
     * @var array
     */
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d\TH:i:s.up',
    ];

    /**
     * Summary of note
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function note(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

}
