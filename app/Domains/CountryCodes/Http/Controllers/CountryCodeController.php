<?php

namespace App\Domains\CountryCodes\Http\Controllers;

use App\Domains\CountryCodes\Models\CountryCode;
use App\Domains\CountryCodes\Services\CountryCodeService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CountryCodeController extends Controller
{
    /**
     * @var CountryCodeService
     */
    protected CountryCodeService $countryCodeService;

    public function __construct(CountryCodeService $countryCodeService)
    {
        $this->countryCodeService = $countryCodeService;
    }

    public function index()
    {
        return view('backend.content.geoData.areas.index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {

        $this->countryCodeService->store((new CountryCode())
            ->setCode($request['code'])
            ->setName($request['name'])
        );

        return redirect()->back()->with('success', 'Ο κωδικός της χώρας έχει αποθηκευτεί');
    }

    /**
     * @param Request $request
     * @param string $countryCodeId
     * @return RedirectResponse
     */
    public function update(Request $request, string $countryCodeId): RedirectResponse
    {
        $this->countryCodeService->update((new CountryCode())
            ->setCode($request['code'])
            ->setName($request['name'])
            , $countryCodeId);

        return redirect()->back()->with('success', 'Ο κωδικός της χώρας έχει ενημερωθεί');
    }

    /**
     * @param Request $request
     * @param string $countryCodeId
     * @return RedirectResponse
     */
    public function destroy(Request $request, string $countryCodeId): RedirectResponse
    {
        $response = $this->countryCodeService->deleteById($countryCodeId);

        if ($response) {
            return redirect()->back()->with('success', 'Ο κωδικός της χώρας διαγράφηκε με επιτυχία!');
        }
        return redirect()->back()->with('error', 'Υπήρξε κάποιο πρόβλημα κατά την διαγραφή!');
    }

}
