<?php

namespace App\Domains\ExtraData\Repositories\Eloquent;

use App\Domains\ExtraData\Enums\ExtraDataModelsEnum;
use App\Domains\ExtraData\Models\ExtraData;
use App\Domains\ExtraData\Repositories\Eloquent\Models\ExtraDataModel;
use App\Domains\ExtraData\Repositories\ExtraDataRepositoryInterface;
use App\Facades\ObjectSerializer;
use App\Models\CactusEntity;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class EloqExtraDataRepository implements ExtraDataRepositoryInterface
{
    public function __construct(protected readonly Models\ExtraData $model)
    {
    }

    public function get(): array
    {
        $extraData = $this->model::with('models')->get();

        return ObjectSerializer::deserialize($extraData?->toJson() ?? "{}",  "array<". ExtraData::class . ">" , 'json');
    }

    public function getByModel(ExtraDataModelsEnum $model): array
    {
        $extraData = $this->model::whereHas('models', function ($query) use ($model) { $query->where('model', $model); })->with('models')->get();

        return ObjectSerializer::deserialize($extraData?->toJson() ?? "{}",  "array<". ExtraData::class . ">" , 'json');
    }

    public function getById(string $id, bool $withRelations = true): ?ExtraData
    {
        $extraData = $this->model::find($id);

        if($withRelations){
            $extraData?->load('prospects');
        }

        return ObjectSerializer::deserialize($extraData?->toJson() ?? "{}",  ExtraData::class , 'json');
    }

    public function store(CactusEntity|ExtraData $entity): ?ExtraData
    {
        $extraData = $this->model::create([
            'name' => $entity->getName(),
            'description' => $entity->getDescription(),
            'type' => $entity->getType(),
            'options' => $entity->getOptions(),
            'required' => $entity->getRequired(),
            'multiple' => $entity->isMultiple(),
        ]);

        return ObjectSerializer::deserialize($extraData?->toJson() ?? "{}",  ExtraData::class , 'json');
    }

    public function update(CactusEntity|ExtraData $entity, string $id): ?ExtraData
    {
        $extraData = $this->model::find($id);
        $extraData->update([
            'name' => $entity->getName(),
            'description' => $entity->getDescription(),
            'type' => $entity->getType(),
            'options' => $entity->getOptions(),
            'required' => $entity->getRequired(),
            'multiple' => $entity->isMultiple(),
        ]);

        return ObjectSerializer::deserialize($extraData?->toJson() ?? "{}",  ExtraData::class , 'json');
    }

    public function deleteById(string $id): bool
    {
        return $this->model::find($id)->delete();
    }

    /**
     * @inheritDoc
     */
    public function dataTableExtraData(array $filters = []): JsonResponse
    {
        $extraData = $this->model->query();

        if ($filters['columnName'] && $filters['columnSortOrder']) {
            $extraData = $extraData->orderBy($filters['columnName'], $filters['columnSortOrder']);
        }

        return DataTables::of($extraData)
            ->editColumn('name', function ($data) {
                return $data?->name;
            })
            ->editColumn('required', function ($data) {
                return $data->required ? '<i class="ti ti-check ti-xs text-success"></i>' : '<i class="ti ti-x ti-xs text-danger"></i>';
            })
            ->addColumn('actions', function ($data) {
                $deleteUrl = route('admin.extraData.destroy', [
                    'extraDataId' => $data->id,
                ]);

                $html = '<div class="btn-group">';

                $html .= '<a href="' . route('admin.extraData.edit', $data->id) . '" class="btn btn-icon btn-gradient-warning">
                             <i class="ti ti-edit ti-xs"></i>
                        </a>';

                $html .= '<a href="#" class="btn btn-icon btn-gradient-danger"
                           data-bs-toggle="modal" data-bs-target="#deleteModal"
                           onclick="deleteForm(\'' . $deleteUrl . '\')">
                            <i class="ti ti-trash ti-xs"></i>
                       </a>';

                $html .= '</div>';
                return $html;
            })
            ->makeHidden(['created_at', 'updated_at', 'deleted_at'])
            ->rawColumns(['required','actions'])
            ->toJson();
    }

    /**
     * @inheritDoc
     */
    public function getTableColumns(): ?array
    {
        return  [
            'id'=> ['name' => 'id', 'table' => 'extra_data.id', 'searchable' => 'false', 'orderable' => 'true'],


            'name' => ['name' => 'Name', 'table' => 'extra_data.name', 'searchable' => 'false', 'orderable' => 'true'],
            'description' => ['name' => 'Description', 'table' => 'extra_data.description', 'searchable' => 'false', 'orderable' => 'false'],
            'type' => ['name' => 'Type', 'table' => 'extra_data.type', 'searchable' => 'false', 'orderable' => 'false'],
            'required' => ['name' => 'Required', 'table' => 'extra_data.required', 'searchable' => 'false', 'orderable' => 'false'],

        ];

    }

    public function assignExtraDataToModel(array $extraData = [])
    {
        ExtraDataModel::query()->truncate();
        foreach ($extraData as $model => $selectedData) {
            foreach($selectedData as $extraDataId){
                ExtraDataModel::create([
                    'model' => $model,
                    'extra_data_id' => $extraDataId
                ]);
            }
        }
    }
}
