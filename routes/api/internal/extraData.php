<?php


use App\Domains\ExtraData\Http\Controllers\ExtraDataDTController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => '/extra-data',
    'namespace' => 'extra-data.',
    'as' => 'extra-data.'
], function () {

    Route::post('table', [ExtraDataDTController::class, 'dataTableExtraData'])->name('datatable');

});
