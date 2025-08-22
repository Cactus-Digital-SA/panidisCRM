<?php
namespace App\Domains\Settings\Services;

class SettingsServices
{
    /**
     * @return array
     */
    public function get_artisan_commands(): array
    {
        $groups=[];
        $object = new \stdClass();
        $object->available = app()->configurationIsCached();
        $object->name = 'Clear Config';
        $object->clear_url = route('admin.clear-settings','config');
        $object->cache_url = route('admin.cache-settings','config');
        $groups[] = $object;

        $object = new \stdClass();
        $object->available = true;
        $object->name = 'Clear Cache';
        $object->clear_url = route('admin.clear-settings','cache');
        $object->cache_url = '';
        $groups[] = $object;

        $object = new \stdClass();
        $object->available = true;
        $object->name = 'Clear View';
        $object->clear_url = route('admin.clear-settings','views');
        $object->cache_url = route('admin.cache-settings','views');
        $groups[] = $object;

        $object = new \stdClass();
        $object->available = app()->routesAreCached();
        $object->name = 'Clear Route';
        $object->clear_url = route('admin.clear-settings','routes');
        $object->cache_url = route('admin.cache-settings','routes');
        $groups[] = $object;

        return $groups;
    }

    public function optimizations(): array
    {
        $groups=[];
        $object = new \stdClass();
        $object->available = app()->configurationIsCached();
        $object->name = 'Optimize All';
        $object->clear_url = route('admin.optimize-clear');
        $object->cache_url = route('admin.optimize-app');
        $groups[] = $object;

        return $groups;
    }
}
