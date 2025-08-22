<?php

namespace App\Providers;

use App\Domains\Auth\Repositories\Eloquent\EloqPermissionRepository;
use App\Domains\Auth\Repositories\Eloquent\EloqRoleRepository;
use App\Domains\Auth\Repositories\Eloquent\EloqUserDetailsRepository;
use App\Domains\Auth\Repositories\Eloquent\EloqUserRepository;
use App\Domains\Auth\Repositories\Eloquent\Models\User;
use App\Domains\Auth\Repositories\PermissionRepositoryInterface;
use App\Domains\Auth\Repositories\RoleRepositoryInterface;
use App\Domains\Auth\Repositories\UserDetailsRepositoryInterface;
use App\Domains\Auth\Repositories\UserRepositoryInterface;
use App\Domains\ExtraData\Repositories\Eloquent\EloqExtraDataRepository;
use App\Domains\ExtraData\Repositories\ExtraDataRepositoryInterface;
use App\Domains\Files\Repositories\Eloquent\EloqFileRepository;
use App\Domains\Files\Repositories\FileRepositoryInterface;
use App\Domains\Notes\Repositories\Eloquent\EloqNoteRepository;
use App\Domains\Notes\Repositories\NoteRepositoryInterface;
use App\Models\ModelMorphEnum;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, EloqUserRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, EloqRoleRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, EloqPermissionRepository::class);
        $this->app->bind(UserDetailsRepositoryInterface::class, EloqUserDetailsRepository::class);
        $this->app->bind(FileRepositoryInterface::class, EloqFileRepository::class);
        $this->app->bind(NoteRepositoryInterface::class, EloqNoteRepository::class);
        $this->app->bind(ExtraDataRepositoryInterface::class, EloqExtraDataRepository::class);

        $this->relations();

    }

    /**
     * Eloquent Models Morphs Relation Classes.
     */
    public function relations(): void
    {
        Relation::morphMap([
            ModelMorphEnum::USER->value => User::class,
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
