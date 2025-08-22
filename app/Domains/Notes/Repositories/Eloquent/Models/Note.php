<?php

namespace App\Domains\Notes\Repositories\Eloquent\Models;

use App\Domains\Auth\Repositories\Eloquent\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Note extends Model
{
    /**
     * Summary of table
     * @var string
     */
    protected $table = 'notes';

    /**
     * Summary of fillable
     * @var array
     */
    protected $fillable = [
        'user_id',
        'content',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d\TH:i:s.up',
    ];

    /**
     * Summary of guarded
     * @var array
     */
    protected $guarded = [];


    /**
     * Summary of notable
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function notable(): BelongsTo
    {
        return $this->belongsTo(Notable::class,'note_id');
    }

    /**
     * Summary of user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
