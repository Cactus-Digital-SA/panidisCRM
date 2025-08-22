<?php

namespace App\Domains\Dashboard\Http\Controllers\Backend;

use Illuminate\Support\Facades\Auth;

class DashboardController
{
    public function index()
    {
        if (Auth::check()) {
            return view('backend.dashboard');
        }

        return view('welcome');
    }
}
