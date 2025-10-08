<?php


use App\Domains\Quotes\Http\Controllers\QuoteDTController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => '/quotes',
    'namespace' => 'quotes.',
    'as' => 'quotes.'
], function () {

    Route::post('table', [QuoteDTController::class, 'dataTableQuotes'])->name('datatable');

});
