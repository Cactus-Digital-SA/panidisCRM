<?php

namespace App\Models;

use JsonException;

abstract class CactusEntity implements CactusEntityInterface
{
    protected function mapRelationToArray(array $array): array
    {
        return array_map(/**
         * @throws JsonException
         */ static function (CactusEntity $entity) {
            return $entity->convertToJSON();
        }, $array);
    }

    /**
     * @throws JsonException
     */
    public function convertToJSON(): string|bool
    {
        return json_encode($this->getValues(), JSON_THROW_ON_ERROR);
    }

    abstract public function getValues(bool $withRelations = true): array;
}
