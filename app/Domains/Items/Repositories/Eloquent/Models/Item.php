<?php

namespace App\Domains\Items\Repositories\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';

    protected $fillable = [
        'erp_id',
        'name',
        'category',
        'price_wholesale',
        'price_retail',
        'brand',
        'model',
        'image_path',
    ];

    protected $casts = [
        'price_wholesale' => 'decimal:2',
        'price_retail' => 'decimal:2',
    ];

}
