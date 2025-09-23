<?php


use App\Domains\Visits\Http\Controllers\VisitDTController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => '/visits',
    'namespace' => 'visits.',
    'as' => 'visits.'
], function () {

    Route::post('follow-up/table', [VisitDTController::class, 'dataTableFollowUp'])->name('datatable.followup');
    Route::post('open/table', [VisitDTController::class, 'dataTableOpen'])->name('datatable.open');
    Route::post('dashboard/table', [VisitDTController::class, 'dataTableDashboard'])->name('datatable.dashboard');


});
