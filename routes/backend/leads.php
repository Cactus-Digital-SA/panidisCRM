<?php

use App\Domains\Leads\Http\Controllers\LeadController;
use App\Domains\Leads\Http\Controllers\LeadDTController;

Route::group([
    'prefix' => '/',
    'as' => 'admin.',
], function () {
    Route::get('leads/lost', [LeadController::class, 'lost'])->name('leads.lost');
    Route::get('leads/converted',  [LeadController::class, 'converted'])->name('leads.converted');
    Route::get('leads/{leadStatus}/index',  [LeadController::class, 'indexPerStatus'])->name('leads.indexPerStatus');

    Route::resource('leads', LeadController::class)->parameters(['leads' => 'leadId']);

});
