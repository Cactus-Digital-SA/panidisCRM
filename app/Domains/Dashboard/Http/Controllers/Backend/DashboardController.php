<?php

namespace App\Domains\Dashboard\Http\Controllers\Backend;

use App\Domains\Erp\Services\ErpService;
use App\Domains\ErpSales\Services\SalesmanService;
use App\Domains\Sectors\Services\SectorService;
use App\Domains\Visits\Services\VisitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

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

    public function customerSalesReport(Request $request)
    {
        $erpService = app(ErpService::class);

        $customerCode = request('customerCode');
        $page = request('page', 1);

        $responseData = $erpService->getCustomerSalesReport($customerCode);
        $html = $erpService->getReport($responseData, $page);

        $maxPages = $responseData->getPages() ?? 1;
        return view('backend.reports.CustomerSalesReport', compact('html', 'customerCode', 'maxPages'));
    }

    public function customerCard(Request $request)
    {
        $erpService = app(ErpService::class);

        $customerCode = request('customerCode');
        $page = request('page', 1);

        $html = '';
        $error = null;
        $maxPages = 1;


        $responseData = $erpService->getCustomerLedgerReport($customerCode);
        $html = $erpService->getReport($responseData, $page);
        $maxPages = $responseData->getPages() ?? 1;

        $htmlDecode = json_decode($html);
        if ($htmlDecode !== null && property_exists($htmlDecode, 'success') && $htmlDecode->success === false) {
            $error = $htmlDecode->error ?? 'Δεν βρέθηκαν δεδομένα.';
        }


        return view('backend.reports.CustomerCard', compact('html', 'customerCode', 'maxPages', 'error'));
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function salesWidget()
    {
        $salesman = request('salesman');
        $productCode = request('productCode');
        $categoryProduct = request('categoryProduct');
        $area = request('area');

        $filters = [
            'salesman' => $salesman,
            'productCode' => $productCode,
            'categoryProduct' => $categoryProduct,
            'area' => $area
        ];

        $erpService = app(ErpService::class);
        $salesmanService = app(SalesmanService::class);
        $sectorService = app(SectorService::class);

        $salesmen  = $salesmanService->get();
        $salesData = $erpService->getSalesDashboardData($filters);
        $areas = $sectorService->get();

        return view('backend.dashboard-sales', compact('salesData', 'salesmen', 'areas'));
    }

    public function customerRevenue(Request $request)
    {
        $erpService = app(ErpService::class);

        $customerCode = $request->get('customerCode');
        $dataArray = [];

//        if ($customerCode) {
        $dataArray = $erpService->getCustomerRevenueData($customerCode);
//        }

        return view('backend.reports.customer-revenue', compact('dataArray', 'customerCode'));
    }
}
