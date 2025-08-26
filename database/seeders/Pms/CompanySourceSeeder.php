<?php

namespace Database\Seeders\Pms;


use App\Domains\CompanySource\Repositories\Eloquent\Models\CompanySource;
use Illuminate\Database\Seeder;

class CompanySourceSeeder extends Seeder
{

    public function run()
    {
        CompanySource::create([
            'name' => 'Exhibition'
        ]);

        CompanySource::create([
            'name' => 'Referal'
        ]);

        CompanySource::create([
            'name' => 'Cold Call'
        ]);

        CompanySource::create([
            'name' => 'Inbound'
        ]);

        CompanySource::create([
            'name' => 'Website'
        ]);

        CompanySource::create([
            'name' => 'E-mail Campaing'
        ]);

    }
}
