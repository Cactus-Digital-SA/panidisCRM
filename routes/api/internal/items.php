<?php


use App\Domains\Auth\Http\Controllers\User\UserApiController;
use App\Domains\Items\Http\Controllers\ItemsApiController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => '/items',
    'namespace' => 'items.',
    'as' => 'items.'
], function () {

    Route::post('paginated', [ItemsApiController::class, 'itemsPaginated'])->name('itemsPaginated');

});
