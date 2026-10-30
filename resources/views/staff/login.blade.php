@extends('layouts.public')

@section('title', 'Staff sign in')

@section('content')
  <div class="nk-block-head wide-xs mx-auto text-center">
    <div class="nk-block-head-content">
      <h1 class="nk-block-title page-title">Shop staff sign in</h1>
      <div class="nk-block-des">
        <p>Sign in to manage paint booking requests and add progress photos.</p>
      </div>
    </div>
  </div>
  <div class="nk-block wide-xs mx-auto">
    <div class="card card-bordered">
      <div class="card-inner">
        @if($errors->any())
          <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('staff.login.store') }}" class="form-validate">
          @csrf
          <div class="form-group">
            <label class="form-label" for="email">Email</label>
            <div class="form-control-wrap">
              <input id="email" name="email" type="email" class="form-control" value="{{ old('email') }}" autocomplete="username" required autofocus />
            </div>
          </div>
          <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <div class="form-control-wrap">
              <input id="password" name="password" type="password" class="form-control" autocomplete="current-password" required />
            </div>
          </div>
          <div class="form-group">
            <div class="custom-control custom-control-sm custom-checkbox">
              <input id="remember" name="remember" type="checkbox" class="custom-control-input" value="1" />
              <label class="custom-control-label" for="remember">Remember me</label>
            </div>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-lg btn-primary btn-block">Sign in</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
