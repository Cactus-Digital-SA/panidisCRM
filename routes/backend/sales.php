<?php

use App\Domains\Dashboard\Http\Controllers\Backend\DashboardController;
use App\Domains\Settings\Http\Controllers\AppSettingsController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => '/',
    'as' => 'admin.sales.',
//    'middleware' => '',
], function () {
    Route::get('sales-widget', [DashboardController::class, 'salesWidget'])->name('widget');
    Route::get('customer-sales-report', [DashboardController::class, 'customerSalesReport'])->name('customerSalesReport');
    Route::get('customer-card', [DashboardController::class, 'customerCard'])->name('customerCard');
    Route::get('customer-revenue', [DashboardController::class, 'customerRevenue'])->name('customerRevenue');
});
