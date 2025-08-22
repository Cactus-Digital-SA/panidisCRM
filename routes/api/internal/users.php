<?php


use App\Domains\Auth\Http\Controllers\User\UserApiController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => '/users',
    'namespace' => 'users.',
    'as' => 'users.'
], function () {

    Route::post('emails/paginated', [UserApiController::class, 'emailsPaginated'])->name('emailsPaginated');
    Route::post('names/paginated', [UserApiController::class, 'namesPaginated'])->name('namesPaginated');
    Route::post('user-by-id/', [UserApiController::class, 'getUserById'])->name('getUserById');

});
