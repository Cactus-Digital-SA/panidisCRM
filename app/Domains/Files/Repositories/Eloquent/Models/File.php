<?php

namespace App\Domains\Files\Repositories\Eloquent\Models;

use App\Domains\Auth\Repositories\Eloquent\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class File extends Model
{
    /**
     * Summary of table
     * @var string
     */
    protected $table = 'files';

    /**
     * Summary of fillable
     * @var array
     */
    protected $fillable = [
        'name',
        'path',
        'file_name',
        'mime_type',
        'extension',
        'size',
        'uploaded_by',
    ];

    /**
     * Summary of guarded
     * @var array
     */
    protected $guarded = [''];

    /**
     * Summary of fileable
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function fileable(): HasOne
    {
        return $this->hasOne(Fileable::class,'file_id');
    }

    /**
     * Summary of user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

}
