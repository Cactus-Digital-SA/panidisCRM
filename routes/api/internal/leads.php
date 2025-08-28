<?php


use App\Domains\Auth\Http\Controllers\User\UserApiController;
use App\Domains\Leads\Http\Controllers\LeadDTController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => '/leads',
    'namespace' => 'leads.',
    'as' => 'leads.'
], function () {

    Route::post('table', [LeadDTController::class, 'dataTableLeads'])->name('datatable');

});
