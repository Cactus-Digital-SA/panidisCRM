<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ArraySerializerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('array.serializer', function ($app) {
            $encoders = [new JsonEncoder()];
            $normalizers = [
                new ArrayDenormalizer(),
                new ObjectNormalizer(),
            ];

            return new Serializer($normalizers, $encoders);
        });
    }
}
