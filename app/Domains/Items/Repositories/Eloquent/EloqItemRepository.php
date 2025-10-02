<?php

namespace App\Domains\Items\Repositories\Eloquent;

use App\Domains\Items\Models\Item;
use App\Domains\Items\Repositories\Eloquent\Models\Item as EloquentItem;
use App\Domains\Items\Repositories\ItemRepositoryInterface;
use App\Facades\ObjectSerializer;
use App\Models\CactusEntity;
use Illuminate\Http\JsonResponse;

class EloqItemRepository implements ItemRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function get(): array
    {
        // TODO: Implement get() method.
    }

    /**
     * @inheritDoc
     */
    public function getById(string $id): ?Item
    {
        // TODO: Implement getById() method.
    }

    /**
     * @inheritDoc
     */
    public function store(CactusEntity|Item $entity): ?Item
    {
        $item = EloquentItem::create([
            'erp_id' => $entity->getErpId(),
            'name' => $entity->getName(),
            'category' => $entity->getCategory(),
            'price_wholesale' => $entity->getPriceWholesale(),
            'price_retail' => $entity->getPriceRetail(),
            'brand' => $entity->getBrand(),
            'model' => $entity->getModel(),
            'image_path' => $entity->getImagePath(),
        ]);

        return ObjectSerializer::deserialize($item->toJson() ?? "{}", Item::class, 'json');
    }

    public function storeOrUpdate(CactusEntity|Item $entity): ?Item
    {
        $item = EloquentItem::updateOrCreate(
            ['erp_id' => $entity->getErpId()],
            [
                'erp_id' => $entity->getErpId(),
                'name' => $entity->getName(),
                'category' => $entity->getCategory(),
                'price_wholesale' => $entity->getPriceWholesale(),
                'price_retail' => $entity->getPriceRetail(),
                'brand' => $entity->getBrand(),
                'model' => $entity->getModel(),
                'image_path' => $entity->getImagePath(),
            ]
        );

        return ObjectSerializer::deserialize($item->toJson() ?? "{}", Item::class, 'json');
    }

    /**
     * @inheritDoc
     */
    public function update(CactusEntity|Item $entity, string $id): ?Item
    {
        $item = EloquentItem::find($id);

        if($item) {
            $item->update([
                'erp_id' => $entity->getErpId(),
                'name' => $entity->getName(),
                'category' => $entity->getCategory(),
                'price_wholesale' => $entity->getPriceWholesale(),
                'price_retail' => $entity->getPriceRetail(),
                'brand' => $entity->getBrand(),
                'model' => $entity->getModel(),
                'image_path' => $entity->getImagePath(),
            ]);
        }

        return ObjectSerializer::deserialize($item->toJson() ?? "{}", Item::class, 'json');
    }

    /**
     * @inheritDoc
     */
    public function deleteById(string $id): bool
    {
        // TODO: Implement deleteById() method.
    }

    /**
     * @inheritDoc
     */
    public function dataTableItems(array $filters = []): JsonResponse
    {
        // TODO: Implement dataTableItems() method.
    }
}
