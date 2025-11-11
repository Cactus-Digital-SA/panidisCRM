<?php

use App\Domains\Projects\Http\Controllers\ProjectController;
use App\Domains\Projects\Http\Controllers\ProjectDTController;

Route::group([
    'prefix' => '/',
    'as' => 'admin.',
    'middleware' => 'can:projects.view'
], function () {

    //Route::resource('projects', ProjectController::class);

    Route::controller(ProjectController::class)
        ->prefix('projects/{type}')
        ->name('projects.')
        ->group(function () {
        Route::get('/cancelled',  'indexCancelled')->name('indexCancelled');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/', 'index')->name('index');
        Route::get('/mine', 'mine')->name('mine');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/update/{id}', 'edit')->name('edit');
        Route::patch('/update/{id}', 'update')->name('update');

        Route::post('/project/assign/ticket', 'assignTicket')->name('assign.ticket');
    });

    Route::delete('/projects/destroy/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::post('datatable/projects', [ProjectDTController::class, 'dataTableProjects'])->name('datatable.projects');
});
