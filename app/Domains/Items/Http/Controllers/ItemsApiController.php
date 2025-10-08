<?php

namespace App\Domains\Items\Http\Controllers;

use App\Domains\Items\Services\ItemService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemsApiController extends Controller
{
    /**
     * @param ItemService $itemService
     */
    public function __construct(private readonly ItemService $itemService)
    {}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function itemsPaginated(Request $request) : JsonResponse
    {
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
        $result = $this->itemService->itemsPaginated($validated['term'], $offset, $resultCount);


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
