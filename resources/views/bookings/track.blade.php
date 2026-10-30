@extends('layouts.public')

@section('title', 'Track a service')
@section('meta_description', 'Use your Cat Kereta service number to check your booking.')

@section('content')
  <div class="nk-block-head wide-sm mx-auto text-center">
    <div class="nk-block-head-content">
      <a href="{{ route('bookings.home') }}" class="ck-back-link">← Back to Cat Kereta</a>
      <h1 class="nk-block-title page-title">Track a service</h1>
      <div class="nk-block-des">
        <p>Enter the service number you received when you made your booking.</p>
      </div>
    </div>
  </div>

  <div class="nk-block wide-sm mx-auto">
    <div class="card card-bordered">
      <div class="card-inner card-inner-lg">
        @if($errors->has('service_number'))
          <div class="alert alert-danger" role="alert">{{ $errors->first('service_number') }}</div>
        @endif
        <form method="POST" action="{{ route('bookings.track') }}">
          @csrf
          <div class="form-group">
            <label class="form-label" for="service_number">Service number</label>
            <input
              id="service_number"
              name="service_number"
              class="form-control"
              value="{{ old('service_number') }}"
              placeholder="SRV-XXXXXXXXXXXX"
              maxlength="16"
              autocapitalize="characters"
              spellcheck="false"
              autocomplete="off"
              aria-describedby="service-number-note"
              required
            />
          </div>
          <button type="submit" class="btn btn-lg btn-primary">Track my service</button>
        </form>
        <p id="service-number-note" class="text-soft mt-3 mb-0">Your service number gives access to booking details and photos. Keep it private.</p>
      </div>
    </div>
    <p class="text-center mt-3">Need to book a new service? <a href="{{ route('bookings.create') }}">Start a booking</a>.</p>
  </div>
@endsection
