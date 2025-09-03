<?php

use App\Domains\Tickets\Http\Controllers\TicketsApiController;

Route::group([
    'prefix' => '/tickets',
    'namespace' => 'tickets.',
    'as' => 'tickets.'
], function () {

    Route::post('search', [TicketsApiController::class, 'searchPaginated'])->name('search-paginated');

});
