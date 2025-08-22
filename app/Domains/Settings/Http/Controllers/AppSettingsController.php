<?php

namespace App\Domains\Settings\Http\Controllers;

use App\Domains\Settings\Http\Requests\ShowSettingsRequest;
use App\Domains\Settings\Services\SettingsServices;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class AppSettingsController extends Controller
{
    protected SettingsServices $settingsServices;

    /**
     * @param SettingsServices $settingsServices
     */
    public function __construct(SettingsServices $settingsServices)
    {
        $this->settingsServices = $settingsServices;
    }

    /**
     * @param ShowSettingsRequest $request
     * @param $setting
     * @return RedirectResponse
     */
    public function clear_settings(ShowSettingsRequest $request,$setting)
    {
        $msg = '';
        switch ($setting):
            case 'config':
                \Artisan::call('config:clear');
                $msg = 'config';
                break;
            case 'cache':
                \Artisan::call('cache:clear');
                $msg = 'cache';
                break;
            case 'views':
                \Artisan::call('view:clear');
                $msg = 'views';
                break;
            case 'routes':
                \Artisan::call('route:clear');
                $msg = 'routes';
                break;
        endswitch;

        return redirect()->back()->with('status',$msg.' Cleared!');
    }

    /**
     * @param ShowSettingsRequest $request
     * @param $setting
     * @return RedirectResponse
     */
    public function cache_settings(ShowSettingsRequest $request, $setting)
    {
        $msg = '';
        switch ($setting):
            case 'config':
                \Artisan::call('config:cache');
                $msg = 'config';
                break;
            case 'views':
                \Artisan::call('view:cache');
                $msg = 'views';
                break;
            case 'routes':
                \Artisan::call('route:cache');
                $msg = 'routes';
                break;
        endswitch;

        return redirect()->back()->with('status',$msg.' Cached!');
    }



    /**
     * @return RedirectResponse
     */
    public function optimize_app()
    {
        \Artisan::call('config:cache');
        \Artisan::call('view:cache');
//        \Artisan::call('route:cache');

        return redirect()->back()->with('status','App Optimized!');
    }

    /**
     * @return RedirectResponse
     */
    public function optimize_clear()
    {
        \Artisan::call('optimize:clear');
        return redirect()->back()->with('status','All Cache Cleared!');
    }


    /**
     * @return mixed
     */
    public function index ()
    {
        $arr['Artisan Commands'] = $this->settingsServices->get_artisan_commands();

        $arr['Optimizations'] = $this->settingsServices->optimizations();


        return view("backend.content.settings.index")
            ->withAppSettings($arr);
    }


}
