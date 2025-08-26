<?php

namespace App\Domains\CountryCodes\Repositories\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class CountryCode extends Model
{
    protected $table = 'country_codes';

    protected $fillable = [
        'code',
        'name',
    ];
}
