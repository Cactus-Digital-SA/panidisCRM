<?php

namespace Database\Seeders\Auth;

use App\Domains\Auth\Models\RolesEnum;
use App\Domains\Auth\Repositories\Eloquent\Models\Permission;
use App\Domains\Auth\Repositories\Eloquent\Models\Role;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

/**
 * Class PermissionRoleTableSeeder.
 */
class PermissionRoleSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();

        // Grouped permissions
        // Users category
        $users = Permission::create([
            'id' => 1,
            'name' => 'admin.access.user',
            'description' => 'Όλα Τα Δικαιώματα Χρηστών',
        ]);

        $users->children()->saveMany([
            new Permission([
                'id' => 2,
                'name' => 'admin.access.user.list',
                'description' => 'Προβολή Χρηστών',
            ]),
            new Permission([
                'id' => 3,
                'name' => 'admin.access.user.deactivate',
                'description' => 'Απενεργοποίηση Χρηστών',
                'sort' => 2,
            ]),
            new Permission([
                'id' => 4,
                'name' => 'admin.access.user.reactivate',
                'description' => 'Επανενεργοποίηση Χρηστών',
                'sort' => 3,
            ]),
            new Permission([
                'id' => 5,
                'name' => 'admin.access.user.clear-session',
                'description' => 'Εκκαθάριση Συνδέσεων Χρήστη',
                'sort' => 4,
            ]),
            new Permission([
                'id' => 6,
                'name' => 'admin.access.user.impersonate',
                'description' => 'Σύνδεση ως Χρήστης',
                'sort' => 5,
            ]),
            new Permission([
                'id' => 7,
                'name' => 'admin.access.user.change-password',
                'description' => 'Αλλαγή Κωδικών Χρήστη',
                'sort' => 6,
            ]),
            new Permission([
                'id' => 8,
                'name' => 'admin.access.user.create',
                'description' => 'Δημιουργία Χρήστη',
                'sort' => 7,
            ]),
            new Permission([
                'id' => 9,
                'name' => 'admin.access.user.edit',
                'description' => 'Επεξεργασία Χρήστη',
                'sort' => 8,
            ]),
            new Permission([
                'id' => 10,
                'name' => 'admin.access.user.show',
                'description' => 'Προβολή Χρήστη',
                'sort' => 9,
            ]),
            new Permission([
                'id' => 11,
                'name' => 'admin.access.user.delete',
                'description' => 'Διαγραφή Χρήστη',
                'sort' => 10,
            ]),
        ]);

        Permission::create([
            'id' => 20,
            'name' => 'edit permissions' ,
            'description' => 'Επεξεργασία Δικαιωμάτων'
        ]);
        Permission::create([
            'id' => 21,
            'name' => 'crud roles' ,
            'description' => 'Crud Ρόλων'
        ]);
        Permission::create([
            'id' => 22,
            'name' => 'view logs' ,
            'description' => 'Προβολή Logs'
        ]);


        $this->enableForeignKeys();

        $role = Role::findById(RolesEnum::SuperAdmin->value);
        $role->syncPermissions(\Spatie\Permission\Models\Permission::all());

        $role=Role::findById(RolesEnum::Administrator->value);
        $role->syncPermissions(\Spatie\Permission\Models\Permission::all());
    }
}
