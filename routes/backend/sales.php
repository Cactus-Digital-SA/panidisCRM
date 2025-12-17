<?php

use App\Domains\ErpSales\Http\Controllers\ErpSalesController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => '/sales/',
    'as' => 'admin.sales.',
//    'middleware' => '',
], function () {
    Route::get('sales-widget', [ErpSalesController::class, 'salesWidget'])->name('widget');
    Route::get('sales-target', [ErpSalesController::class, 'salesTarget'])->name('target');

    Route::get('customer-sales-report', [ErpSalesController::class, 'customerSalesReport'])->name('customerSalesReport');
    Route::get('customer-card', [ErpSalesController::class, 'customerCard'])->name('customerCard');
    Route::get('customer-revenue', [ErpSalesController::class, 'customerRevenue'])->name('customerRevenue');
});
