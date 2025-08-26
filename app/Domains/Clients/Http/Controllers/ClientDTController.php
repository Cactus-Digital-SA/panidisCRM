<?php

namespace App\Domains\Clients\Http\Controllers;

use App\Domains\Clients\Services\ClientService;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ClientDTController extends Controller
{
    /**
     * @var ClientService
     */
    protected ClientService $clientService;

    /**
     * @param ClientService $clientService
     */
    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function dataTableClients(Request $request): JsonResponse
    {
        $filters = Helpers::filters($request);

        $filters['filterName'] = $request['filterName'];
        // $filters['filterTypeId'] = $request['filterTypeId'];

        return $this->clientService->dataTableClients($filters);
    }
}
