<?php

use App\Domains\Auth\Http\Controllers\User\UserApiController;
use App\Domains\Contacts\Http\Controllers\ContactApiController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => '/contacts',
    'namespace' => 'contacts.',
    'as' => 'contacts.'
], function () {

    Route::get('{contactId}/get', [ContactApiController::class, 'getContact'])->name('getContact');
    Route::post('names/paginated', [UserApiController::class, 'contactNamesPaginated'])->name('namesPaginated');

});
