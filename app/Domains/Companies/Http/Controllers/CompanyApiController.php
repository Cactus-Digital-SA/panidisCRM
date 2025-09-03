<?php

namespace App\Domains\Companies\Http\Controllers;

use App\Domains\Companies\Services\CompanyService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class CompanyApiController extends Controller
{

    /**
     * @param CompanyService $companyService
     */
    public function __construct(private readonly CompanyService $companyService)
    {}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getCompanyById(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id' => 'required',
        ]);

        $company = $this->companyService->getById($validated['id']);

        return response()->json($company->getValues(false));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function namesPaginated(Request $request){
        $validated = $request->validate([
            'page' => 'required|integer',
            'term' => 'nullable|string',
        ]);

        $page = $validated['page'];
        $resultCount = 25;

        $offset = ($page - 1) * $resultCount;

        /**
         * result['data']
         * result['count']
         */
        $result = $this->companyService->namesPaginated($validated['term'], $offset, $resultCount);


        $subSections = $result['data'];
        $count = $result['count'];


        $endCount = $offset + $resultCount;
        $morePages = $count > $endCount;


        $results = array(
            "results" => $subSections,
            "pagination" => array(
                "more" => $morePages
            )
        );

        return response()->json($results);

    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getContactsByCompanyId(Request $request, int $companyId){
        $validated = $request->validate([
            'page' => 'required|integer',
            'term' => 'nullable|string',
        ]);

        $page = $validated['page'];
        $resultCount = 25;

        $offset = ($page - 1) * $resultCount;

        /**
         * result['data']
         * result['count']
         */
        $result = $this->companyService->getContactsPaginatedByCompanyId($validated['term'], $offset, $resultCount, $companyId);


        $data = $result['data'];
        $count = $result['count'];


        $endCount = $offset + $resultCount;
        $morePages = $count > $endCount;


        $results = array(
            "results" => $data,
            "pagination" => array(
                "more" => $morePages
            )
        );

        return response()->json($results);

    }
}
