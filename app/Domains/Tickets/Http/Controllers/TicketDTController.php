<?php

namespace App\Domains\Tickets\Http\Controllers;

use App\Domains\Tickets\Services\TicketService;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class TicketDTController extends Controller
{

    /**
     * @param TicketService $ticketService
     */
    public function __construct(protected TicketService $ticketService)
    {}

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function dataTableTickets(Request $request): JsonResponse
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

        $filters['ticketMine'] =  $request['ticketMine'];
        $filters['assignedBy'] =  $request['assignedBy'];
        if($request['morphableType']) {
            $filters['morphableType'] = $request['morphableType'];
            $filters['morphableId'] = $request['morphableId'];
        }


        return $this->ticketService->dataTableTickets($filters);
    }

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

        $filters['ticketMine'] =  $request['ticketMine'];
        $filters['assignedBy'] =  $request['assignedBy'];
        if($request['morphableType']) {
            $filters['morphableType'] = $request['morphableType'];
            $filters['morphableId'] = $request['morphableId'];
        }


        return $this->ticketService->dataTableVisits($filters);
    }

}
