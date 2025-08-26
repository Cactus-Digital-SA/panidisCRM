<?php

namespace App\Domains\CountryCodes\Http\Controllers;

use App\Domains\CountryCodes\Services\CountryCodeService;
use App\Helpers\DataTablesHelper;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CountryCodeDTController extends Controller
{
    /**
     * @var CountryCodeService
     */
    protected CountryCodeService $countryCodeService;

    public function __construct(CountryCodeService $countryCodeService)
    {
        $this->countryCodeService = $countryCodeService;
    }

    public function getCountryCodes(Request $request): JsonResponse
    {
        $countryCodes = $this->countryCodeService->get();

        $countryCodesCollection = collect($countryCodes);

        $formattedCountryCodes = $countryCodesCollection->map(function($countryCode) {
            return [
                'id' => $countryCode->getId(),
                'code' => $countryCode->getCode(),
                'name' => $countryCode->getName(),
            ];
        });

        return response()->json($formattedCountryCodes);
    }

    public function getCountryCode(Request $request, string $countryCodeId): JsonResponse
    {
        $countryCode = $this->countryCodeService->getById($countryCodeId);

        return response()->json($countryCode?->getValues() ?? null);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function dataTableCountryCodes(Request $request): JsonResponse
    {
        $filters = DataTablesHelper::filters($request);

        return $this->countryCodeService->dataTableCountryCodes($filters);
    }


}
