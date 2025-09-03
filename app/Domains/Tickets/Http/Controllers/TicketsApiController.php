<?php

namespace App\Domains\Tickets\Http\Controllers;

use App\Domains\Tickets\Services\TicketService;
use App\Domains\Tickets\Services\TicketStatusService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TicketsApiController extends Controller
{
    public function __construct(
        private readonly TicketService $ticketService,
        private readonly TicketStatusService $ticketStatusService
    )
    {}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function searchPaginated(Request $request){
        $validated = $request->validate([
            'page' => 'required|integer',
            'term' => 'nullable|string',
        ]);

        $page = $validated['page'];
        $resultCount = 20;

        $offset = ($page - 1) * $resultCount;

        /**
         * result['data']
         * result['count']
         */
        $result = $this->ticketService->searchPaginated($validated['term'], $offset, $resultCount);


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
}
