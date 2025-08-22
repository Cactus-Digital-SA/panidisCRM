<?php

namespace App\Helpers;

use App\Models\Enums\EloqMorphEnum;
use Illuminate\Database\Eloquent\Model;

class EloquentRelationHelper
{

    /**
     * @param Model $model
     * @param array $relations
     * @return Model|null
     */
    public function modelLoadRelations(Model $model, array $relations = []): ?Model
    {
        $relations = $this->checkIfRelationsIsEnum($relations);

        // Filter undefined relations
        $validRelations = array_filter($relations, function ($relation) use ($model) {
            $relationParts = explode('.', $relation);

            // Check each part of the relation
            foreach ($relationParts as $part) {
                if (!method_exists($model, $part)) {
                    return false;
                }
                // Move to the related model for the next part of the relation
                $model = $model->$part()->getRelated();
            }

            return true;
        });

        $model->load($validRelations);

        return $model;
    }
    public function getByIdWithRelations(string $modelId, array $relations = []): ?Model
    {
        //$exampleRelations = ['user','user.roles','user.userDetails'];

        // Filter undefined relations
        $validRelations = array_filter($relations, function ($relation) {
            $relationParts = explode('.', $relation);
            $model = $this->model;

            // Check each part of the relation
            foreach ($relationParts as $part) {
                if (!method_exists($model, $part)) {
                    return false;
                }
                // Move to the related model for the next part of the relation
                $model = $model->$part()->getRelated();
            }

            return true;
        });

        $model = $this->model->with($validRelations)->find($modelId);

        return $model;
    }

    /**
     * @param array $enums
     * @return array
     */
    private function checkIfRelationsIsEnum(array $enums) : array
    {
        $enumValues = [];
        foreach ($enums as $enum){
            if(!$enum instanceof EloqMorphEnum){
                $enumValues[] = $enum;
            }else {
                $enumValues[] = $enum->value;
            }
        }
        return $enumValues;
    }

    // Helper function Enums
    private function transformDirectRelation($relation, $relationIdKey, $enumClass): ?object
    {
        $id = $relation->$relationIdKey ?? null;
        return $id ? $enumClass::from($id)->model() : null;
    }

}
