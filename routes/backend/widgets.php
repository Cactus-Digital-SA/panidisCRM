<?php


use App\Domains\Widgets\Http\Controllers\WidgetController;

Route::group([
    'prefix' => '/',
    'as' => 'admin.',
], function () {

    Route::get('widgets/assign', [WidgetController::class, 'assignWidgetsToRoleIndex'])->name('widgets.indexUsers');
    Route::post('widgets/assign', [WidgetController::class, 'assignWidgetToRole'])->name('widgets.assign.store');

    Route::resource('widgets', WidgetController::class)->parameters(['widget' => 'widgetId']);

});
