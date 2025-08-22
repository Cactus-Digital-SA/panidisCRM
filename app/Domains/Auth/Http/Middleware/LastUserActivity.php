<?php

namespace App\Domains\Auth\Http\Middleware;

use Closure;
use Auth;
use Cache;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LastUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            if (!Cache::get('user-is-online-'. Auth::user()->id)) {
                $expiresAt = Carbon::now()->addMinutes(2);
                Cache::put('user-is-online-' . Auth::user()->id, true, $expiresAt);
            }
        }
        return $next($request);
    }
}
