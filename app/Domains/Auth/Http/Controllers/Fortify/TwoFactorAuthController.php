<?php
namespace App\Domains\Auth\Http\Controllers\Fortify;

use App\Domains\Auth\Services\UserService;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Fortify\Http\Requests\TwoFactorLoginRequest;

/**
 * Fortify 2FA Controller
 */
class TwoFactorAuthController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function confirm(Request $request)
    {
        $confirmed = $this->userService->confirmTwoFactorAuth( $request->code);

        if (!$confirmed) {
            return back()->withErrors('Invalid Two Factor Authentication code');
        }

        return back();
    }

    /**
     * @param TwoFactorLoginRequest $request
     * @return Application|Factory|View|\Illuminate\Foundation\Application|RedirectResponse|\Illuminate\View\View
     */
    public function recoveryPass(TwoFactorLoginRequest $request)
    {
        if (! $request->hasChallengedUser()) {
            return redirect()->route('login');
        }

        return view('frontend.auth.fortify.recovery-pass');
    }

    /**
     * @throws Exception
     */
    public function sendEmail(TwoFactorLoginRequest $request)
    {
        if (! $request->hasChallengedUser()) {
            return redirect()->route('login');
        }

        if($request->session()->has('login.id')){
            $userId = $request->session()->get('login.id');
            $this->userService->sendOTP($userId);
        }

        return true;
    }
}
