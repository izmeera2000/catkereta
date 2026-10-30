<div class="nk-block-head nk-block-head-sm">
  <div class="nk-block-between">
    <div class="nk-block-head-content">
      <h3 class="nk-block-title page-title">@yield('page_title', 'Dashboard')</h3>
      @hasSection('page_description')
        <div class="nk-block-des text-soft">
          <p>@yield('page_description')</p>
        </div>
      @endif
    </div>
    <div class="nk-block-head-content">
      <nav aria-label="Breadcrumb">
        <ol class="breadcrumb">
          @hasSection('breadcrumbs')
            @yield('breadcrumbs')
          @else
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">@yield('page_title', 'Dashboard')</li>
          @endif
        </ol>
      </nav>
    </div>
  </div>
</div>
