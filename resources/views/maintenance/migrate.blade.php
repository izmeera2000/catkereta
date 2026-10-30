@extends('layouts.public')

@section('title', 'Run database migrations')

@section('content')
  <div class="nk-block-head wide-sm mx-auto text-center">
    <div class="nk-block-head-content">
      <h1 class="nk-block-title page-title">Run database migrations</h1>
      <div class="nk-block-des">
        <p>This temporary page applies pending Laravel database migrations. It does not roll back or reset the database.</p>
      </div>
    </div>
  </div>

  <div class="nk-block wide-sm mx-auto">
    @if(session('migration_output'))
      <div class="alert alert-success" role="status">
        <strong>Migrations finished.</strong>
        <pre class="mt-2 mb-0">{{ session('migration_output') }}</pre>
      </div>
    @endif

    @if($errors->has('migration'))
      <div class="alert alert-danger" role="alert">
        <strong>Migration failed.</strong>
        <pre class="mt-2 mb-0">{{ $errors->first('migration') }}</pre>
      </div>
    @endif

    <div class="card card-bordered">
      <div class="card-inner">
        <div class="alert alert-warning" role="alert">
          This page is publicly reachable while <code>MIGRATIONS_ROUTE_ENABLED=true</code>. Turn it off immediately after use.
        </div>

        <form method="POST" action="{{ route('maintenance.migrations.store') }}" onsubmit="return confirm('Run pending database migrations now?');">
          @csrf
          <button type="submit" class="btn btn-lg btn-primary">Run pending migrations</button>
        </form>
      </div>
    </div>
  </div>
@endsection
