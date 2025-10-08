<?php


use App\Domains\Auth\Http\Controllers\User\UserApiController;
use App\Domains\Companies\Http\Controllers\CompanyApiController;
use App\Domains\Companies\Http\Controllers\CompanyDTController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => '/companies',
    'namespace' => 'companies.',
    'as' => 'companies.'
], function () {
    Route::post('names/paginated', [CompanyApiController::class, 'namesPaginated'])->name('namesPaginated');
    Route::post('{type}/names/paginated', [CompanyApiController::class, 'namesPaginatedByType'])->name('namesPaginatedByType');
    Route::post('{companyId}/getContacts', [CompanyApiController::class, 'getContactsByCompanyId'])->name('getContactsByCompanyId');


    Route::post('table', [CompanyDTController::class, 'dataTableCompanies'])->name('datatable');
    Route::post('company-by-id/', [CompanyApiController::class, 'getCompanyById'])->name('getCompanyById');
});

Route::group([
    'prefix' => '/company/contacts',
    'namespace' => 'company.contacts.',
    'as' => 'company.contacts.'
], function () {

    Route::post('{companyId}/table', [CompanyDTController::class, 'dataTableCompaniesContacts'])->name('datatable');

});
