<?php

namespace App\Domains\Companies\Repositories\Eloquent\Models;

use App\Domains\Auth\Repositories\Eloquent\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserCompany extends Pivot
{
    /**
     * Summary of table
     * @var string
     */
    protected $table = 'users_companies';
    protected $fillable = [
        'user_id',
        'company_id',
    ];

    /**
     * Summary of user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id','user_id');
    }

    /**
     * Summary of company
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'id','company_id');
    }

}
