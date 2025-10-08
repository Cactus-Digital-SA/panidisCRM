<?php

namespace App\Domains\Quotes\Repositories\Eloquent;

use App\Domains\Quotes\Models\Quote;
use App\Domains\Quotes\Repositories\Eloquent\Models\Quote as EloquentQuote;
use App\Domains\Quotes\Repositories\QuoteRepositoryInterface;
use App\Facades\ObjectSerializer;
use App\Helpers\EloquentRelationHelper;
use App\Models\CactusEntity;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;
use function Laravel\Prompts\search;

class EloqQuoteRepository extends EloquentRelationHelper implements QuoteRepositoryInterface
{
    private EloquentQuote $model;

    /**
     *  @param EloquentQuote $quote
     *  @return void
     */
    public function __construct(EloquentQuote $quote)
    {
        $this->model = $quote;
    }

    /**
     * @inheritDoc
     */
    public function get(): array
    {
        $quotes = $this->model->all();
        return ObjectSerializer::deserialize($quotes?->toJson() ?? "{}", 'array<' . Quote::class . '>', 'json');
    }

    /**
     * @inheritDoc
     */
    public function getById(string $id, bool $withRelations = true): ?Quote
    {
        $quote = $this->model;

        if($withRelations) {
            $quote = $quote->with(['items','company','contacts','assignees']);
        }
        $quote = $quote->find($id);

        return ObjectSerializer::deserialize($quote?->toJson() ?? "{}",  Quote::class, 'json');
    }

    /**
     * @inheritDoc
     */
    public function getByUuid(string $uuid, bool $withRelations = true): ?Quote
    {
        $quote = $this->model;

        if($withRelations) {
            $quote = $quote->with(['items.item','company','contacts','assignees']);
        }
        $quote = $quote->where('uuid', $uuid)->first();

        return ObjectSerializer::deserialize($quote?->toJson() ?? "{}",  Quote::class, 'json');
    }

    /**
     * @inheritDoc
     */
    public function store(CactusEntity|Quote $entity): ?Quote
    {
        $quote = $this->model::create([
            'title' => $entity->getTitle(),
            'company_id' => $entity->getCompanyId(),
            'status' => $entity->getStatus(),
            'valid_until' => $entity->getValidUntil(),
            'payment_terms' => $entity->getPaymentTerms(),
            'delivery_terms' => $entity->getDeliveryTerms(),
            'subtotal' => $entity->getSubtotal(),
            'tax_rate' => $entity->getTaxRate(),
            'tax' => $entity->getTax(),
            'total' => $entity->getTotal(),
        ]);

        foreach ($entity->getItems() as $item) {
            $quote->items()->create([
                'item_id' => $item->getItemId(),
                'product_name' => $item->getProductName(),
                'sku' => $item->getSku(),
                'color' => $item->getColor(),
                'unit_type' => $item->getUnitType(),
                'quantity' => $item->getQuantity(),
                'price' => $item->getPrice(),
                'total' => $item->getTotal()
            ]);
        }

        $users = $entity->getContacts();
        $quote->contacts()->sync($users);

        $users = $entity->getAssignees();
        $quote->assignees()->sync($users);
        return ObjectSerializer::deserialize($quote?->toJson() ?? "{}", Quote::class, 'json');
    }

    /**
     * @inheritDoc
     */
    public function update(CactusEntity|Quote $entity, string $id): ?Quote
    {
        $quote = $this->model->findOrFail($id);
        $quote->update([
            'title' => $entity->getTitle(),
            'status' => $entity->getStatus(),
            'company_id' => $entity->getCompanyId(),
            'valid_until' => $entity->getValidUntil(),
            'payment_terms' => $entity->getPaymentTerms(),
            'delivery_terms' => $entity->getDeliveryTerms(),
            'subtotal' => $entity->getSubtotal(),
            'tax_rate' => $entity->getTaxRate(),
            'tax' => $entity->getTax(),
            'total' => $entity->getTotal(),
        ]);

        $quote->items()->delete();

        foreach ($entity->getItems() as $item) {
            $quote->items()->create([
                'item_id' => $item->getItemId(),
                'product_name' => $item->getProductName(),
                'sku' => $item->getSku(),
                'color' => $item->getColor(),
                'unit_type' => $item->getUnitType(),
                'quantity' => $item->getQuantity(),
                'price' => $item->getPrice(),
                'total' => $item->getTotal()
            ]);
        }

        $users = $entity->getContacts();
        $quote->contacts()->sync($users);

        $users = $entity->getAssignees();
        $quote->assignees()->sync($users);
        return ObjectSerializer::deserialize($quote?->toJson() ?? "{}", Quote::class, 'json');
    }

    /**
     * @inheritDoc
     */
    public function updateStatus(CactusEntity|Quote $entity, string $id): ?Quote
    {
        $quote = $this->model->findOrFail($id);
        $quote->update([
            'status' => $entity->getStatus(),
        ]);

        return ObjectSerializer::deserialize($quote?->toJson() ?? "{}", Quote::class, 'json');

    }

    /**
     * @inheritDoc
     */
    public function deleteById(string $id): bool
    {
        return $this->model->destroy($id);
    }

    /**
     * @inheritDoc
     */
    public function dataTableQuotes(array $filters = []): JsonResponse
    {
        $quotes = $this->model->query();

        $quotes = $quotes->with(['company', 'contacts']);

        $quotes = $quotes
            ->when($filters['filterName'], function ($query,$searchTerm) {
                $query->where('quotes.title', 'LIKE', '%'.$searchTerm.'%')
                ->orWhere('quotes.reference_code', 'LIKE', '%'.$searchTerm.'%');
            });

        return DataTables::of($quotes)
            ->editColumn('quoteId', function ($quote) {
                return '# '. $quote->reference_code ?? ' - ';
            })
            ->editColumn('company', function ($quote) {
                return $quote?->company?->name ?? ' - ';
            })
            ->addColumn('totalAmount', function ($quote) {
                return '€ '. number_format($quote?->total ?? 0, 2) ?? ' - ';
            })
            ->editColumn('status', function ($quote) {
                $html = '<span class="'.$quote->status->getLabelClass().'"><i class="'.$quote->status->icon().' me-2">'.'</i>'.$quote->status->label().'</span>';


                return $html;
            })
            ->addColumn('validUntil', function ($quote) {
                return $quote?->valid_until?->format('d-m-Y') ?? ' - ';
            })
            ->addColumn('assignees', function ($quote){
                $html = '';
                foreach($quote->assignees as $assignee){
                       $html .= $assignee->name .'<br>';
                }

                return $html;
            })
            ->addColumn('actions', function ($quote) {
                $deleteUrl = route('admin.quotes.destroy', [
                    'quoteId' => $quote->id,
                ]);

                $html = '<div class="btn-group">';

                $html .= '<a href="' . route('admin.quotes.pdf', $quote->uuid) . '" class="btn btn-icon btn-gradient-info" target="_blank">
                             <i class="ti ti-cloud-download ti-sm"></i>
                        </a>';

                $html .= '<a href="' . route('admin.quotes.show', $quote->id) . '" class="btn btn-icon btn-gradient-warning">
                             <i class="ti ti-eye ti-sm"></i>
                        </a>';

                $html .= '<a href="#" class="btn btn-icon btn-gradient-danger"
                           data-bs-toggle="modal" data-bs-target="#deleteModal"
                           onclick="deleteForm(\'' . $deleteUrl . '\')">
                            <i class="ti ti-trash ti-sm"></i>
                       </a>';

                $html .= '</div>';
                return $html;
            })
            ->makeHidden(['created_at', 'updated_at', 'deleted_at'])
            ->rawColumns(['status','assignees','actions'])
            ->toJson();
    }

    /**
     * @inheritDoc
     */
    public function getTableColumns(): ?array
    {
        return  [
            'id' => ['name' => 'id', 'table' => 'quotes.id', 'searchable' => 'false', 'orderable' => 'false'],
//            'uuid' => ['name'=>'Κωδικός', 'table' => 'quotes.uuid', 'searchable' => 'true', 'orderable' => 'false'],
            'quoteId' => ['name' => 'id', 'table' => 'quotes.reference_code', 'searchable' => 'false', 'orderable' => 'true'],
            'company' => ['name' => 'Εταιρεία', 'table' => 'company.name', 'searchable' => 'true', 'orderable' => 'true'],
            'title' => ['name' => 'Τίτλος', 'table' => 'quotes.title', 'searchable' => 'false', 'orderable' => 'false'],
            'totalAmount' => ['name' => 'Σύνολο', 'table' => 'quotes.total', 'searchable' => 'true', 'orderable' => 'true'],
            'status' => ['name' => 'Status', 'table' => 'quotes.status', 'searchable' => 'true', 'orderable' => 'true'],
            'validUntil' => ['name' => 'Αποδοχή έως', 'table' => 'quotes.valid_until', 'searchable' => 'true', 'orderable' => 'true'],
            'assignees' => ['name' => 'Signed by', 'table' => '', 'searchable' => 'false', 'orderable' => 'false']
        ];
    }

    /**
     * @inheritDoc
     */
    public function getByIdWithMorphsAndRelations(string $modelId, array $morphs = [], array $relations = []): ?Quote
    {
        $quote = $this->model::findOrFail($modelId);

        $quote = $this->modelLoadRelations($quote, $morphs);
        $quote = $this->modelLoadRelations($quote, $relations);

        return ObjectSerializer::deserialize($quote?->toJson() ?? "{}",  Quote::class , 'json');
    }
}
