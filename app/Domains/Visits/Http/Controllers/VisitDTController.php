<?php

namespace App\Domains\Visits\Http\Controllers;

use App\Domains\Visits\Enums\VisitNextActionSourceEnum;
use App\Domains\Visits\Services\VisitService;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class VisitDTController extends Controller
{

    /**
     * @param VisitService $visitService
     */
    public function __construct(protected VisitService $visitService)
    {}

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function dataTableVisits(Request $request): JsonResponse
    {
        $filters = Helpers::filters($request);

        $filters['filterName'] = $request['filterName'];
        $filters['filterOwner'] = $request['filterOwner'];
        $filters['filterStartDate'] = $request['filterStartDate'];
        $filters['filterDeadline'] = $request['filterDeadline'];
        $filters['filterStatus'] = $request['filterStatus'];
        $filters['filterAssignees'] = $request['filterAssignees'];
        $filters['filterPriority'] = $request['filterPriority'];
        $filters['filterCompany'] = $request['filterCompany'];

        $filters['assignedBy'] =  $request['assignedBy'];
        if($request['morphableType']) {
            $filters['morphableType'] = $request['morphableType'];
            $filters['morphableId'] = $request['morphableId'];
        }

        return $this->visitService->dataTableVisits($filters);
    }


    public function dataTableFollowUp(Request $request): JsonResponse
    {
        $filters = Helpers::filters($request);
        $filters['nextAction'] = VisitNextActionSourceEnum::FOLLOW_UP->value;

        return $this->visitService->dataTableVisits($filters);
    }

    public function dataTableOpen(Request $request): JsonResponse
    {
        $filters = Helpers::filters($request);
        $filters['nextAction'] = VisitNextActionSourceEnum::OPEN->value;

        return $this->visitService->dataTableVisits($filters);
    }
}
