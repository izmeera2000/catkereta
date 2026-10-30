<aside class="nk-sidebar" data-content="sidebarMenu" aria-label="Main navigation">
  <div class="nk-sidebar-inner" data-simplebar>
    <ul class="nk-menu nk-menu-md">
      <li class="nk-menu-heading">
        <h6 class="overline-title text-primary-alt">Dashboard &amp; Panels</h6>
      </li>
      <li class="nk-menu-item {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
        <a href="{{ route('staff.dashboard') }}" class="nk-menu-link">
          <span class="nk-menu-icon"><em class="icon ni ni-dashlite"></em></span>
          <span class="nk-menu-text">Bookings</span>
        </a>
      </li>
    </ul>
  </div>
</aside>
