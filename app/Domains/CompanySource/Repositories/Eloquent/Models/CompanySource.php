<?php

namespace App\Domains\CompanySource\Repositories\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanySource extends Model
{
    use SoftDeletes;
    protected $table = 'company_source';
    protected $fillable = [
        'name',
    ];

}
