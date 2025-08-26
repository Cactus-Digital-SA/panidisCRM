<?php

use App\Domains\CompanyTypes\Http\Controllers\CompanyTypeController;

Route::group([
    'prefix' => '/',
    'as' => 'admin.',
], function () {

    Route::resource('companyTypes', CompanyTypeController::class)->parameters(['companyType' => 'companyTypeId']);

});
