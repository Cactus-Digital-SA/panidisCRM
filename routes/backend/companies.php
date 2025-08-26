<?php

use App\Domains\Companies\Http\Controllers\CompanyController;
use App\Domains\Contacts\Http\Controllers\ContactController;

Route::group([
    'prefix' => '/',
    'as' => 'admin.',
], function () {

    Route::resource('companies', CompanyController::class)->parameters(['companies' => 'companyId']);

    Route::post('company/contacts/create', [CompanyController::class, 'addNewContact'])->name('companies.contacts.create');

    Route::post('company/{companyId}/contacts/add', [CompanyController::class, 'addContact'])->name('companies.contacts.add');
    Route::post('company/{companyId}/contacts/add-new', [CompanyController::class, 'addNewContact'])->name('companies.contacts.addNew');

    Route::delete('company/{companyId}/contacts/{deleteUserId}/delete', [CompanyController::class, 'deleteContact'])->name('companies.contacts.delete');

});

Route::group([
    'prefix' => '/',
    'as' => 'admin.',
], function () {

    Route::get('contact/{contactId}', [ContactController::class, 'edit'])->name('contacts.edit');
    Route::patch('contact/{contactId}', [ContactController::class, 'update'])->name('contacts.update');

});
