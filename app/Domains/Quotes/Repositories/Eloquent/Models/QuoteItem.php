<?php

namespace App\Domains\Quotes\Repositories\Eloquent\Models;

use App\Domains\Items\Repositories\Eloquent\Models\Item;
use App\Domains\Quotes\Enums\UnitTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuoteItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'quote_id',
        'item_id',
        'product_name',
        'sku',
        'color',
        'unit_type',
        'price',
        'discount',
        'quantity',
        'total',
    ];

    protected $casts = [
        'unit_type' => UnitTypeEnum::class,
        'price' => 'float',
        'discount' => 'float',
        'quantity' => 'float',
        'total' => 'float',
    ];

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
