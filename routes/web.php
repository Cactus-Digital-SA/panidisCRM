<?php

use App\Domains\Auth\Http\Controllers\Fortify\TwoFactorAuthController;
use App\Domains\Auth\Http\Controllers\User\ProfileController;
use App\Domains\Dashboard\Http\Controllers\Frontend\HomeController;
use App\Domains\Erp\Services\ErpService;
use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//Route::get('/syncCountries', [ErpService::class, 'syncCountries'])->name('syncCountries');
//Route::get('/getItems', [ErpService::class, 'getItems'])->name('getItems');
//Route::get('/syncCustomers', [ErpService::class, 'syncCustomers'])->name('syncCustomers');


Route::get('/', [HomeController::class, 'index'])->name('home');

//Route::get('/', function () {
//    return view('welcome');
//});


// locale Route
Route::get('lang/{locale}', [LanguageController::class, 'swap']);

/*
 * Backend Routes
 *
 * These routes can only be accessed by users with type `admin`
 */
Route::group(['prefix' => 'admin', 'as' => '', 'middleware' => 'admin'], function () {
    includeRouteFiles(__DIR__.'/backend/');
});

Route::group(['namespace' => 'Api', 'prefix' => '/api/internal', 'as' => 'api.internal.', 'middleware' => 'auth:web'], function () {
    includeRouteFiles(__DIR__.'/api/internal');
});



//2fa fortify
Route::post('/2fa-confirm', [TwoFactorAuthController::class, 'confirm'])->name('fortify.two-factor.confirm');

Route::group(['middleware' => config('fortify.middleware', ['web'])], function () {
    if (Features::enabled(Features::twoFactorAuthentication())) {
        if (config('fortify.views', true)) {
            Route::get('/recovery-pass', [TwoFactorAuthController::class, 'recoveryPass'])
                ->middleware(['guest:'.config('fortify.guard')])
                ->name('two-factor.recovery_pass');

            Route::post('/send-email', [TwoFactorAuthController::class, 'sendEmail'])
                ->middleware(['guest:'.config('fortify.guard')])
                ->name('two-factor.send_email');
        }
    }
});



// LoggedIn User Profile - Update
Route::group([
    'prefix' => 'profile',
    'as' => 'profile.',
    'middleware' => ['auth'],
], function () {
    Route::get('/', [ProfileController::class, 'show'])->name('show');
    Route::patch('/{user}/delete-photo', [ProfileController::class, 'delete_profile_photo'])->name('delete_profile_photo');

    Route::get('/{userId}/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::patch('/{userId}/', [ProfileController::class, 'update'])->name('update');

    Route::get('/{userId}/password/change', [ProfileController::class, 'editPassword'])
        ->name('change-password');

    Route::patch('/{userId}/password/change', [ProfileController::class, 'updatePassword'])
        ->name('change-password.update');

});
