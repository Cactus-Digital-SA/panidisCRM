<?php

namespace App\Domains\Tags\Repositories\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TagType extends Model
{
    use SoftDeletes;

    protected $table = 'tag_types';
    protected $fillable = [
        'name'
    ];

    /**
     * @return BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'tag_types_pivot');
    }

}
