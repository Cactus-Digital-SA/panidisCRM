<?php

namespace App\Domains\Quotes\Repositories\Eloquent\Models;

use App\Domains\Auth\Repositories\Eloquent\Models\User;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuoteContact extends Pivot
{
    use SoftDeletes;

    protected $table = 'quote_contacts';
    protected $fillable = [
        'quote_id',
        'user_id',
    ];

    public function quote(): HasOne
    {
        return $this->hasOne(Quote::class, 'id','quote_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id','user_id');
    }


}
