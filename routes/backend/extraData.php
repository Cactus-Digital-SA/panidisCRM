<?php

use App\Domains\ExtraData\Http\Controllers\ExtraDataController;

Route::group([
    'prefix' => '/',
    'as' => 'admin.',
], function () {

    Route::get('extraData/assign', [ExtraDataController::class, 'assignExtraDataToModelIndex'])->name('extraData.assign');
    Route::post('extraData/assign', [ExtraDataController::class, 'assignExtraDataToModelStore'])->name('extraData.assign.store');

    Route::resource('extraData', ExtraDataController::class)->parameters(['extraData' => 'extraDataId']);

});
