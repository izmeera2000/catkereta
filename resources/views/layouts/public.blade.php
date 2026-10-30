<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="js">
  <head>
    @include('layouts.partials.head')
  </head>
  <body class="nk-body npc-default">
    <div class="nk-app-root">
      <div class="nk-main">
        <div class="nk-wrap">
          <header class="nk-header nk-header-fixed is-light">
            <div class="container-fluid">
              <div class="nk-header-wrap">
                <a href="{{ route('bookings.home') }}" class="logo-link ck-wordmark">
                  <span>{{ config('app.name', 'Cat Kereta') }}</span>
                </a>
                <div class="nk-header-tools ck-header-actions">
                  <a href="{{ route('bookings.create') }}" class="btn btn-outline-primary me-2">Book a service</a>
                  <a href="{{ route('bookings.track.create') }}" class="btn btn-outline-primary me-2">Track a service</a>
                  <a href="{{ route('staff.login') }}" class="btn btn-outline-primary">Shop staff</a>
                </div>
              </div>
            </div>
          </header>
          <main class="nk-content">
            <div class="container">
              @yield('content')
            </div>
          </main>
          @include('layouts.partials.footer')
        </div>
      </div>
    </div>
    @include('layouts.partials.scripts')
  </body>
</html>
