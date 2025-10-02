<?php

use App\Domains\Leads\Http\Controllers\LeadController;
use App\Domains\Leads\Http\Controllers\LeadDTController;

Route::group([
    'prefix' => '/',
    'as' => 'admin.',
], function () {
//    Route::get('leads/lost', [LeadController::class, 'lost'])->name('leads.lost');
//    Route::get('leads/converted',  [LeadController::class, 'converted'])->name('leads.converted');
    Route::get('leads/{leadId}/convert', [LeadController::class, 'convertLead'])->name('leads.convert');

    Route::resource('leads', LeadController::class)->parameters(['leads' => 'leadId']);

});
