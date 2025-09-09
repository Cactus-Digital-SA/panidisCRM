<?php

namespace App\Domains\Visits\Repositories\Eloquent\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\HasOne;

class VisitsStatusesPivot extends Pivot
{

    protected $table = 'visits_statuses';
    protected $fillable = [
        'visit_id',
        'visit_status_id',
        'date',
        'sort',
    ];

    protected $casts = [
        'date' => 'datetime:Y-m-d\TH:i:s.up',
    ];

    public function visit(): HasOne
    {
        return $this->hasOne(Visit::class, 'id','visit_id');
    }

    public function visitStatus(): HasOne
    {
        return $this->hasOne(VisitStatus::class, 'id','visit_status_id');
    }

}
