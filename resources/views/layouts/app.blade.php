<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="js">
  <head>
    @include('layouts.partials.head')
  </head>
  <body class="nk-body npc-default has-apps-sidebar has-sidebar">
    <div class="nk-app-root">
      <div class="nk-apps-sidebar is-theme">
        <div class="nk-apps-brand">
          <a href="{{ route('staff.dashboard') }}" class="logo-link" aria-label="{{ config('app.name') }}">
            <img class="logo-light logo-img" src="{{ asset('images/logo-small.png') }}" alt="{{ config('app.name') }}" />
            <img class="logo-dark logo-img" src="{{ asset('images/logo-dark-small.png') }}" alt="{{ config('app.name') }}" />
          </a>
        </div>
        <div class="nk-sidebar-element">
          <div class="nk-sidebar-body">
            <div class="nk-sidebar-content" data-simplebar>
              <div class="nk-sidebar-menu">
                <ul class="nk-menu apps-menu">
                  <li class="nk-menu-item">
                    <a href="{{ route('staff.dashboard') }}" class="nk-menu-link" title="Dashboard">
                      <span class="nk-menu-icon"><em class="icon ni ni-dashlite"></em></span>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="nk-main">
        <div class="nk-wrap">
          @include('layouts.partials.navbar')
          @include('layouts.partials.sidenav')

          <main class="nk-content">
            <div class="container-fluid">
              <div class="nk-content-inner">
                <div class="nk-content-body">
                  @include('layouts.partials.breadcrumbs')
                  @yield('content')
                </div>
              </div>
            </div>
          </main>

          @include('layouts.partials.footer')
        </div>
      </div>
    </div>

    @include('layouts.partials.scripts')
  </body>
</html>
