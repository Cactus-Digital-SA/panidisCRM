<?php

use App\Domains\Tickets\Http\Controllers\TicketController;
use App\Domains\Tickets\Http\Controllers\TicketDTController;
use App\Helpers\Enums\ActionTypesEnum;

Route::group([
    'prefix' => '/',
    'as' => 'admin.',
    'middleware' => 'can:tickets.view'
], function () {
    Route::get('tickets/mine', [TicketController::class, 'mine'])->name('tickets.mine');
    Route::get('tickets/assigned-by-me', [TicketController::class, 'assignedByMe'])->name('tickets.assigned-by-me');

    Route::resource('tickets', TicketController::class)->except('destroy');

    Route::post('/tickets/{ticketId}/update-status', [TicketController::class, 'updateStatus'])->name('tickets.update-status');

    Route::delete('/tickets/destroy/{id}', [TicketController::class, 'destroy'])->name('tickets.destroy');
    Route::post('datatable/tickets', [TicketDTController::class, 'dataTableTickets'])->name('datatable.tickets');

});
