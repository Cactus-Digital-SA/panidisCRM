<?php

namespace App\Domains\Projects\Http\Controllers;

use App\Domains\Projects\Services\ProjectService;
use App\Helpers\Global\DataTablesHelper;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ProjectDTController extends Controller
{

    /**
     * @param ProjectService $projectService
     */
    public function __construct(protected ProjectService $projectService)
    {}

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function dataTableProjects(Request $request): JsonResponse
    {
        $filters = Helpers::filters($request);

        $filters['filterName'] = $request['filterName'];
        $filters['filterOwner'] = $request['filterOwner'];
        $filters['filterStartDate'] = $request['filterStartDate'];
        $filters['filterDeadline'] = $request['filterDeadline'];
        $filters['filterStatus'] = $request['filterStatus'];
        $filters['filterAssignees'] = $request['filterAssignees'];
        $filters['filterPriority'] = $request['filterPriority'];
        $filters['filterClient'] = $request['filterClient'];
        $filters['filterCompany'] = $request['filterCompany'];

        $filters['projectTypeId'] =  $request['projectTypeId'];
        $filters['projectTypeSlug'] =  $request['projectTypeSlug'];
        $filters['projectMine'] =  $request['projectMine'];


        return $this->projectService->dataTableProjects($filters);
    }

}
