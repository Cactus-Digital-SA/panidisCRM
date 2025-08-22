<?php

namespace Database\Seeders;

use App\Domains\Auth\Models\RolesEnum;
use App\Domains\Auth\Repositories\Eloquent\Models\Permission;
use App\Domains\Auth\Repositories\Eloquent\Models\Role;
use App\Domains\Auth\Repositories\Eloquent\Models\User;
use Illuminate\Database\Seeder;

class RolesAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $role = Role::firstOrCreate(['id' => RolesEnum::SuperAdmin->value, 'name' => 'super-admin']);
        $role->syncPermissions(Permission::all());

        // Create Roles
        $role = Role::firstOrCreate(['id' => RolesEnum::Administrator->value, 'name' => 'Administrator',]);
        $role->syncPermissions(Permission::all());


    }
}
