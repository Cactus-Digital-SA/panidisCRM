<?php

namespace App\Domains\Quotes\Http\Controllers;

use App\Domains\Quotes\Services\QuoteService;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuoteDTController extends Controller
{
    /**
     * @var QuoteService
     */
    public function __construct(
        protected QuoteService $quoteService
    )
    {}

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function dataTableQuotes(Request $request): JsonResponse
    {
        $filters = Helpers::filters($request);
        $filters['filterName'] = $request['filterName'];

        return $this->quoteService->dataTableQuotes($filters);
    }
}
