<?php

use App\Domains\Tickets\Enums\TicketActionTypesEnum;
use App\Domains\Tickets\Http\Controllers\TicketController;
use App\Domains\Tickets\Http\Controllers\TicketDTController;

Route::group([
    'prefix' => '/',
    'as' => 'admin.',
    'middleware' => 'can:tickets.view'
], function () {
    Route::get('tickets/mine', [TicketController::class, 'mine'])->name('tickets.mine');
    Route::get('tickets/assigned-by-me', [TicketController::class, 'assignedByMe'])->name('tickets.assigned-by-me');

    Route::get('tickets/create/{type}', [TicketController::class, 'create'])
        ->where('type', implode('|', TicketActionTypesEnum::slugs()))
        ->name('tickets.actionType.create');

    Route::get('visits', [TicketController::class, 'visitsIndex'])->name('visits.index');
    Route::post('visits/store', [TicketController::class, 'store'])->name('visits.store');
    Route::patch('visits/update/{ticketId}', [TicketController::class, 'update'])->name('visits.update');


    Route::resource('tickets', TicketController::class);

    Route::post('/tickets/{ticketId}/update-status', [TicketController::class, 'updateStatus'])->name('tickets.update-status');

    Route::delete('/tickets/destroy/{id}', [TicketController::class, 'destroy'])->name('tickets.destroy');
    Route::post('datatable/tickets', [TicketDTController::class, 'dataTableTickets'])->name('datatable.tickets');
    Route::post('datatable/visits', [TicketDTController::class, 'dataTableVisits'])->name('datatable.visits');

});
