<?php

namespace Database\Seeders;

use Database\Seeders\Pms\PmsSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AuthSeeder::class);
        $this->call(ApiPermissionRoleTableSeeder::class);
        $this->call(PmsSeeder::class);

    }
}
