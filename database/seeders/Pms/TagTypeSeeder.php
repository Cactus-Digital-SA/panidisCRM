<?php

namespace Database\Seeders\Pms;


use App\Domains\Tags\Enums\TagTypesEnum;
use App\Domains\Tags\Repositories\Eloquent\Models\TagType;
use Illuminate\Database\Seeder;

class TagTypeSeeder extends Seeder
{
    public function run()
    {
        $types = [
            [TagTypesEnum::PRODUCT, 'Product'],
        ];

        foreach ($types as [$enum, $name]) {
            TagType::updateOrCreate(
                ['id' => $enum->value],
                [
                    'name' => $name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
