<?php

namespace Database\Seeders\Auth;

use App\Domains\Auth\Repositories\Eloquent\Models\Permission;
use App\Domains\Auth\Repositories\Eloquent\Models\Role;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

/**
 * Class PermissionRoleTableSeeder.
 */
class CustomPermissionRoleSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();

        // Function to handle firstOrCreate for permissions
        function updateOrCreatePermissions($parentData, $childrenData): void
        {
            $parent = Permission::updateOrCreate([
                'id' => $parentData['id'],
            ], $parentData);

            $childrenPermissions = [];
            foreach ($childrenData as $child) {
                $childrenPermissions[] = Permission::updateOrCreate([
                    'id' => $child['id'],
                ], $child);
            }

            $parent->children()->saveMany($childrenPermissions);
        }

        // Settings permissions
        updateOrCreatePermissions(
            ['id' => 30, 'name' => 'settings', 'description' => 'Διαχείριση Ρυθμίσεων'],
            [
                ['id' => 31, 'name' => 'settings.view', 'description' => 'Προβολή ρυθμίσεων διαχειριστή'],
                ['id' => 32, 'name' => 'settings.create', 'description' => 'Δημιουργία ρύθμισης διαχειριστή', 'sort' => 2],
                ['id' => 33, 'name' => 'settings.update', 'description' => 'Επεξεργασία ρύθμισης διαχειριστή', 'sort' => 3],
                ['id' => 34, 'name' => 'settings.delete', 'description' => 'Διαγραφή ρύθμισης διαχειριστή', 'sort' => 4],
            ]
        );

        // Notes permissions
        updateOrCreatePermissions(
            ['id' => 35, 'name' => 'notes', 'description' => 'Διαχείριση Σημειώσεων'],
            [
                ['id' => 36, 'name' => 'notes.view', 'description' => 'Προβολή Σημειώσεων'],
                ['id' => 37, 'name' => 'notes.create', 'description' => 'Δημιουργία Σημειώσεων', 'sort' => 2],
                ['id' => 38, 'name' => 'notes.update', 'description' => 'Επεξεργασία Σημειώσεων', 'sort' => 3],
                ['id' => 39, 'name' => 'notes.delete', 'description' => 'Διαγραφή Σημειώσεων', 'sort' => 4],
            ]
        );

        updateOrCreatePermissions(
            ['id' => 45, 'name' => 'files', 'description' => 'Διαχείριση Αρχείων'],
            [
                ['id' => 46, 'name' => 'file.create', 'description' => 'Δημιουργία Αρχείων', 'sort' => 2],
                ['id' => 47, 'name' => 'file.download', 'description' => 'Επεξεργασία Αρχείων', 'sort' => 3],
                ['id' => 48, 'name' => 'file.preview', 'description' => 'Προβολή Αρχείων', 'sort' => 4],
                ['id' => 49, 'name' => 'file.delete', 'description' => 'Διαγραφή Αρχείων', 'sort' => 5],
            ]
        );

        updateOrCreatePermissions(
            ['id' => 50, 'name' => 'tickets', 'description' => 'Διαχείριση Tickets'],
            [
                [ 'id' => 51, 'name' => 'tickets.view', 'description' => 'Προβολή Ticket'],
                [ 'id' => 52, 'name' => 'tickets.create', 'description' => 'Δημιουργία Tickets', 'sort' => 2],
                [ 'id' => 53, 'name' => 'tickets.update', 'description' => 'Επεξεργασία Tickets', 'sort' => 3],
                [ 'id' => 54, 'name' => 'tickets.delete', 'description' => 'Διαγραφή Tickets', 'sort' => 4],
            ]
        );

        updateOrCreatePermissions(
            ['id' => 70, 'name' => 'leads', 'description' => 'Διαχείριση Leads'],
            [
                [ 'id' => 71, 'name' => 'leads.view', 'description' => 'Προβολή Leads'],
                [ 'id' => 72, 'name' => 'leads.create', 'description' => 'Δημιουργία Lead', 'sort' => 2],
                [ 'id' => 73, 'name' => 'leads.update', 'description' => 'Επεξεργασία Lead', 'sort' => 3],
                [ 'id' => 74, 'name' => 'leads.delete', 'description' => 'Διαγραφή Lead', 'sort' => 4],
                [ 'id' => 75, 'name' => 'leads.create.select.company', 'description' => 'Επιλογή εταιρείας κατά την δημιουργία', 'sort' => 5],
            ]
        );

        updateOrCreatePermissions(
            ['id' => 80, 'name' => 'clients', 'description' => 'Διαχείριση Clients'],
            [
                [ 'id' => 81, 'name' => 'clients.view', 'description' => 'Προβολή Clients'],
                [ 'id' => 82, 'name' => 'clients.create', 'description' => 'Δημιουργία Client', 'sort' => 2],
                [ 'id' => 83, 'name' => 'clients.update', 'description' => 'Επεξεργασία Client', 'sort' => 3],
                [ 'id' => 84, 'name' => 'clients.delete', 'description' => 'Διαγραφή Client', 'sort' => 4],
                [ 'id' => 85, 'name' => 'clients.create.select.company', 'description' => 'Επιλογή εταιρείας κατά την δημιουργία', 'sort' => 5],
            ]
        );

        updateOrCreatePermissions(
            ['id' => 90, 'name' => 'visits', 'description' => 'Διαχείριση Visits'],
            [
                [ 'id' => 91, 'name' => 'visits.view', 'description' => 'Προβολή Visits'],
                [ 'id' => 92, 'name' => 'visits.create', 'description' => 'Δημιουργία Visit', 'sort' => 2],
                [ 'id' => 93, 'name' => 'visits.update', 'description' => 'Επεξεργασία Visit', 'sort' => 3],
                [ 'id' => 94, 'name' => 'visits.delete', 'description' => 'Διαγραφή Visit', 'sort' => 4],
            ]
        );

        updateOrCreatePermissions(
            ['id' => 100, 'name' => 'quotes', 'description' => 'Διαχείριση Quotes'],
            [
                [ 'id' => 101, 'name' => 'quotes.view', 'description' => 'Προβολή Quotes'],
                [ 'id' => 102, 'name' => 'quotes.create', 'description' => 'Δημιουργία Quote', 'sort' => 2],
                [ 'id' => 103, 'name' => 'quotes.update', 'description' => 'Επεξεργασία Quote', 'sort' => 3],
                [ 'id' => 104, 'name' => 'quotes.delete', 'description' => 'Διαγραφή Quote', 'sort' => 4],
            ]
        );


        // Assign Permissions to other Roles
        $this->enableForeignKeys();

        $role = Role::findByName('super-admin');
        $role->syncPermissions(\Spatie\Permission\Models\Permission::all());

        $role=Role::findByName('Administrator','web');
        $role->syncPermissions(\Spatie\Permission\Models\Permission::all());
    }
}
