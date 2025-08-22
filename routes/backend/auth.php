<?php


//Users
use App\Domains\Auth\Http\Controllers\DataTable\DataTableController;
use App\Domains\Auth\Http\Controllers\Role\RoleController;
use App\Domains\Auth\Http\Controllers\User\UserController;

Route::group([
    'as' => 'admin.',
], function () {
//Users
    Route::group([
        'prefix' => 'users',
        'as' => 'users.',
        //    'middleware' => 'permission:crud roles',
    ], function () {
        Route::get('/', [UserController::class, 'index'])->name('index')->middleware('role_or_permission:admin.access.user.list');
        Route::post('/', [UserController::class, 'store'])->name('store')->middleware('role_or_permission:admin.access.user.create');
        Route::get('/create', [UserController::class, 'create'])->name('create')->middleware('role_or_permission:admin.access.user.create');

        Route::delete('/{userId}', [UserController::class, 'delete'])->name('delete')->middleware('role_or_permission:admin.access.user.delete');

        Route::get('/{userId}/edit', [UserController::class, 'edit'])->name('edit');
        Route::patch('/{userId}/', [UserController::class, 'update'])->name('update');

        Route::get('/{userId}/password/change', [UserController::class, 'editPassword'])
            ->name('change-password');

        Route::patch('/{userId}/password/change', [UserController::class, 'updatePassword'])
            ->name('change-password.update');

        Route::get('/deleted', [UserController::class, 'deleted'])->name('deleted')->middleware('role_or_permission:admin.access.user');
        Route::get('/deactivated', [UserController::class, 'deactivated'])->name('deactivated')->middleware('role_or_permission:admin.access.user');
        Route::post('/restore', [UserController::class, 'restore'])->name('restore')->middleware('role_or_permission:admin.access.user');
        Route::get('/{userId}', [UserController::class, 'show'])->name('show')->middleware('role_or_permission:admin.access.user');

        Route::patch('/{userId}/details', [UserController::class, 'updateUserDetails'])->name('details');

        Route::post('/{userId}/notes/store', [UserController::class, 'storeNote'])->name('notes.store');
        Route::patch('/{userId}/notes/{noteId}/update', [UserController::class, 'updateNote'])->name('notes.update');
        Route::delete('/{userId}/notes/{noteId}/delete', [UserController::class, 'deleteNote'])->name('notes.delete');


        Route::post('users/{model}/{id}',[UserController::class,'sync'])->name('sync.morph');

    });

    Route::patch('user/{userId}/mark/{status}', [UserController::class, 'updateActive'])->name('users.mark')
        ->where(['status' => '[0,1]'])
        ->middleware('role_or_permission:admin.access.user.edit');

    //Roles
    Route::group([
        'prefix' => 'roles',
        'as' => 'roles.',
        'middleware' => 'permission:crud roles',
    ], function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/create', [RoleController::class, 'create'])->name('create');
        Route::get('/{roleId}', [RoleController::class, 'show'])->name('show');
        Route::get('/{roleId}/edit', [RoleController::class, 'edit'])->name('edit');
        Route::patch('/{roleId}', [RoleController::class, 'update'])->name('update');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        Route::delete('/{roleId}', [RoleController::class, 'delete'])->name('delete');
    });

//    //Permissions
//    Route::group([
//        'prefix' => 'permissions',
//        'as' => 'permissions.',
//        'middleware' => 'permission:edit permissions',
//    ], function () {
//        Route::get('/', [PermissionController::class, 'index'])->name('index');
//        Route::get('/by-user', [PermissionController::class, 'by_user'])->name('by_user');
//        Route::post('/by-user', [PermissionController::class, 'store_by_user'])->name('store.by_user');
//        Route::post('', [PermissionController::class, 'store'])->name('store');
//    });
//
//
//    //Imports
//    Route::controller(ImportController::class)->prefix('import')->name('import.')->group(function () {
//        Route::post('/users', 'users')->name('users')->middleware('permission:create users');
//    });

    //Datatables
    Route::controller(DataTableController::class)->prefix('datatable')->name('datatable.')->group(function () {
        Route::get('/roles', 'roles')->name('roles')->middleware('permission:crud roles');
        Route::get('/logs', 'logs')->name('logs')->middleware('permission:view logs');
        Route::post('/users', 'users')->name('users');
    });


});
