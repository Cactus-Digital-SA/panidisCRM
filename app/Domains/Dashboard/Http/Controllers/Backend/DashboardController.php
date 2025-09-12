<?php

namespace App\Domains\Dashboard\Http\Controllers\Backend;

use App\Domains\Visits\Services\VisitService;
use Illuminate\Support\Facades\Auth;

class DashboardController
{
    public function __construct(
        private VisitService $visitService,
    )
    {}

    public function index()
    {
        if (Auth::check()) {
            $visitsColumns = $this->visitService->getDashboardTableColumns();

            return view('backend.dashboard', compact('visitsColumns'));
        }

        return view('welcome');
    }
}
