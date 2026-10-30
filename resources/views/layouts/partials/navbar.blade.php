<header class="nk-header nk-header-fixed is-light">
  <div class="container-fluid">
    <div class="nk-header-wrap">
      <div class="nk-menu-trigger d-xl-none ms-n1">
        <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu" aria-label="Toggle navigation">
          <em class="icon ni ni-menu"></em>
        </a>
      </div>
      <div class="nk-header-app-name">
        <div class="nk-header-app-logo">
          <em class="icon ni ni-dashlite bg-purple-dim"></em>
        </div>
        <div class="nk-header-app-info">
          <span class="sub-text">@yield('app_name', 'DashLite')</span>
          <span class="lead-text">@yield('page_title', 'Dashboard')</span>
        </div>
      </div>
      <div class="nk-header-menu is-light">
        <div class="nk-header-menu-inner">
          <ul class="nk-menu nk-menu-main">
            <li class="nk-menu-item {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
              <a href="{{ route('staff.dashboard') }}" class="nk-menu-link">
                <span class="nk-menu-text">Bookings</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
      <div class="nk-header-tools">
        <ul class="nk-quick-nav">
          <li class="dropdown user-dropdown">
            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-label="User menu">
              <div class="user-toggle">
                <div class="user-avatar sm"><em class="icon ni ni-user-alt"></em></div>
              </div>
            </a>
            <div class="dropdown-menu dropdown-menu-md dropdown-menu-end">
              <div class="dropdown-inner">
                <ul class="link-list">
                  <li>
                    <a href="{{ route('staff.dashboard') }}">
                      <em class="icon ni ni-dashlite"></em><span>Dashboard</span>
                    </a>
                  </li>
                  <li>
                    <form method="POST" action="{{ route('staff.logout') }}">
                      @csrf
                      <button class="btn btn-link" type="submit">
                        <em class="icon ni ni-signout"></em><span>Sign out</span>
                      </button>
                    </form>
                  </li>
                </ul>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</header>
