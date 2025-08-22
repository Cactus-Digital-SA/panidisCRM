<?php

namespace App\Domains\Auth\Http\Controllers\DataTable;

use App\Domains\Auth\Services\RoleService;
use App\Domains\Auth\Services\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DataTableController extends Controller
{
    protected UserService $userService;
    protected RoleService $roleService;


    public function __construct(UserService $userService, RoleService $roleService)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
    }

    /**
     * @param Request $request
     * @return JsonResponse //Datatable
     */
    public function users(Request $request): JsonResponse
    {

        $columnIndex = $request['order'][0]['column'] ?? 0; // Column index
        $columnName = $request['columns'][$columnIndex]['name']; // Order Column name
        $columnSortOrder = $request['order'][0]['dir'] ?? 'asc'; // asc or desc

        $filters = [];
        $filters['columnName'] = $columnName;
        $filters['columnSortOrder'] = $columnSortOrder;
        $filters['filterName'] = $request['filterName'];
        $filters['filterUserEmail'] = $request['filterUserEmail'];
        $filters['filterRole'] = $request['filterRole'];
        $filters['status'] = $request['status'];
        $filters['active'] = $request['active'];

        return $this->userService->usersDatatable($filters);
    }

    /**
     * @return JsonResponse //Datatable
     */
    public function roles(): JsonResponse
    {
        return $this->roleService->rolesDatatable();
    }

}
