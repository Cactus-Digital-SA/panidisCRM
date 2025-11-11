<?php

use App\Domains\Projects\Http\Controllers\ProjectApiController;

Route::group([
    'prefix' => '/projects',
    'namespace' => 'projects.',
    'as' => 'projects.'
], function () {


    Route::get('category/{categoryId}/options', [ProjectApiController::class, 'getCategoryOptions'])->name('get-category-options');
});
