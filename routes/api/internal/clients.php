<?php

use App\Domains\Clients\Http\Controllers\ClientApiController;
use App\Domains\Clients\Http\Controllers\ClientDTController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => '/clients',
    'namespace' => 'clients.',
    'as' => 'clients.'
], function () {

    Route::post('names/paginated', [ClientApiController::class, 'namesPaginated'])->name('namesPaginated');
    Route::post('client-by-id/', [ClientApiController::class, 'getClientById'])->name('getClientById');
    Route::post('client-with-companies-by-client-id/', [ClientApiController::class, 'getClientWithCompanyByClientId'])->name('getClientWithCompanyByClientId');

    Route::post('table', [ClientDTController::class, 'dataTableClients'])->name('datatable');
});
