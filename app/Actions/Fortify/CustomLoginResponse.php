<?php

namespace App\Actions\Fortify;

use App\Domains\Auth\Models\RolesEnum;
use Illuminate\Support\Facades\Log;
use Laravel\Fortify\Contracts\LoginResponse;

class CustomLoginResponse implements LoginResponse
{

    /**
     * @inheritDoc
     */
    public function toResponse($request)
    {
        Log::info('Είσοδος Χρήστη: '. $request->user()?->name);

        $user = $request->user();

        if ($user->hasRole(RolesEnum::Administrator->value) || $user->hasRole(RolesEnum::SuperAdmin->value)) {
            return redirect()->route('admin.home');
        }

        return redirect()->route('home');
    }
}
