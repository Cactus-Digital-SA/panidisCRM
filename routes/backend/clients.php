<?php


use App\Domains\Clients\Http\Controllers\ClientController;

Route::group([
    'prefix' => '/',
    'as' => 'admin.',
], function () {

    Route::resource('clients', ClientController::class)->parameters(['clients' => 'clientId']);

});
