<?php

namespace App\Domains\Dashboard\Http\Controllers\Frontend;

use Auth;

/**
 * Class HomeController.
 */
class HomeController
{

    public function index()
    {
        if(Auth::guest()){
            return redirect()->route('login');
        }else{
            if (Auth::check()) {
                if (Auth::user()->isAdmin()) {
                    return redirect()->route('admin.home');
                }
                return redirect()->route('admin.home');
            }

            return view('welcome');
        }
    }
}
