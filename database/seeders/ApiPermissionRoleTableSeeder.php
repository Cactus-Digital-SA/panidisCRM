<?php
namespace Database\Seeders;

use App\Domains\Auth\Models\RolesEnum;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * Class PermissionRoleTableSeeder.
 */
class ApiPermissionRoleTableSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();

        // Create Roles
        $apirole = Role::firstOrCreate(['id' => RolesEnum::API->value, 'name' => 'Api']);
        $permissions = ['api.view'];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission,
                'description' => 'Πρόσβαση API',
            ]);
        }



        // Assign Permissions to other Roles
        $apirole->givePermissionTo('api.view');

        $this->enableForeignKeys();
    }
}
