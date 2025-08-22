<?php

namespace App\Helpers\Casts;

use Carbon\CarbonInterval;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class SecondsToTime implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $value = $model->time();
        if(!$value){
            return [
                'total_seconds' => '',
                'seconds'=> '',
                'minutes' => '',
                'hours' => '',
                'human' => '-'
            ];
        }

        $totalSeconds = $value;


        $human =  CarbonInterval::seconds($value)->cascade()->forHumans();
        $hours = intdiv($value, 3600);
        $minutes = intdiv($value % 3600, 60);
        $seconds = $value % 60;


        return [
            'total_seconds' => $totalSeconds,
            'seconds'=> $seconds,
            'minutes' => $minutes,
            'hours' => $hours,
            'human' => $human
        ];
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value;
    }
}
