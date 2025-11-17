<?php

namespace App\Domains\ErpSales\Repositories\Eloquent\Models;

use App\Domains\Auth\Repositories\Eloquent\Models\User;
use Illuminate\Database\Eloquent\Model;

class Salesman extends Model
{

    protected $table = 'salesmen';

    protected $fillable = [
        'erp_id',
        'name',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
