<?php

namespace Database\Seeders\Pms;

use Illuminate\Database\Seeder;

class PmsSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CountryCodeSeeder::class);
        $this->call(CompanyTypeSeeder::class);
        $this->call(CompanySourceSeeder::class);
        $this->call(TicketStatusSeeder::class);
        $this->call(VisitStatusSeeder::class);

    }
}
