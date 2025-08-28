<?php

namespace App\Domains\Companies\Http\Controllers;

use App\Domains\Companies\Services\CompanyService;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class CompanyDTController extends Controller
{

    public function __construct(protected CompanyService $companyService)
    {}

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function dataTableCompanies(Request $request): JsonResponse
    {
        $filters = Helpers::filters($request);

        $filters['filterName'] = $request['filterName'];
        $filters['filterTypeId'] = $request['filterTypeId'];
        $filters['filterDoyId'] = $request['filterDoyId'];

        return $this->companyService->dataTableCompanies($filters);
    }


    /**
     * @param Request $request
     * @param string $companyId
     * @return JsonResponse
     */
    public function dataTableCompaniesContacts(Request $request, string $companyId): JsonResponse
    {
        $filters = Helpers::filters($request);
        $filters['filterCompanyId'] = $companyId;

        return $this->companyService->dataTableCompaniesContacts($filters);
    }

}
