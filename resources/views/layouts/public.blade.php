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
                <a href="{{ route('bookings.create') }}" class="logo-link">
                  <img class="logo-dark logo-img" src="{{ asset('images/logo-dark.png') }}" alt="{{ config('app.name') }}" />
                </a>
                <div class="nk-header-tools">
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
