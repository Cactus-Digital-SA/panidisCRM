<?php

namespace App\Domains\Items\Repositories\Eloquent;

use App\Domains\Items\Models\Item;
use App\Domains\Items\Repositories\Eloquent\Models\Item as EloquentItem;
use App\Domains\Items\Repositories\ItemRepositoryInterface;
use App\Facades\ObjectSerializer;
use App\Models\CactusEntity;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

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

    public function itemsPaginated(?string $searchTerm, int $offset, int $resultCount): array
    {
        $items = EloquentItem::select(
            'id',
            DB::raw('name AS text'),
            'erp_id as sku',
            'price_wholesale AS price',
        );

        if ($searchTerm != null) {
            $items = $items->where('name', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('erp_id', 'LIKE', '%' . $searchTerm . '%');
        }

        $items = $items->skip($offset)->take($resultCount)->get('id');

        if ($searchTerm == null) {
            $count = EloquentItem::count();
        } else {
            $count = $items->count();
        }

        return array(
            "data" => $items,
            "count" => $count
        );
    }
}
