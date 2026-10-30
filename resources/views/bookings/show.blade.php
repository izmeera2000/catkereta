@extends('layouts.public')

@section('title', 'Booking progress')

@section('content')
  @if(session('booking_created'))
    <div class="alert alert-success alert-icon">
      <em class="icon ni ni-check-circle"></em>
      <strong>Request received.</strong> We’ll contact you to confirm your preferred date.
      Save this private link to check your booking and see progress photos.
    </div>
  @endif

  <div class="nk-block-head wide-md mx-auto text-center">
    <div class="nk-block-head-content">
      <span class="badge badge-dim bg-primary">{{ \App\Models\PaintBooking::STATUSES[$booking->status] ?? $booking->status }}</span>
      <h1 class="nk-block-title page-title mt-2">Your paint booking</h1>
      <div class="nk-block-des">
        <p>{{ $booking->vehicle_year ? $booking->vehicle_year.' ' : '' }}{{ $booking->vehicle_make }} {{ $booking->vehicle_model }} · {{ \App\Models\PaintBooking::SERVICES[$booking->service_type] ?? $booking->service_type }}</p>
        @if($booking->preferred_date)
          <p>Preferred date: {{ $booking->preferred_date->format('F j, Y') }}</p>
        @endif
      </div>
    </div>
  </div>

  <div class="nk-block">
    <div class="card card-bordered">
      <div class="card-inner">
        <h5 class="title">Work in progress</h5>
        <p class="text-soft">Photos are private and can only be viewed through this link. Keep it safe and share it only with people you trust.</p>
      </div>
    </div>
  </div>

  @foreach(\App\Models\PaintBooking::PHOTO_STAGES as $stage => $label)
    <div class="nk-block">
      <div class="nk-block-head nk-block-head-sm">
        <h5 class="nk-block-title">{{ $label }}</h5>
      </div>
      <div class="row g-gs">
        @forelse($booking->photos->where('stage', $stage) as $photo)
          <div class="col-sm-6 col-lg-4">
            <div class="card card-bordered">
              <img
                src="{{ \Illuminate\Support\Facades\URL::signedRoute('bookings.photos.show', ['booking' => $booking->public_id, 'photo' => $photo->id], null, false) }}"
                alt="{{ $photo->caption ?: $label.' paint progress photo' }}"
                class="card-img-top"
                loading="lazy"
              />
              @if($photo->caption)
                <div class="card-inner py-3"><p class="mb-0">{{ $photo->caption }}</p></div>
              @endif
            </div>
          </div>
        @empty
          <div class="col-12"><p class="text-soft">No {{ strtolower($label) }} photos have been added yet.</p></div>
        @endforelse
      </div>
    </div>
  @endforeach
@endsection
