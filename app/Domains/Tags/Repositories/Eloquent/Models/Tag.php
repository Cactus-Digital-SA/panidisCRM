<?php

namespace App\Domains\Tags\Repositories\Eloquent\Models;

use App\Domains\Leads\Repositories\Eloquent\Models\Lead;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use SoftDeletes;

    /**
     * Summary of table
     * @var string
     */
    protected $table = 'tags';

    /**
     * Summary of fillable
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * Summary of guarded
     * @var array
     */
    protected $guarded = [''];

    /**
     * Summary of taggable
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function taggable(): HasOne
    {
        return $this->hasOne(Taggable::class, 'tag_id');
    }

    /**
     * Summary of types
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function types(): BelongsToMany
    {
        return $this->belongsToMany(TagType::class, 'tag_types_pivot');
    }

    public function leads(): MorphToMany
    {
        return $this->morphedByMany(Lead::class, 'taggable', 'taggables', 'taggable_id', 'tag_id');
    }


}
