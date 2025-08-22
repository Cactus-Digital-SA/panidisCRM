<?php

namespace App\Domains\ExtraData\Http\Controllers;

use App\Domains\ExtraData\Services\ExtraDataService;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExtraDataDTController extends Controller
{
    public function __construct(
        protected ExtraDataService $extraDataService
    )
    {}

    public function dataTableExtraData(Request $request): JsonResponse
    {
        $filters = Helpers::filters($request);

        return $this->extraDataService->dataTableExtraData($filters);
    }
}
