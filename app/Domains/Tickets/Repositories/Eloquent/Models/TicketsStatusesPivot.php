<?php

namespace App\Domains\Tickets\Repositories\Eloquent\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TicketsStatusesPivot extends Pivot
{

    protected $table = 'tickets_statuses';
    protected $fillable = [
        'ticket_id',
        'ticket_status_id',
        'date',
        'sort',
    ];

    protected $casts = [
        'date' => 'datetime:Y-m-d\TH:i:s.up',
    ];

    public function ticket(): HasOne
    {
        return $this->hasOne(Ticket::class, 'id','ticket_id');
    }

    public function ticketStatus(): HasOne
    {
        return $this->hasOne(TicketStatus::class, 'id','ticket_status_id');
    }

}
