<?php

namespace App\Domains\Tags\Repositories\Eloquent;

use App\Domains\Tags\Enums\TagTypesEnum;
use App\Domains\Tags\Models\Tag;
use App\Domains\Tags\Repositories\Eloquent\Models\Tag as EloquentTag;
use App\Domains\Tags\Repositories\TagRepositoryInterface;
use App\Facades\ObjectSerializer;
use App\Models\CactusEntity;

class EloqTagRepository implements TagRepositoryInterface
{
    private EloquentTag $model;

    public function __construct(EloquentTag $tag)
    {
        $this->model = $tag;
    }

    /**
     * @return Tag[]
     */
    public function get(): array
    {
        $tags = $this->model->get();

        return ObjectSerializer::deserialize($tags->toJson() ?? "{}", 'array<' . Tag::class . '>', 'json');
    }

    public function getByType(string $tagTypeId): array
    {
        $tags = $this->model
            ->whereHas('types', function ($q) use ($tagTypeId) {
                $q->where('tag_types.id', $tagTypeId);
            })
            ->get();

        return ObjectSerializer::deserialize($tags->toJson() ?? "{}", 'array<' . Tag::class . '>', 'json');
    }

    public function getById(string $id): ?Tag
    {
        $tag = $this->model->find($id);

        return ObjectSerializer::deserialize($tag->toJson() ?? "{}", Tag::class , 'json');
    }

    public function store(CactusEntity|Tag $entity): ?Tag
    {
        $tag = $this->model::create([
            'name' => $entity->getName(),
        ]);

        return ObjectSerializer::deserialize($tag?->toJson() ?? "{}", Tag::class, 'json');
    }

    public function update(CactusEntity|Tag $entity, string $id): ?Tag
    {
        $tag = $this->model->find($id);
        if($tag){
            $tag->update([
                'name' => $entity->getName(),
            ]);
        }

        return ObjectSerializer::deserialize($tag?->toJson() ?? "{}", Tag::class, 'json');
    }

    public function deleteById(string $id): bool
    {
        $tag = $this->model->find($id);

        if ($tag) {
            $tag->delete();
            return true;
        }

        return false;
    }

}
