<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\DisableTwoFactorAuthentication as ReallyDisableTwoFactorAuthentication;
use App\Actions\Fortify\RedirectIfTwoFactorConfirmed;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Domains\Auth\Repositories\Eloquent\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\AttemptToAuthenticate;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnsureLoginIsNotThrottled;
use Laravel\Fortify\Actions\PrepareAuthenticatedSession;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        $this->app->bind(DisableTwoFactorAuthentication::class, function(){
            return new ReallyDisableTwoFactorAuthentication();
        });

        $this->app->singleton(
            \Laravel\Fortify\Contracts\LoginResponse::class,
            \App\Actions\Fortify\CustomLoginResponse::class
        );

        $this->app->singleton(
            \Laravel\Fortify\Contracts\TwoFactorLoginResponse::class,
            \App\Actions\Fortify\CustomTwoFactorLoginResponse::class
        );

        Fortify::loginView(function () {
            return view('frontend.auth.login');
        });

        Fortify::requestPasswordResetLinkView(function () {
            abort(404);
//            return view('frontend.auth.forgot-password');
        });

        Fortify::resetPasswordView(function (Request $request) {
            abort(404);
//            return view('frontend.auth.reset-password', ['request' => $request]);
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(10)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Fortify::authenticateThrough(function(){
            return array_filter([
                config('fortify.limiters.login') ? null : EnsureLoginIsNotThrottled::class,

                Features::enabled(Features::twoFactorAuthentication()) ? RedirectIfTwoFactorConfirmed::class : null,
                AttemptToAuthenticate::class,
                PrepareAuthenticatedSession::class,
            ]);
        });

        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                throw ValidationException::withMessages([
                    Fortify::username() => [trans('auth.failed_email')],
                ]);
            }

            if (!Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'password' => [trans('auth.failed_password')],
                ]);
            }

            if ($user->active == 0) {
                throw ValidationException::withMessages([
                    Fortify::username() => [trans('auth.deactivated')],
                ]);
            }

            if ($user && Hash::check($request->password, $user->password) && $user->active == 1) {
                if ($user->active) {
                    $user->last_login_at = now();
                    $user->last_login_ip = request()->getClientIp();
                    $user->update();

                    return $user;
                }

                Log::info('Ο Χρήστης : '.$user->name.' είναι Απενεργοποιημένος και προσπάθησε να κάνει Login!');
            }

            return null;
        });


        Fortify::confirmPasswordView(function () {
            return view('frontend.auth.fortify.confirm');
        });

        Fortify::twoFactorChallengeView(function () {
            return view('frontend.auth.fortify.two-factor-challenge');
        });
    }
}
