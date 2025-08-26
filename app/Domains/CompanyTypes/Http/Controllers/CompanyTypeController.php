<?php

namespace App\Domains\CompanyTypes\Http\Controllers;

use App\Domains\CompanyTypes\Models\CompanyType;
use App\Domains\CompanyTypes\Services\CompanyTypeService;
use App\Domains\CompanyTypes\Http\Requests\DeleteCompanyTypeRequest;
use App\Domains\CompanyTypes\Http\Requests\StoreCompanyTypeRequest;
use App\Domains\CompanyTypes\Http\Requests\UpdateCompanyTypeRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

final class CompanyTypeController extends Controller
{
    protected CompanyTypeService $companyTypeService;

    /**
     * @param CompanyTypeService $companyTypeService
     */
    public function __construct(CompanyTypeService $companyTypeService)
    {
        $this->companyTypeService = $companyTypeService;
    }

    /**
     * Summary of index
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('backend.content.companyTypes.index');
    }

    /**
     * @param StoreCompanyTypeRequest $request
     * @return RedirectResponse
     */
    public function store(StoreCompanyTypeRequest $request): RedirectResponse
    {

        $this->companyTypeService->store(CompanyType::fromRequest($request));

        return redirect()->back()->with('success', 'Επιτυχής αποθήκευση!');
    }

    /**
     * @param UpdateCompanyTypeRequest $request
     * @param string $companyTypeId
     * @return RedirectResponse
     */
    public function update(UpdateCompanyTypeRequest $request, string $companyTypeId): RedirectResponse
    {
        $this->companyTypeService->update(CompanyType::fromRequest($request), $companyTypeId);

        return redirect()->back()->with('success', 'Επιτυχής αποθήκευση!');
    }

    /**
     * @param DeleteCompanyTypeRequest $request
     * @param string $companyTypeId
     * @return RedirectResponse
     */
    public function destroy(DeleteCompanyTypeRequest $request, string $companyTypeId): RedirectResponse
    {
        $response = $this->companyTypeService->deleteById($companyTypeId);

        if ($response) {
            return redirect()->back()->with('success', 'Επιτυχής διαγραφή!');
        }
        return redirect()->back()->with('error', 'Υπήρξε κάποιο πρόβλημα κατά την διαγραφή!');
    }
}
