<?php

use App\Domains\Dashboard\Http\Controllers\Backend\DashboardController;
use App\Domains\Settings\Http\Controllers\AppSettingsController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => '',
    'as' => 'admin.',
//    'middleware' => '',
], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    //Route::get('home', [DashboardController::class, 'index'])->name('home');
});

Route::group([
    'prefix' => '',
    'as' => 'admin.',
    'middleware' => 'role:Administrator',
], function () {
    Route::get('clear/{setting}', [AppSettingsController::class, 'clear_settings'])->name('clear-settings');
    Route::get('cache/{setting}', [AppSettingsController::class, 'cache_settings'])->name('cache-settings');

    Route::get('optimize-app', [AppSettingsController::class, 'optimize_app'])->name('optimize-app');
    Route::get('optimize-clear', [AppSettingsController::class, 'optimize_clear'])->name('optimize-clear');


    Route::get('app-settings', [AppSettingsController::class, 'index'])->name('setting.index');

});
