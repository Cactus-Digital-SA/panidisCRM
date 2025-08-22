<?php

namespace App\Domains\Auth\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class PreventDuplicateSubmissions
{
    public function handle($request, Closure $next)
    {
        if ($request->isMethod('post')) {

            $data = $request->all();
            foreach ($data as $key => $value) {
                if ($value instanceof \Illuminate\Http\UploadedFile) {
                    unset($data[$key]);
                }
            }

            $key = 'submission:' . md5($request->fullUrl() . serialize($request->all()));
            // Block if the request is already being processed
            if (Cache::has($key)) {
                if($request->isJson() || $request->ajax()){
                    return response()->json(['Duplicate error' => 'Duplicate submission detected.'], 429);
                }
                return redirect()->back()->with('success', ' ');
            }

            // Cache the request (e.g., 5 seconds)
            Cache::put($key, true, 5);
        }

        return $next($request);
    }
}
