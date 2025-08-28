<?php

namespace App\Domains\Leads\Http\Controllers;

use App\Domains\Leads\Services\LeadService;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class LeadDTController extends Controller
{
    /**
     * @var LeadService
     */
    public function __construct(
        protected LeadService $leadService
    )
    {}

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function dataTableLeads(Request $request): JsonResponse
    {
        $filters = Helpers::filters($request);

        $filters['filterName'] = $request['filterName'];

        return $this->leadService->dataTableLeads($filters);
    }
}
