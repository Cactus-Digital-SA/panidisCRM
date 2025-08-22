<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use JMS\Serializer\SerializerBuilder;

class ObjectSerializerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('json.serializer', function () {
            return SerializerBuilder::create()
                ->enableEnumSupport()
                ->build();
        });
    }
}
