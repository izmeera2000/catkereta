    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="description" content="@yield('meta_description', 'Dashboard application')" />
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" />
    <title>@yield('title', 'Dashboard') | {{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/dashlite.css') }}" />
    <link id="skin-default" rel="stylesheet" href="{{ asset('assets/css/theme.css') }}" />
    @stack('styles')
