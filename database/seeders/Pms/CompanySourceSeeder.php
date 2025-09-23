<?php

namespace Database\Seeders\Pms;


use App\Domains\CompanySource\Repositories\Eloquent\Models\CompanySource;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySourceSeeder extends Seeder
{

    public function run()
    {
        DB::table('company_source')->updateOrInsert(
            ['id' => 1],
            ['name' => 'Exhibition']
        );

        DB::table('company_source')->updateOrInsert(
            ['id' => 2],
            ['name' => 'Referral']
        );

        DB::table('company_source')->updateOrInsert(
            ['id' => 3],
            ['name' => 'Cold Call']
        );

        DB::table('company_source')->updateOrInsert(
            ['id' => 4],
            ['name' => 'Inbound']
        );

        DB::table('company_source')->updateOrInsert(
            ['id' => 5],
            ['name' => 'Website']
        );

        DB::table('company_source')->updateOrInsert(
            ['id' => 6],
            ['name' => 'E-mail Campaign']
        );

    }
}
