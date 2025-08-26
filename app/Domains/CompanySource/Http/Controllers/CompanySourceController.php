<?php

namespace App\Domains\CompanySource\Http\Controllers;

use App\Domains\CompanySource\Models\CompanySource;
use App\Domains\CompanySource\Services\CompanySourceService;
use App\Domains\CompanySource\Http\Requests\DeleteCompanySourceRequest;
use App\Domains\CompanySource\Http\Requests\StoreCompanySourceRequest;
use App\Domains\CompanySource\Http\Requests\UpdateCompanySourceRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

final class CompanySourceController extends Controller
{
    protected CompanySourceService $companySourceService;

    /**
     * @param CompanySourceService $companySourceService
     */
    public function __construct(CompanySourceService $companySourceService)
    {
        $this->companySourceService = $companySourceService;
    }

    /**
     * Summary of index
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('backend.content.companySource.index');
    }

    /**
     * @param StoreCompanySourceRequest $request
     * @return RedirectResponse
     */
    public function store(StoreCompanySourceRequest $request): RedirectResponse
    {

        $this->companySourceService->store(CompanySource::fromRequest($request));

        return redirect()->back()->with('success', 'Επιτυχής αποθήκευση!');
    }

    /**
     * @param UpdateCompanySourceRequest $request
     * @param string $companySourceId
     * @return RedirectResponse
     */
    public function update(UpdateCompanySourceRequest $request, string $companySourceId): RedirectResponse
    {
        $this->companySourceService->update(CompanySource::fromRequest($request), $companySourceId);

        return redirect()->back()->with('success', 'Επιτυχής αποθήκευση!');
    }

    /**
     * @param DeleteCompanySourceRequest $request
     * @param string $companySourceId
     * @return RedirectResponse
     */
    public function destroy(DeleteCompanySourceRequest $request, string $companySourceId): RedirectResponse
    {
        $response = $this->companySourceService->deleteById($companySourceId);

        if ($response) {
            return redirect()->back()->with('success', 'Επιτυχής διαγραφή!');
        }
        return redirect()->back()->with('error', 'Υπήρξε κάποιο πρόβλημα κατά την διαγραφή!');
    }
}
