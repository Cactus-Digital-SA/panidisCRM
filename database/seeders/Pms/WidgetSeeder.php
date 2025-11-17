<?php

namespace Database\Seeders\Pms;

use App\Domains\Widgets\Repositories\Eloquent\Models\Widget;
use Illuminate\Database\Seeder;

class WidgetSeeder extends Seeder
{
    public function run()
    {
        $widgets = [
            [
                'name' => 'sales_targets',
                'label' => 'Στόχοι Πωλήσεων',
                'description' => 'Μηνιαίοι και ετήσιοι στόχοι πωλήσεων ανά χρήστη ή τομέα.',
            ],
            [
                'name' => 'sales_analysis',
                'label' => 'Ανάλυση Πωλήσεων',
                'description' => 'Ανάλυση πωλήσεων ανά κατηγορία ειδών και τομέα.',
            ],
        ];

        foreach ($widgets as $widget) {
            Widget::firstOrCreate(
                ['name' => $widget['name']],
                [
                    'label' => $widget['label'],
                    'description' => $widget['description'],
                ]
            );
        }
    }

}
