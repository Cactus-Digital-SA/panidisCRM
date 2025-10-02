<?php

namespace App\Domains\CountryCodes\Repositories\Eloquent;

use App\Domains\CountryCodes\Models\CountryCode;
use App\Domains\CountryCodes\Repositories\Eloquent\Models\CountryCode as EloquentCountryCode;
use App\Domains\CountryCodes\Repositories\CountryCodeRepositoryInterface;
use App\Facades\ObjectSerializer;
use App\Models\CactusEntity;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;

class EloqCountryCodeRepository implements CountryCodeRepositoryInterface
{
    private EloquentCountryCode $model;
    private string $cacheKey = 'countryCodes';

    /**
     * @param EloquentCountryCode $countryCodes
     */
    public function __construct(EloquentCountryCode $countryCodes)
    {
        $this->model = $countryCodes;
    }

    /**
     * @inheritDoc
     */
    public function get(): array
    {
        $cacheKey = $this->cacheKey;
        if (Cache::has($cacheKey)) {
            $countryCodesSerialized = Cache::get($cacheKey);
        }else{
            $countryCodes = $this->model->orderBy('name')->get();
            $countryCodesSerialized = ObjectSerializer::deserialize($countryCodes?->toJson() ?? "{}", 'array<' . CountryCode::class . '>', 'json');

            if($countryCodes){
                $expiresAt = Carbon::now()->endofmonth()->addSecond();
                Cache::put($cacheKey, $countryCodesSerialized, $expiresAt);
            }
        }

        return $countryCodesSerialized;
       }

    /**
     * @inheritDoc
     */
    public function getById(string $id): ?CountryCode
    {
        $countryCode = $this->model->find($id);

        return ObjectSerializer::deserialize($countryCode->toJson() ?? "{}", CountryCode::class, 'json');

    }

    /**
     * @inheritDoc
     */
    public function store(CactusEntity|CountryCode $entity): ?CountryCode
    {
        $countryCode = $this->model::create([
            'code' => $entity->getCode(),
            'name' => $entity->getName(),
        ]);
        Cache::forget($this->cacheKey);
        return ObjectSerializer::deserialize($countryCode->toJson() ?? "{}", CountryCode::class, 'json');
    }

    /**
     * @inheritDoc
     */
    public function update(CactusEntity|CountryCode $entity, string $id): ?CountryCode
    {
        $countryCode = $this->model->find($id);

        if($countryCode) {
            $countryCode->update([
                'code' => $entity->getCode(),
                'name' => $entity->getName(),
            ]);
            Cache::forget($this->cacheKey);
        }

        return ObjectSerializer::deserialize($countryCode->toJson() ?? "{}", CountryCode::class, 'json');
    }

    public function updateErpIdByCountryCode(string $isoCode, string $erpId, ?CountryCode $entity = null): ?CountryCode
    {
        $countryCode = $this->model->where('iso_code', $isoCode)->first();
        if($countryCode){
            $countryCode->update([
                'erp_id' => $erpId,
            ]);
        }else{
            $countryCode = $this->model::updateOrCreate(
                [
                    'erp_id' => $erpId,
                ],
                [
                    'iso_code' => $isoCode,
                    'erp_id' => $erpId,
                    'name' => $entity->getName(),
                ]
            );
        }
        Cache::forget($this->cacheKey);
        return ObjectSerializer::deserialize($countryCode?->toJson() ?? "{}", CountryCode::class, 'json');
    }

    /**
     * @inheritDoc
     */
    public function deleteById(string $id): bool
    {
        $countryCode = $this->model::find($id);

        if ($countryCode) {
            $countryCode->delete();
            return true;
        }

        return false;
    }

    public function dataTableCountryCodes(array $filters = []): JsonResponse
    {
        $countryCodes = $this->model;

        if ($filters['columnName'] && $filters['columnSortOrder']) {
            $countryCodes = $countryCodes->orderBy($filters['columnName'], $filters['columnSortOrder']);
        }

        return DataTables::of($countryCodes)
            ->addColumn('code', function ($countryCode) {
                return $countryCode->code;
            })
            ->addColumn('name', function ($countryCode) {
                return $countryCode->name;
            })
            ->addColumn('actions', function ($countryCode) {
                $deleteUrl = route('admin.country-codes.destroy', [
                    'countryCodeId' => $countryCode->id,
                ]);

                $html = '<div class="btn-group">';

                $html .= '<button type="button" class="btn btn-icon btn-gradient-warning" data-bs-toggle="modal" data-bs-target="#editCountryCodeModal" onclick="editClick(\'' . $countryCode->id . '\')">
                                <i class="ti ti-edit ti-xs"></i>
                            </button>';

                $html .= '<a href="#" class="btn btn-icon btn-gradient-danger"
                           data-bs-toggle="modal" data-bs-target="#deleteModal"
                           onclick="deleteForm(\'' . $deleteUrl . '\')">
                            <i class="ti ti-trash ti-xs"></i>
                       </a>';

                $html .= '</div>';
                return $html;
            })
            ->makeHidden(['created_at', 'updated_at', 'deleted_at'])
            ->rawColumns(['actions'])
            ->toJson();
    }



}
