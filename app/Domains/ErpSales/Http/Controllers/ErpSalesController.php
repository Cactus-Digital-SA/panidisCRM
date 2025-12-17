<?php

namespace App\Domains\ErpSales\Http\Controllers;

use App\Domains\Auth\Models\RolesEnum;
use App\Domains\Auth\Services\UserService;
use App\Domains\Erp\Services\ErpService;
use App\Domains\ErpSales\Http\Requests\ManageErpSalesRequest;
use App\Domains\ErpSales\Services\SalesmanService;
use App\Domains\ErpSales\Enums\MonthEnum;
use App\Domains\Sectors\Services\SectorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ErpSalesController
{
    public function __construct(private SalesmanService $salesmanService, private UserService $userService)
    {}

    public function customerSalesReport(ManageErpSalesRequest $request)
    {
        $erpService = app(ErpService::class);

        $customerCode = request('customerCode');
        $page = request('page', 1);

        $responseData = $erpService->getCustomerSalesReport($customerCode);
        $html = $erpService->getReport($responseData, $page);

        $maxPages = $responseData->getPages() ?? 1;
        return view('backend.reports.CustomerSalesReport', compact('html', 'customerCode', 'maxPages'));
    }

    public function customerCard(ManageErpSalesRequest $request)
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
    public function salesWidget(ManageErpSalesRequest $request)
    {
        $user = $this->userService->getAuthUser();
        $eloquentUser = Auth::user();

        $productCode = request('productCode');
        $categoryProduct = request('categoryProduct');
        $area = request('area');

        if ($eloquentUser->hasRole([RolesEnum::Administrator->value, RolesEnum::SuperAdmin->value])) {
            $salesman = $request->get('salesman');
        } elseif($eloquentUser->hasRole([RolesEnum::SALES_DIRECTOR->value])){
            $salesman = $request->get('salesman');
        } else{
            $salesman = $user->getSalesman()?->getName();
            if(!$salesman){
                abort(404);
            }
        }


        $filters = [
            'salesman' => $salesman,
            'productCode' => $productCode,
            'categoryProduct' => $categoryProduct,
            'area' => $area
        ];

        $erpService = app(ErpService::class);
        $salesmanService = app(SalesmanService::class);
        $sectorService = app(SectorService::class);

        $salesmen  = $salesmanService->getVisibleForUser($user->getId());

        $salesData = $erpService->getSalesDashboardData($filters);
        $areas = $sectorService->get();

//        if ($request->get('widgetContext')) {
//            return view('backend.widgets.sales-overview', compact('salesData'));
//        }

        return view('backend.dashboard-sales', compact('salesData', 'salesmen', 'areas'));
    }

    public function salesTarget(Request $request)
    {
        $user = $this->userService->getAuthUser();
        $eloquentUser = Auth::user();
        // default current month
        $month = $request->get('month', now()->month);

        if ($eloquentUser->hasRole([RolesEnum::Administrator->value, RolesEnum::SuperAdmin->value])) {
            $salesman = $request->get('salesman');
        } elseif($eloquentUser->hasRole([RolesEnum::SALES_DIRECTOR->value])){
            $salesman = $request->get('salesman');
        } else{
            $salesman = $user->getSalesman()?->getErpId();
            if(!$salesman){
                abort(404);
            }
        }

        $filters = [
            'salesman' => $salesman,
            'month'    => $month,
        ];

        $erpService = app(ErpService::class);
        $salesData = $erpService->getSalesTarget($filters);

        // visible salesmen based on role + sectors
        $visibleSalesmen = $this->salesmanService->getVisibleForUser($user->getId());

        // Φιλτράρουμε τους Πωλητές με βάση την περιοχή που έχει ο Director
        if ($eloquentUser->hasRole([RolesEnum::SALES_DIRECTOR->value])) {
            $allowedErpIds = collect($visibleSalesmen)
                ->map(fn ($s) => $s->getErpId())
                ->filter()
                ->values()
                ->all();

            $salesData = array_filter($salesData, function ($row) use ($allowedErpIds) {
                return in_array($row->getSalesmanCode() ?? null, $allowedErpIds, true);
            });
        }


        $months = collect(MonthEnum::cases())->map(fn (MonthEnum $m) => [
            'value' => $m->number(),
            'label' => $m->label(),
        ]);



        return view('backend.sales-target', [
            'salesData' => $salesData,
            'salesmen'  => $visibleSalesmen,
            'months'    => $months,
            'activeMonth' => $month,
        ]);
    }


    public function customerRevenue(ManageErpSalesRequest $request)
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
