<!DOCTYPE html>
@php
    use Illuminate\Support\Str;
    use App\Helpers\Helpers;

    $menuFixed =
        $configData['layout'] === 'vertical'
            ? $menuFixed ?? ''
            : ($configData['layout'] === 'front'
                ? ''
                : $configData['headerType']);
    $navbarType =
        $configData['layout'] === 'vertical'
            ? $configData['navbarType']
            : ($configData['layout'] === 'front'
                ? 'layout-navbar-fixed'
                : '');
    $isFront = ($isFront ?? '') == true ? 'Front' : '';
    $contentLayout = isset($container) ? ($container === 'container-xxl' ? 'layout-compact' : 'layout-wide') : '';

    // Get skin name from configData - only applies to admin layouts
    $isAdminLayout = !Str::contains($configData['layout'] ?? '', 'front');
    $skinName = $isAdminLayout ? $configData['skinName'] ?? 'default' : 'default';

    // Get semiDark value from configData - only applies to admin layouts
    $semiDarkEnabled = $isAdminLayout && filter_var($configData['semiDark'] ?? false, FILTER_VALIDATE_BOOLEAN);

    // Generate primary color CSS if color is set
    $primaryColorCSS = '';
    if (isset($configData['color']) && $configData['color']) {
        $primaryColorCSS = Helpers::generatePrimaryColorCSS($configData['color']);
    }

@endphp

<html lang="{{ session()->get('locale') ?? app()->getLocale() }}"
      class="{{ $navbarType ?? '' }} {{ $contentLayout ?? '' }} {{ $menuFixed ?? '' }} {{ $menuCollapsed ?? '' }} {{ $footerFixed ?? '' }} {{ $customizerHidden ?? '' }}"
      dir="{{ $configData['textDirection'] }}" data-skin="{{ $skinName }}" data-assets-path="{{ asset('/assets') . '/' }}"
      data-base-url="{{ url('/') }}" data-framework="laravel" data-template="{{ $configData['layout'] }}-menu-template"
      data-bs-theme="{{ $configData['theme'] }}" @if ($isAdminLayout && $semiDarkEnabled) data-semidark-menu="true" @endif>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>@yield('title') | {{ config('appVariables.templateName') ? config('appVariables.templateName') : 'TemplateName' }}</title>
    <meta name="description" content="{{ config('appVariables.templateDescription') ? config('appVariables.templateDescription') : '' }}" />
    <meta name="keywords" content="{{ config('appVariables.templateKeyword') ? config('appVariables.templateKeyword') : '' }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="canonical" href="{{ config('appVariables.productPage') ? config('appVariables.productPage') : '' }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.png') }}" />

    @include('backend/layouts/sections/styles')
    @include('backend/layouts/sections/scriptsIncludes')
    @stack('after-styles')
</head>
<body>
<!-- Layout Content -->
  @yield('layoutContent')
  @yield('modals')
  @stack('extra-modals')
  <!--/ Layout Content -->
  @include('backend/layouts/sections/scripts')
  @stack('after-scripts')
</body>
</html>
