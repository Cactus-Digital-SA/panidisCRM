<?php

namespace App\Domains\Settings\Http\Controllers;

use App\Http\Controllers\Controller;

class LogController extends Controller
{
    public function index()
    {
        return view('content.logs.index');
    }
}
