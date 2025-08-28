<?php

namespace Database\Seeders\Auth;

use App\Domains\Auth\Models\RolesEnum;
use App\Domains\Auth\Repositories\Eloquent\Models\Role;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

/**
 * Class UserRoleTableSeeder.
 */
class ExtraRolesSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        Role::firstOrCreate(['id' => RolesEnum::FINANCE->value, 'name' => 'Finance']);

        Role::firstOrCreate(['id' => RolesEnum::RND_DIRECTOR->value, 'name' => 'R&D Director']);
        Role::firstOrCreate(['id' => RolesEnum::RND_ENG->value, 'name' => 'R&D Engineer']);

        Role::firstOrCreate(['id' => RolesEnum::SALES_DIRECTOR->value, 'name' => 'Sales Director']);
        Role::firstOrCreate(['id' => RolesEnum::SALES_SKG->value, 'name' => 'Sales SKG']);
        Role::firstOrCreate(['id' => RolesEnum::SALES_ATH->value, 'name' => 'Sales Ath']);

        Role::firstOrCreate(['id' => RolesEnum::LOGISTICS_SKG->value, 'name' => 'Logistics SKG']);
        Role::firstOrCreate(['id' => RolesEnum::LOGISTICS_ATH->value, 'name' => 'Logistics Ath']);
    }
}
