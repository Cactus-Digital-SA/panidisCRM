<?php

namespace App\Domains\Quotes\Repositories\Eloquent\Models;

use App\Domains\Auth\Repositories\Eloquent\Models\User;
use App\Domains\Companies\Repositories\Eloquent\Models\Company;
use App\Domains\Quotes\Enums\QuoteStatusEnum;
use App\Domains\Quotes\Enums\TaxRatesEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid as PackageUuid;

class Quote extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'reference_code',
        'title',
        'company_id',
        'status',
        'valid_until',
        'payment_terms',
        'delivery_terms',
        'subtotal',
        'total_discount',
        'tax_rate',
        'tax',
        'total',
    ];

    protected $casts = [
        'status' => QuoteStatusEnum::class,
        'valid_until' => 'datetime:Y-m-d',
        'subtotal' => 'float',
        'total_discount' => 'float',
        'tax_rate' => TaxRatesEnum::class,
        'tax' => 'float',
        'total' => 'float',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $model) {
            if(!$model->uuid){
                $model->uuid = PackageUuid::uuid4()->toString();
            }

            $numericId = crc32($model->uuid);
            $numericId = abs($numericId);

            while (Quote::where('uuid', $model->uuid)->orwhere('reference_code', $numericId)->exists()) {
                $model->uuid = PackageUuid::uuid4()->toString();

                $numericId = crc32($model->uuid);
                $numericId = abs($numericId);
            }

            $model->reference_code = $numericId;
        });

        static::deleting(function ($quote) {
            $quote->contacts()->detach();
            $quote->items()->delete();
        });
    }

    public function items()
    {
        return $this->hasMany(QuoteItem::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return MorphToMany
     */
    public function assignees() : MorphToMany
    {
        return $this->morphToMany(User::class, 'assignment', 'assignments')
            ->withPivot(['assigned_by','sort'])
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function contacts() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'quote_contacts')->using(QuoteContact::class);
    }

}
