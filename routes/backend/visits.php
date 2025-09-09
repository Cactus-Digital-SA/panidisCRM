<?php

use App\Domains\Visits\Http\Controllers\VisitController;
use App\Domains\Visits\Http\Controllers\VisitDTController;

Route::group([
    'prefix' => '/',
    'as' => 'admin.',
], function () {
    Route::resource('visits', VisitController::class);

    Route::post('/visits/{visitsId}/update-status', [VisitController::class, 'updateStatus'])->name('visits.update-status');

    Route::delete('/visits/destroy/{id}', [VisitController::class, 'destroy'])->name('visits.destroy');
    Route::post('datatable/visits', [VisitDTController::class, 'dataTableVisits'])->name('datatable.visits');

});
