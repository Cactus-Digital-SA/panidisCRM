<?php

namespace App\Domains\Tickets\Repositories\Eloquent\Models;

use App\Domains\Auth\Repositories\Eloquent\Models\User;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TicketContact extends Pivot
{
    protected $table = 'ticket_contacts';
    protected $fillable = [
        'ticket_id',
        'user_id',
    ];

    public function ticket(): HasOne
    {
        return $this->hasOne(Ticket::class, 'id','ticket_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id','user_id');
    }


}
