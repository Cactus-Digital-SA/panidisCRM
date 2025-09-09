<?php

namespace App\Domains\Visits\Repositories\Eloquent\Models;

use App\Domains\Auth\Repositories\Eloquent\Models\User;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;

class VisitsContact extends Pivot
{
    protected $table = 'visit_contacts';
    protected $fillable = [
        'visit_id',
        'user_id',
    ];

    public function visit(): HasOne
    {
        return $this->hasOne(Visit::class, 'id','visit_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id','user_id');
    }


}
