<?php

namespace Database\Seeders\Auth;

use App\Domains\Auth\Models\RolesEnum;
use App\Domains\Auth\Repositories\Eloquent\Models\Role;
use App\Domains\Auth\Repositories\Eloquent\Models\User;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

/**
 * Class UserRoleTableSeeder.
 */
class UserRoleSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();

        $adminRole = Role::findById(RolesEnum::Administrator->value);
        User::find(1)->assignRole($adminRole);

        $superAdminRole = Role::findById(RolesEnum::SuperAdmin->value);
        User::find(2)->assignRole($superAdminRole);

        $this->enableForeignKeys();
    }
}
