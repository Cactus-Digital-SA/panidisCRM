<?php

namespace Database\Seeders;

use App\Domains\Auth\Models\RolesEnum;
use App\Domains\Auth\Repositories\Eloquent\Models\Role;
use Database\Seeders\Auth\CustomPermissionRoleSeeder;
use Database\Seeders\Auth\ExtraRolesSeeder;
use Database\Seeders\Auth\PermissionRoleSeeder;
use Database\Seeders\Auth\UserRoleSeeder;
use Database\Seeders\Auth\UserSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\PermissionRegistrar;

/**
 * Class AuthTableSeeder.
 */
class AuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Reset cached roles and permissions
        Artisan::call('cache:clear');
        resolve(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->call(UserSeeder::class);
        $this->call(RolesAndPermissionSeeder::class);

        $this->call(PermissionRoleSeeder::class);
        $this->call(CustomPermissionRoleSeeder::class);
        $this->call(UserRoleSeeder::class);

        $role = Role::findById(RolesEnum::SuperAdmin->value);
        $role->syncPermissions(\Spatie\Permission\Models\Permission::all());

        $role=Role::findById(RolesEnum::Administrator->value);
        $role->syncPermissions(\Spatie\Permission\Models\Permission::all());

        $this->call(ExtraRolesSeeder::class);

    }
}
