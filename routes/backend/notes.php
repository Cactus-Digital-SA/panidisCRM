<?php

use App\Domains\Notes\Http\Controllers\NotesController;

Route::group([
    'prefix' => '/',
    'as' => '',
], function () {

    Route::resource('notes', NotesController::class)->except('store')->parameters(['note' => 'noteId']);
    Route::post('notes/{model}/{id}', [NotesController::class,'store'])->name('notes.store');

});
