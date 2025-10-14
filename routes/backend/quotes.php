<?php


use App\Domains\Quotes\Http\Controllers\QuoteController;

Route::group([
    'prefix' => '/',
    'as' => 'admin.',
], function () {

    Route::get('quote/{slug}/pdf',[QuoteController::class, 'generatePdf'])->name('quotes.pdf');

    Route::resource('quotes', QuoteController::class)->parameters(['quotes' => 'quoteId']);

});
