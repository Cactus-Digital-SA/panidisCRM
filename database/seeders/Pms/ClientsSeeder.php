<?php

namespace Database\Seeders\Pms;


use App\Domains\Auth\Models\RolesEnum;
use App\Domains\Auth\Repositories\Eloquent\Models\User;
use App\Domains\Clients\Repositories\Eloquent\Models\ClientStatusEnum;
use App\Domains\Companies\Repositories\Eloquent\Models\Company;
use App\Domains\Leads\Repositories\Eloquent\Models\Lead;
use Illuminate\Database\Seeder;

class ClientsSeeder extends Seeder
{
    public function run()
    {
        $company = Company::create([
            'erp_id' => 1,
            'name' => 'Cactus',
            'email' => 'info@cactusweb.gr',
            'phone' => '2311 822 997',
            'type_id' => 11,
            'country_id' => 81,
            'source_id' => 2,

        ]);

        $salesPerson = User::where('role_id', RolesEnum::SALES_SKG->value)->first();
        Lead::create([
            'company_id' => $company->id,
            'sales_person_id' => $salesPerson->id,
        ]);
    }
}
