<?php

namespace Database\Seeders\Pms;


use App\Domains\CompanyTypes\Repositories\Eloquent\Models\CompanyType;
use Illuminate\Database\Seeder;

class CompanyTypeSeeder extends Seeder
{
    public function run()
    {
        CompanyType::create([
            'name' => 'Extrusions',
        ]);

        CompanyType::create([
            'name' => 'Extrusions with Architectural Systems',
        ]);

        CompanyType::create([
            'name' => 'Aluminium Fabricators',
        ]);

        CompanyType::create([
            'name' => 'Aluminium Fabricator with their own Architectural Systems',
        ]);

        CompanyType::create([
            'name' => 'Aluminium Wholesalers',
        ]);

        CompanyType::create([
            'name' => 'Glass Wholesalers',
        ]);

        CompanyType::create([
            'name' => 'Glass Installers',
        ]);

        CompanyType::create([
            'name' => 'Doors Fabricators',
        ]);

        CompanyType::create([
            'name' => 'Architects',
        ]);

        CompanyType::create([
            'name' => 'Construction Companies',
        ]);

        CompanyType::create([
            'name' => 'Others',
        ]);
    }
}
