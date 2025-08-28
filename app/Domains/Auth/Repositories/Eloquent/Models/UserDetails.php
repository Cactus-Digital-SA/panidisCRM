<?php

namespace App\Domains\Auth\Repositories\Eloquent\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone',
        'phone_confirmed',
        'phone_confirmed_at',
        'birthday',
    ];

    protected $casts = [
        'phone_confirmed' => 'boolean',
        'phone_confirmed_at' => 'datetime:Y-m-d H:i:s',
        'birthday'  => 'date:Y-m-d',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }



}
