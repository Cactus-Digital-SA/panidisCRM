<?php

namespace Database\Seeders\Pms;

use App\Domains\Visits\Repositories\Eloquent\Models\VisitStatus;
use App\Helpers\Enums\LabelEnum;
use Illuminate\Database\Seeder;

class VisitStatusSeeder extends Seeder
{
    public function run()
    {
        VisitStatus::updateOrCreate([
            'id' => 1,
        ], [
            'name' => 'Δεν ξεκίνησε',
            'slug' => 'not-started',
            'label' => LabelEnum::PRIMARY,
            'sort' => 1,
            'visibility' => true
        ]);

        VisitStatus::updateOrCreate([
            'id' => 2,
        ], [
            'name' => 'Ανοιχτό',
            'slug' => 'open',
            'label' => LabelEnum::SUCCESS,
            'sort' => 2,
            'visibility' => true
        ]);

        VisitStatus::updateOrCreate([
            'id' => 3,
        ], [
            'name' => 'Υπο εργασία',
            'slug' => 'in-progress',
            'label' => LabelEnum::INFO,
            'sort' => 3,
            'visibility' => true
        ]);

        VisitStatus::updateOrCreate([
            'id' => 4,
        ], [
            'name' => 'Προς έλεγχο',
            'slug' => 'under-review',
            'label' => LabelEnum::INFO,
            'sort' => 4,
            'visibility' => true
        ]);

        VisitStatus::updateOrCreate([
            'id' => 5,
        ], [
            'name' => 'Τελείωσε',
            'slug' => 'finished',
            'label' => LabelEnum::DARK,
            'sort' => 5,
            'visibility' => true
        ]);

        VisitStatus::updateOrCreate([
            'id' => 8,
        ], [
            'name' => 'Ακυρωμένο',
            'slug' => 'cancelled',
            'label' => LabelEnum::DANGER,
            'sort' => 6,
            'visibility' => true
        ]);

    }
}
