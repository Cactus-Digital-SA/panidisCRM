<?php

namespace App\Domains\CompanyTypes\Repositories\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyType extends Model
{
    use SoftDeletes;
    protected $table = 'company_types';
    protected $fillable = [
        'name',
    ];

}
