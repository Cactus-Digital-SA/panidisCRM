<?php

namespace App\Domains\Clients\Http\Controllers;

use App\Domains\Clients\Services\ClientService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ClientApiController extends Controller
{

    /**
     * @param ClientService $clientService
     */
    public function __construct(private readonly ClientService $clientService)
    {}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function namesPaginated(Request $request) : JsonResponse
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
        $result = $this->clientService->namesPaginated($validated['term'], $offset, $resultCount);


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
    public function getClientById(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id' => 'required',
        ]);

        $client = $this->clientService->getById($validated['id']);

        return response()->json($client->getValues(false));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getClientWithCompanyByClientId(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id' => 'required',
        ]);

        $client = $this->clientService->getById($validated['id']);

        return response()->json(['client' => $client->getValues(false),'company'=> $client->getCompany()->getValues(false)]);
    }
}
