<?php

use App\Domains\Files\Http\Controllers\FileController;

Route::group([
    'prefix' => '/',
    'as' => '',
], function () {

    Route::post('file/{model}/{id}',[FileController::class,'store'])->name('file.store');
    Route::get('file/preview',[FileController::class,'previewFile'])->name('file.preview');
    Route::post('file/download',[FileController::class,'downloadFile'])->name('file.download');
    Route::delete('file/destroy',[FileController::class,'destroy'])->name('file.destroy');

});
